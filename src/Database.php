<?php

namespace Xuma\Fixer;

use \PDO;

class Database
{
    private $db;
    public $database;
    public function __construct($database, $user, $pass)
    {
        $this->database = $database;

        try {
            $this->db = new PDO('mysql:host=localhost;dbname='.$database, $user, $pass, [
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET collation_connection = 'utf8mb4_general_ci'"
            ]);
        } catch (\PDOException $e) {
            die("Veritabani baglantisi saglanamadi:". $e->getMessage());
        }
    }

    public function execute($statement, $data = [])
    {
        $query = $this->db->prepare($statement);
        $query->execute($data);

        if (isset($query->errorInfo()[2])) {
            echo "QUERY: ".$statement.PHP_EOL;
            echo "ERROR: ".$query->errorInfo()[2].PHP_EOL;
            echo "------------------------\n";
        }
        return $query;
    }

    public function query($statement, $data = [])
    {
        $query = $this->execute($statement, $data);
        if (!$query) {
            die("Sorgu hatasi, Query: ". $statement);
        }
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}