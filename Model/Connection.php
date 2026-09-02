<?php
namespace Model;

use PDO;
use PDOException;

class Connection {

    private static ?PDO $instance = null;

    public static function getInstance(): PDO {
        if (self::$instance === null) {
            
            $configPath = __DIR__ . '/../config/configuration.php';

            if (!file_exists($configPath)) {
                throw new PDOException("Arquivo de configuração não encontrado em: {$configPath}");
            }

            $config = require $configPath;
            $dbConfig = $config['db'];

            $dsn = sprintf(
                "mysql:host=%s;dbname=%s;charset=%s",
                $dbConfig['host'],
                $dbConfig['dbname'],
                $dbConfig['charset']
            );

            try {
                self::$instance = new PDO($dsn, $dbConfig['user'], $dbConfig['password'], [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            } catch (PDOException $e) {
                throw new PDOException("Erro ao conectar ao banco de dados: " . $e->getMessage());
            }
        }

        return self::$instance;
    }
}