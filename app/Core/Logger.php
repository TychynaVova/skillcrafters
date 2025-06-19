<?php

namespace App\Core;

class Logger
{
    protected string $logDirectory;
    protected string $logFile;
    protected string $dateFormat = 'Y-m-d H:i:s';

    public function __construct(string $logDirectory = __DIR__ . '/../../logs')
    {
        $this->logDirectory = rtrim($logDirectory, '/');

        if (!is_dir($this->logDirectory)) {
            mkdir($this->logDirectory, 0755, true);
        }

        $this->logFile = $this->logDirectory . '/' . date('Y-m-d') . '.log';
    }

    public function info(string $message, array $context = []): void
    {
        $this->writeLog('INFO', $message, $context);
    }

    public function warning(string $message, array $context = []): void
    {
        $this->writeLog('WARNING', $message, $context);
    }

    public function error(string $message, array $context = []): void
    {
        $this->writeLog('ERROR', $message, $context);
    }

    protected function writeLog(string $level, string $message, array $context = []): void
    {
        $timestamp = date($this->dateFormat);
        $contextString = $this->formatContext($context);

        $logLine = "[$timestamp] [$level] $message $contextString" . PHP_EOL;
        file_put_contents($this->logFile, $logLine, FILE_APPEND | LOCK_EX);
    }

    protected function formatContext(array $context): string
    {
        if (empty($context)) {
            return '';
        }

        return json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
