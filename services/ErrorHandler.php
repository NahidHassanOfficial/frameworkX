<?php

class ErrorHandler
{
    protected string $logDir;
    protected string $logFile;
    protected bool $isProduction;

    public function __construct(bool $isProduction = true, string $logDir = "errors")
    {
        // Determine if the environment is production
        $this->isProduction = $isProduction;

        // Set up log directory and file
        $this->logDir = __DIR__ . '/../' . $logDir;
        $this->logFile = $this->logDir . DIRECTORY_SEPARATOR . 'error.log';

        $this->setupLog();
        $this->setupErrorHandling();
    }

    protected function setupLog(): void
    {
        $this->makeLogDir();
        $this->makeLogFile();
        $this->cleanupLogOccasionally();

        ini_set('log_errors', 1);
        ini_set('error_log', $this->logFile);
        error_reporting(E_ALL);
        ini_set('display_errors', $this->isProduction ? 0 : 1);
        ini_set('display_startup_errors', $this->isProduction ? 0 : 1);
    }

    protected function setupErrorHandling(): void
    {
        // Convert PHP errors to exceptions
        set_error_handler(function ($severity, $message, $file, $line) {
            throw new ErrorException($message, 0, $severity, $file, $line);
        });

        // Handle uncaught exceptions
        set_exception_handler(function ($e) {
            // Always log the exception
            error_log('Uncaught Exception: ' . $e);

            // Send JSON response
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => $this->isProduction
                    ? 'An error occurred, please contact system administrator'
                    : $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine()
            ]);
            exit;
        });
    }

    protected function makeLogDir(): void
    {
        if (!is_dir($this->logDir)) {
            mkdir($this->logDir, 0777, true);
        }
    }

    protected function makeLogFile(): void
    {
        if (!file_exists($this->logFile)) {
            file_put_contents($this->logFile, "");
        }
    }

    protected function cleanupLogOccasionally(): void
    {
        // ~1% chance per request
        if (mt_rand(1, 100) === 1 && file_exists($this->logFile)) {
            if (filesize($this->logFile) > 1024 * 1024) { // 1MB
                $backupFile = $this->logDir . DIRECTORY_SEPARATOR . 'error_' . date('Ymd-His') . '.log.bak';
                rename($this->logFile, $backupFile);
                file_put_contents($this->logFile, '');
                error_log("Error log cleaned up and archived to $backupFile");
            }
        }
    }
}
