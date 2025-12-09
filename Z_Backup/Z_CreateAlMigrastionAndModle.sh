#!/usr/bin/env bash
set -euo pipefail

# ----------------------------
# Config
# ----------------------------
SQL_FILE="${1:-smart_edu_db.sql}"   # مسیر فایل SQL (اگر ندی همین اسم پیشفرض)
MIG_DIR="database/migrations"
MODEL_DIR="app/Models"

if [[ ! -f "$SQL_FILE" ]]; then
  echo "❌ SQL file not found: $SQL_FILE"
  exit 1
fi

mkdir -p "$MIG_DIR" "$MODEL_DIR"

echo "✅ Reading SQL from: $SQL_FILE"
echo "✅ Output migrations to: $MIG_DIR"
echo "✅ Output models to: $MODEL_DIR"
echo "-----------------------------------------"

# ----------------------------
# Write generator to a temp PHP file (NO heredoc to php)
# ----------------------------
PHP_TMP="$(mktemp /tmp/sql2laravel.XXXXXX.php)"

cat > "$PHP_TMP" <<'PHP'
<?php
// args
$sqlFile  = $argv[1];
$migDir   = rtrim($argv[2], '/');
$modelDir = rtrim($argv[3], '/');

$sql = file_get_contents($sqlFile);

// 1) Find CREATE TABLE blocks
preg_match_all('/CREATE TABLE\s+`([^`]+)`\s*\((.*?)\)\s*ENGINE=/s', $sql, $matches, PREG_SET_ORDER);

if (!$matches) {
    fwrite(STDERR, "No CREATE TABLE found.\n");
    exit(1);
}

// Helper: snake -> Studly + singular-ish
function studlySingular($table){
    $t = $table;
    if (str_ends_with($t, 'ies')) $t = substr($t,0,-3).'y';
    else if (str_ends_with($t, 'ses')) $t = substr($t,0,-2);
    else if (str_ends_with($t, 's') && !str_ends_with($t,'ss')) $t = substr($t,0,-1);

    return str_replace(' ', '', ucwords(str_replace('_',' ', $t)));
}

function isPivot($table, $cols){
    if (!isset($cols['id'])) {
        $idCols = 0; $total = 0;
        foreach ($cols as $c => $_){
            $total++;
            if (str_ends_with($c, '_id')) $idCols++;
        }
        return ($total <= 4 && $idCols >= 2);
    }
    return false;
}

// Map MySQL type to Schema builder string
function mapType($col, $typeRaw){
    $t = strtolower(trim($typeRaw));

    if (preg_match('/bigint\(\d+\)\s+unsigned/', $t)) {
        if ($col === 'id') return '$table->id();';
        return "\$table->unsignedBigInteger('$col');";
    }

    if (preg_match('/int\(\d+\)\s+unsigned/', $t)) {
        return "\$table->unsignedInteger('$col');";
    }

    if (preg_match('/tinyint\(\d+\)/', $t)) {
        if (strpos($t,'tinyint(1)') !== false) return "\$table->boolean('$col');";
        return "\$table->unsignedTinyInteger('$col');";
    }

    if (preg_match('/decimal\((\d+),(\d+)\)/', $t, $m)) {
        return "\$table->decimal('$col', {$m[1]}, {$m[2]});";
    }

    if (preg_match('/varchar\((\d+)\)/', $t, $m)) {
        return "\$table->string('$col', {$m[1]});";
    }

    if (str_starts_with($t, 'longtext')) return "\$table->longText('$col');";
    if (str_starts_with($t, 'text'))     return "\$table->text('$col');";

    if (str_starts_with($t, 'datetime'))  return "\$table->dateTime('$col');";
    if (str_starts_with($t, 'timestamp')) return "\$table->timestamp('$col');";
    if (str_starts_with($t, 'date'))      return "\$table->date('$col');";

    if (preg_match('/enum\((.+)\)/', $t, $m)){
        $vals = trim($m[1]);
        return "\$table->enum('$col', [{$vals}]);";
    }

    return "\$table->string('$col');";
}

$base = new DateTimeImmutable('now');
$sec  = 0;

foreach ($matches as $m) {
    $table = $m[1];
    $colsBlock = $m[2];

    if ($table === 'migrations') continue;

    $lines = preg_split('/\r?\n/', $colsBlock);
    $cols = [];

    foreach ($lines as $line) {
        $line = trim($line);
        if (!preg_match('/^`([^`]+)`\s+(.+?)(,)?$/', $line, $cm)) continue;

        $col = $cm[1];
        $typeAndRest = $cm[2];

        $parts = preg_split('/\s+/', $typeAndRest, 2);
        $type = $parts[0];
        $rest = $parts[1] ?? '';

        $cols[$col] = ['type'=>$type, 'rest'=>$rest];
    }

    $pivot = isPivot($table, $cols);

    $migName = "create_{$table}_table";
    $ts = $base->modify("+{$sec} seconds")->format('Y_m_d_His');
    $sec++;

    $migFile = "{$migDir}/{$ts}_{$migName}.php";

    $schemaLines = [];
    $hasCreatedAt = isset($cols['created_at']);
    $hasUpdatedAt = isset($cols['updated_at']);

    foreach ($cols as $col => $info){
        if (in_array($col, ['created_at','updated_at'])) continue;

        $line = mapType($col, $info['type']);

        if (stripos($info['rest'], 'default null') !== false
            || (stripos($info['rest'], 'null') !== false && stripos($info['rest'], 'not null') === false)) {
            if (!str_contains($line, '->nullable()') && !str_contains($line, 'id();')) {
                $line = rtrim($line, ';') . "->nullable();";
            }
        }

        if (stripos($info['rest'], 'unique') !== false) {
            if (!str_contains($line, '->unique()')) {
                $line = rtrim($line, ';') . "->unique();";
            }
        }

        $schemaLines[] = "            {$line}";
    }

    if ($hasCreatedAt && $hasUpdatedAt) {
        $schemaLines[] = "            \$table->timestamps();";
    } elseif ($hasCreatedAt) {
        $schemaLines[] = "            \$table->timestamp('created_at')->nullable();";
    }

    $migContent = <<<PHP2
<?php

use Illuminate\\Database\\Migrations\\Migration;
use Illuminate\\Database\\Schema\\Blueprint;
use Illuminate\\Support\\Facades\\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('{$table}', function (Blueprint \$table) {
PHP2;

    $migContent .= "\n" . implode("\n", $schemaLines) . "\n";
    $migContent .= <<<PHP3
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('{$table}');
    }
};
PHP3;

    file_put_contents($migFile, $migContent);
    echo "✅ Migration created: {$migFile}\n";

    sleep(2);

    if ($pivot) {
        echo "ℹ️ Pivot detected, model skipped for table: {$table}\n";
        continue;
    }

    $modelName = studlySingular($table);
    $modelFile = "{$modelDir}/{$modelName}.php";

    $fillable = array_filter(array_keys($cols), fn($c) => !in_array($c, ['id','created_at','updated_at']));
    $fillableStr = implode("', '", $fillable);

    $modelContent = <<<PHP4
<?php

namespace App\\Models;

use Illuminate\\Database\\Eloquent\\Factories\\HasFactory;
use Illuminate\\Database\\Eloquent\\Model;

class {$modelName} extends Model
{
    use HasFactory;

    protected \$table = '{$table}';

    protected \$fillable = ['{$fillableStr}'];
}
PHP4;

    file_put_contents($modelFile, $modelContent);
    echo "✅ Model created: {$modelFile}\n";

    sleep(2);
}

echo "-----------------------------------------\n";
echo "✅ Done. Review migrations for FK/index if needed.\n";
PHP
# ----------------------------
# Run generator
# ----------------------------
php "$PHP_TMP" "$SQL_FILE" "$MIG_DIR" "$MODEL_DIR"

# cleanup
rm -f "$PHP_TMP"
