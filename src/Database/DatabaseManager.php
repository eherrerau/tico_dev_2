<?php

declare(strict_types=1);

namespace Tico\Database;

use PDO;
use PDOException;
use Tico\Config\Config;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class DatabaseManager
{
    private static ?self $instance = null;
    private array $connections = [];
    private Logger $logger;
    private Config $config;

    private function __construct()
    {
        $this->config = Config::getInstance();
        $this->initializeLogger();
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function initializeLogger(): void
    {
        $this->logger = new Logger('database');
        $logPath = $this->config->get('logging.path') . '/database.log';
        $this->logger->pushHandler(new StreamHandler($logPath, Logger::DEBUG));
    }

    public function getConnection(string $name = 'default'): PDO
    {
        if (!isset($this->connections[$name])) {
            $this->connections[$name] = $this->createConnection($name);
        }

        return $this->connections[$name];
    }

    private function createConnection(string $name): PDO
    {
        $config = $this->config->get("database.{$name}");
        
        if (!$config) {
            throw new \InvalidArgumentException("Database configuration '{$name}' not found");
        }

        try {
            $dsn = $this->buildDsn($config);
            $pdo = new PDO(
                $dsn,
                $config['username'],
                $config['password'],
                $config['options']
            );

            $this->logger->info("Database connection established", ['connection' => $name]);
            
            return $pdo;
        } catch (PDOException $e) {
            $this->logger->error("Database connection failed", [
                'connection' => $name,
                'error' => $e->getMessage()
            ]);
            
            throw new \RuntimeException(
                "Could not connect to database '{$name}': " . $e->getMessage(),
                0,
                $e
            );
        }
    }

    private function buildDsn(array $config): string
    {
        switch ($config['driver']) {
            case 'sqlsrv':
                return sprintf(
                    'sqlsrv:Server=%s,%d;Database=%s;ConnectionPooling=0',
                    $config['host'],
                    $config['port'],
                    $config['database']
                );
            
            case 'mysql':
                return sprintf(
                    'mysql:host=%s;port=%d;dbname=%s;charset=%s',
                    $config['host'],
                    $config['port'],
                    $config['database'],
                    $config['charset'] ?? 'utf8mb4'
                );
            
            case 'sqlite':
                return sprintf(
                    'sqlite:%s',
                    $config['database']
                );
            
            default:
                throw new \InvalidArgumentException("Unsupported database driver: {$config['driver']}");
        }
    }

    public function closeConnection(string $name = 'default'): void
    {
        if (isset($this->connections[$name])) {
            $this->connections[$name] = null;
            unset($this->connections[$name]);
            $this->logger->info("Database connection closed", ['connection' => $name]);
        }
    }

    public function closeAllConnections(): void
    {
        foreach (array_keys($this->connections) as $name) {
            $this->closeConnection($name);
        }
    }

    public function beginTransaction(string $connection = 'default'): bool
    {
        return $this->getConnection($connection)->beginTransaction();
    }

    public function commit(string $connection = 'default'): bool
    {
        return $this->getConnection($connection)->commit();
    }

    public function rollback(string $connection = 'default'): bool
    {
        return $this->getConnection($connection)->rollback();
    }

    public function executeQuery(string $sql, array $params = [], string $connection = 'default'): \PDOStatement
    {
        $pdo = $this->getConnection($connection);
        
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            $this->logger->debug("Query executed successfully", [
                'sql' => $sql,
                'params' => $params,
                'connection' => $connection
            ]);
            
            return $stmt;
        } catch (PDOException $e) {
            $this->logger->error("Query execution failed", [
                'sql' => $sql,
                'params' => $params,
                'connection' => $connection,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    public function fetchAll(string $sql, array $params = [], string $connection = 'default'): array
    {
        $stmt = $this->executeQuery($sql, $params, $connection);
        return $stmt->fetchAll();
    }

    public function fetchOne(string $sql, array $params = [], string $connection = 'default'): ?array
    {
        $stmt = $this->executeQuery($sql, $params, $connection);
        $result = $stmt->fetch();
        return $result === false ? null : $result;
    }

    public function fetchColumn(string $sql, array $params = [], string $connection = 'default')
    {
        $stmt = $this->executeQuery($sql, $params, $connection);
        return $stmt->fetchColumn();
    }

    public function insert(string $table, array $data, string $connection = 'default'): string
    {
        $columns = array_keys($data);
        $placeholders = array_map(fn($col) => ":$col", $columns);
        
        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $table,
            implode(', ', $columns),
            implode(', ', $placeholders)
        );
        
        $this->executeQuery($sql, $data, $connection);
        
        return $this->getConnection($connection)->lastInsertId();
    }

    public function update(string $table, array $data, array $where, string $connection = 'default'): int
    {
        $setClause = array_map(fn($col) => "$col = :$col", array_keys($data));
        $whereClause = array_map(fn($col) => "$col = :where_$col", array_keys($where));
        
        $sql = sprintf(
            'UPDATE %s SET %s WHERE %s',
            $table,
            implode(', ', $setClause),
            implode(' AND ', $whereClause)
        );
        
        $params = array_merge($data, array_combine(
            array_map(fn($key) => "where_$key", array_keys($where)),
            array_values($where)
        ));
        
        $stmt = $this->executeQuery($sql, $params, $connection);
        
        return $stmt->rowCount();
    }

    public function delete(string $table, array $where, string $connection = 'default'): int
    {
        $whereClause = array_map(fn($col) => "$col = :$col", array_keys($where));
        
        $sql = sprintf(
            'DELETE FROM %s WHERE %s',
            $table,
            implode(' AND ', $whereClause)
        );
        
        $stmt = $this->executeQuery($sql, $where, $connection);
        
        return $stmt->rowCount();
    }
}
