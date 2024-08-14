<?php


namespace App\Logging;

use Monolog\Formatter\LineFormatter;
use Monolog\LogRecord;

class CustomFormatter extends LineFormatter
{
    private $isFirstLog = true;

    public function format(LogRecord $record): string
    {
        $output = '';

        if ($this->isFirstLog) {
            $output .= str_repeat(PHP_EOL, 3);
            $output .= "=== New Log: " . date('Y-m-d H:i:s') . " ===" . PHP_EOL;
            $this->isFirstLog = false;
        }

        // Format the log record
        $output .= parent::format($record);

        return $output;
    }
}
