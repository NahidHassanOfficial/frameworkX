<?php

/**
 * Rate Limiter using SQLite
 *
 * This function limits the number of requests to a specific endpoint
 * based on the IP address or a custom identifier.
 * It uses the same key for the same IP and endpoint for the every request.
 *
 * @param int $limit The maximum number of requests allowed.
 * @param int $seconds The time window in seconds for the rate limit.
 * @param string|null $identifier Optional custom identifier for rate limiting.
 */
class ThrottleMiddleware
{
    private static ?PDO $pdo = null;
    private static string $dbPath = __DIR__ . '/rate_limits/rate_limiter.sqlite';

    /**
     * Initialize the SQLite database (static)
     */
    private static function initDatabase(): void
    {
        if (self::$pdo !== null) return;

        $dir = dirname(self::$dbPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        self::$pdo = new PDO('sqlite:' . self::$dbPath, null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT => true
        ]);

        self::$pdo->exec("
            CREATE TABLE IF NOT EXISTS requests (
                key TEXT PRIMARY KEY,
                ip TEXT,
                endpoint TEXT,
                count INTEGER,
                expires_at INTEGER
            )
        ");
    }

    /**
     * Static rate limiter middleware
     *
     * @param int $limit Max requests
     * @param int $seconds Time window in seconds
     * @param string|null $identifier Optional identifier
     */
    public static function handle(Request $request, int $limit = 5, int $seconds = 60, ?string $identifier = null): void
    {
        self::initDatabase();

        $ip       = $_SERVER['REMOTE_ADDR'] ?? 'unknown_ip';
        $endpoint = $request->uri ?? 'unknown_endpoint';
        $id       = $identifier ?? $ip;
        $now      = time();

        $key = md5($id . '|' . $endpoint);

        $stmt = self::$pdo->prepare("SELECT count, expires_at FROM requests WHERE key = :key");
        $stmt->execute([':key' => $key]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            if ($row['expires_at'] <= $now) {
                self::$pdo->prepare("
                    UPDATE requests
                    SET count = 1, expires_at = :exp, ip = :ip, endpoint = :endpoint
                    WHERE key = :key
                ")->execute([
                    ':exp' => $now + $seconds,
                    ':ip' => $ip,
                    ':endpoint' => $endpoint,
                    ':key' => $key
                ]);
            } else {
                if ($row['count'] >= $limit) {
                    self::tooManyRequests();
                }
                self::$pdo->prepare("UPDATE requests SET count = count + 1 WHERE key = :key")
                    ->execute([':key' => $key]);
            }
        } else {
            self::$pdo->prepare("
                INSERT INTO requests (key, ip, endpoint, count, expires_at)
                VALUES (:key, :ip, :endpoint, 1, :exp)
            ")->execute([
                ':key' => $key,
                ':ip' => $ip,
                ':endpoint' => $endpoint,
                ':exp' => $now + $seconds
            ]);
        }

        // Occasional cleanup
        if (mt_rand(1, 100) === 1) {
            self::$pdo->exec("DELETE FROM requests WHERE expires_at <= $now");
        }
    }

    /**
     * Respond with 429 Too Many Requests
     */
    private static function tooManyRequests(): void
    {
        header('Content-Type: application/json');
        http_response_code(429);
        echo json_encode([
            'status' => 'failed',
            'message' => 'Too many requests. Please try again later.'
        ], JSON_PRETTY_PRINT);
        exit;
    }
}
