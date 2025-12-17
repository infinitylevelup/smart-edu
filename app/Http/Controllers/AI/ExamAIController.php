<?php

namespace App\Http\Controllers\AI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ExamAIController extends Controller
{
    public function suggest(Request $request)
    {
            $apiKey = env('OPENAI_API_KEY');

            $prompt = "
        تو یک دستیار آموزشی هوشمند هستی. فقط و فقط یک JSON معتبر بده.
        ساختار دقیق:

        {
        \"title\": \"...\",
        \"description\": \"...\"
        }

        به هیچ‌وجه متن قبل یا بعد JSON اضافه نکن.
            ";

            $response = Http::withToken($apiKey)->post(
                'https://api.openai.com/v1/chat/completions',
                [
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'temperature' => 0.6
                ]
            );

            $raw = $response->json()['choices'][0]['message']['content'] ?? '';

            // --- استخراج JSON با Regex ---
            preg_match('/\{(?:[^{}]|(?R))*\}/u', $raw, $match);

            if (!$match) {
                return response()->json([
                    'title' => '',
                    'description' => 'AI JSON Parse Error'
                ]);
            }

            $json = $match[0];
            $data = json_decode($json, true);

            if (!$data) {
                return response()->json([
                    'title' => '',
                    'description' => 'Invalid JSON Received'
                ]);
            }

            return response()->json([
                'title' => $data['title'] ?? '',
                'description' => $data['description'] ?? ''
            ]);
    }

}
