<?php

namespace App\Logging;

use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\HandlerInterface;

class CustomizeFormatter
{
    public function __invoke($logger): void
    {
        foreach ($logger->getHandlers() as $handler) {
            if ($handler instanceof HandlerInterface) {
                $handler->setFormatter(new JsonFormatter());
            }
        }
    }
}
