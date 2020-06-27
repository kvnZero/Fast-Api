<?php
namespace Fastapi\Db\PDO;

use PDO;

class PDOConnection
{
    private static $db;

    private $table;

    private $sql = [
        'where' => []
    ];

    private $column = ['*'];

    public function __construct() {
        if($this->db == NULL) new PDO(MYSQL_HOST, MYSQL_HOST, MYSQL_PASSWORD);
    }

    public function table($name) {
        $this->table = $name;
        return $this;
    }

    public function query() {
        return $this->db->query($this->sql);
    }

    public function field($column) {
        $this->column = $column;
        return $this;
    }

    public function insert($sql) {
        return $this->db->exec($this->sql);
    }

    public function where($key, $val, $option = '=') {
        $this->sql['where'][] = "`$key` $option \"$val\"";
        return $this;
    }

    private function build($type) {
        if(empty($this->table)) return 'null table';

        switch ($type) {
            case 'select': 
                $column = $this->column;
                if(is_array($column)){
                    $column = join(',', $column);
                }

                $where_sql = ''; 
                if(!empty($this->sql['where'])) {
                    $where_sql = join(' AND ', $this->sql['where']);  
                    $where_sql = "WHERE $where_sql";
                }
                
                $sql = trim("
                SELECT {$column} {$where_sql} FROM {$this->table}
                ");
                break;
            default:
                # code...
                break;
        }
        return $sql;
    }

}
