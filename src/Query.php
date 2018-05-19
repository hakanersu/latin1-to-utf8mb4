<?php

namespace Xuma\Fixer;

class Query
{
    private $db;

    private $parts = [];

    private $tables = [];

    public $types = [];

    public $ignoredFiels = [];

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function setDatabaseCharset()
    {
        $this->db->execute('ALTER DATABASE :dbname CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci', [
            'dbname' => $this->db->database,
        ]);

        $this->getTables();

        return $this;
    }

    public function setTableCharsets()
    {
        foreach ($this->tables as $table) {
            $this->modifySingleTable($table);
        }

        return $this;
    }

    public function setParts($arr)
    {
        $this->parts = $arr;
    }

    public function updateColumns()
    {
        foreach ($this->tables as $table) {
            $columns = $this->getColumns($table);
            foreach ($columns as $column) {
                if (in_array($column['DATA_TYPE'], $this->types)) {
                    $this->updateField($table, $column['COLUMN_NAME']);
                }
            }
        }
    }

    private function modifySingleTable($table)
    {
        $this->db->execute("ALTER TABLE {$table} DEFAULT CHARSET=utf8mb4");

        $this->db->execute("ALTER TABLE {$table} CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
    }

    private function getTables()
    {
        $tables = $this->db->query('SELECT table_name FROM information_schema.tables where table_schema = :dbname', [
            'dbname' => $this->db->database,
        ]);

        $this->tables = array_column($tables, 'table_name');
    }

    private function getColumns($table)
    {
        $columns = $this->db->query("SELECT COLUMN_NAME,DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '{$this->db->database}' AND TABLE_NAME = '{$table}'");

        return $columns;
    }

    private function updateField($table, $columnName)
    {
        echo "Updating {$table} > $columnName ".PHP_EOL;
        foreach ($this->parts as $wrong => $correct) {
            if (in_array($columnName, $this->ignoredFiels)) {
                continue;
            }
            $this->db->execute("UPDATE {$table} SET `{$columnName}` = REPLACE (`{$columnName}`, '{$wrong}', '{$correct}')");
        }
    }
}
