<?php

namespace App\Libraries;

class Notifier
{
    public function logProblem(string $message): void
    {
        log_message('warning', sprintf('[%s] %s', date('Y-m-d H:i:s'), $message));
    }
}