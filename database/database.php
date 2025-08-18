<?php

class MySQLDatabase
{
    private static $instance = null;
    private $connection;
    private $last_query;

    // Singleton constructor
    private function __construct()
    {
        $this->connection = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        if ($this->connection->connect_error) {
            die("Database connection failed: " . $this->connection->connect_error);
        }
        $this->connection->set_charset("utf8");
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new MySQLDatabase();
        }
        return self::$instance;
    }

    private function __clone() { }
    private function __wakeup() { }

    // -------------------- QUERY METHODS --------------------

    // Raw query
    public function query($sql)
    {
        $this->last_query = $sql;
        $result = $this->connection->query($sql);
        $this->confirm_query($result);
        return $result;
    }

    // Prepared statement for SELECT that returns one object
    public function result_one($sql, $params = [])
    {
        $stmt = $this->prepare_and_bind($sql, $params);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_object();
        $stmt->close();
        return $row;
    }

    // Prepared statement for SELECT that returns all objects
    public function result_all($sql, $params = [])
    {
        $stmt = $this->prepare_and_bind($sql, $params);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = [];
        while ($row = $result->fetch_object()) {
            $rows[] = $row;
        }
        $stmt->close();
        return $rows;
    }

    // Insert/Update/Delete, returns affected rows or inserted ID for insert
    public function execute_query($sql, $params = [], $return_insert_id = false)
    {
        $stmt = $this->prepare_and_bind($sql, $params);
        $stmt->execute();

        $result = $return_insert_id ? $stmt->insert_id : $stmt->affected_rows;
        $stmt->close();
        return $result;
    }

    // Escape a value safely
    public function escape($value)
    {
        return $this->connection->real_escape_string($value);
    }

    // Total rows in a table
    public function total_rows($table)
    {
        $row = $this->query("SELECT COUNT(*) AS total FROM `$table`")->fetch_assoc();
        return (int)$row['total'];
    }

    // Get last executed query
    public function last_query()
    {
        return $this->last_query;
    }

    // Close connection
    public function close()
    {
        if ($this->connection) {
            $this->connection->close();
        }
    }

    // -------------------- INTERNAL METHODS --------------------

    // Prepare and bind parameters
    private function prepare_and_bind($sql, $params)
    {
        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $this->connection->error);
        }

        if (!empty($params)) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }

        return $stmt;
    }

    private function confirm_query($result)
    {
        if (!$result) {
            die("Database query failed: " . $this->connection->error);
        }
    }
}

// ------------------ GLOBAL INSTANCE ------------------
$DB = MySQLDatabase::getInstance();
$GLOBALS['DB'] = $DB;
