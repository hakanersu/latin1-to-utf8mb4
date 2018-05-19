<?php

namespace Xuma\Fixer;

class Query
{
    private $db;

    private $parts = [];

    private $tables = [];

    public $types = [];

    public $ignoredFields = [];

    public $ignoredTables = [];

    public $selectedTables = false;

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
        $tables = $this->tables;
        if ($this->selectedTables) {
            $tables = $this->selectedTables;
        }
        foreach ($tables as $table) {
            $this->modifySingleTable($table);
        }

        return $this;
    }

    public function setParts($arr)
    {
        $this->parts = $arr;
        return  $this;
    }

    public function updateColumns()
    {
        $tables = $this->tables;
        if ($this->selectedTables) {
            $tables = $this->selectedTables;
        }
        foreach ($tables as $table) {
            if (in_array($table, $this->ignoredTables)) {
                continue;
            }
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

    public function setTypes($types)
    {
        $this->types = $types;
        return $this;
    }

    public function setIgnoredFields($fields)
    {
        $this->ignoredFields = $fields;
        return $this;
    }

    public function setIgnoredTables($tables)
    {
        $this->ignoredTables = $tables;
        return $this;
    }
    private function updateField($table, $columnName)
    {
        echo "Updating {$table} > $columnName ".PHP_EOL;
        foreach ($this->parts as $wrong => $correct) {
            if (in_array($columnName, $this->ignoredFields)) {
                continue;
            }
            // Prepared statement tablolarda ise yaramadigindan direkt yazdim ve diger alanlarda prepared statementlar problem cikardi ve
            // ugrasmak istemedim ne de olsa public olarak kullanmayacagiz.
            $this->db->execute("UPDATE {$table} SET `{$columnName}` = REPLACE (`{$columnName}`, '{$wrong}', '{$correct}')");
        }
    }

    public function tables($tables)
    {
        $this->selectedTables = $tables;
        return $this;
    }
}
