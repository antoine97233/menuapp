<?php

namespace App\config;

require_once '../vendor/autoload.php';

use Dotenv\Dotenv as Dotenv;





abstract class DbConnect
{

    private $hostname;
    private $dbname;
    private $username;
    private $password;

    protected $request;
    protected $connection;

    protected $table;

    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
        $dotenv->load();
        $this->hostname = $_ENV["DB_HOST"];
        $this->dbname = $_ENV["DB_NAME"];
        $this->username = $_ENV["DB_USER"];
        $this->password = $_ENV["DB_PASS"];
    }

    /**
     * Connexion à la BDD
     *
     * @return PDO  instance de PDO représentant la connexion à la base de données.
     */
    protected function getConnection(): \PDO
    {
        try {
            $this->connection = new \PDO("mysql:host=$this->hostname;dbname=$this->dbname;charset=UTF8", $this->username, $this->password);
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $this->connection;
        } catch (\PDOException $e) {
            $msg = 'Error: ' . $e->getMessage();
            die($msg);
        }
    }
}
