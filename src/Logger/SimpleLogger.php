<?php

namespace Logger;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class SimpleLogger implements LoggerInterface
{
    private string $logDir;
    private string $minLevel;

    private const LEVELS = [
        LogLevel::EMERGENCY => 0,
        LogLevel::ALERT     => 1,
        LogLevel::CRITICAL  => 2,
        LogLevel::ERROR     => 3,
        LogLevel::WARNING   => 4,
        LogLevel::NOTICE    => 5,
        LogLevel::INFO      => 6,
        LogLevel::DEBUG     => 7,
    ];

    public function __construct(string $logDir, string $minLevel = LogLevel::DEBUG)
    {
        $this->logDir = rtrim($logDir, DIRECTORY_SEPARATOR);
        $this->minLevel = $minLevel;

        if (!is_dir($this->logDir)) {
            mkdir($this->logDir, 0755, true);
        }
    }

    public function log($level, $message, array $context = []): void
    {
        if (!isset(self::LEVELS[$level])) {
            throw new \Psr\Log\InvalidArgumentException("Неизвестный уровень логирования: $level");
        }

        if (self::LEVELS[$level] > self::LEVELS[$this->minLevel]) {
            return; // уровень ниже минимального
        }

        $message = $this->interpolate($message, $context);

        // Если после интерполяции сообщение не содержит данных контекста — добавим JSON с контекстом
        if (empty($context) === false && strpos($message, '{') === false) {
            $message .= ' ' . json_encode($context, JSON_UNESCAPED_UNICODE);
        }

        $date = date('Y-m-d H:i:s');
        $logLine = "[$date] " . strtoupper($level) . ": $message" . PHP_EOL;

        $file = $this->logDir . DIRECTORY_SEPARATOR . date('Y-m-d') . '.log';

        file_put_contents($file, $logLine, FILE_APPEND | LOCK_EX);
    }

    private function interpolate(string $message, array $context): string
    {
        $replace = [];
        foreach ($context as $key => $val) {
            if (is_array($val) || is_object($val)) {
                $val = json_encode($val, JSON_UNESCAPED_UNICODE);
            } elseif ($val === null) {
                $val = 'null';
            } else {
                $val = (string)$val;
            }
            $replace['{' . $key . '}'] = $val;
        }
        return strtr($message, $replace);
    }

    // Методы для каждого уровня
    public function emergency($message, array $context = []): void { $this->log(LogLevel::EMERGENCY, $message, $context); }
    public function alert($message, array $context = []): void     { $this->log(LogLevel::ALERT, $message, $context); }
    public function critical($message, array $context = []): void  { $this->log(LogLevel::CRITICAL, $message, $context); }
    public function error($message, array $context = []): void     { $this->log(LogLevel::ERROR, $message, $context); }
    public function warning($message, array $context = []): void   { $this->log(LogLevel::WARNING, $message, $context); }
    public function notice($message, array $context = []): void    { $this->log(LogLevel::NOTICE, $message, $context); }
    public function info($message, array $context = []): void      { $this->log(LogLevel::INFO, $message, $context); }
    public function debug($message, array $context = []): void     { $this->log(LogLevel::DEBUG, $message, $context); }
}
