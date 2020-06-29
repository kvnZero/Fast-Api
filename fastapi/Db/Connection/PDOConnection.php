<?php
namespace Fastapi\Db\Connection;

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
        $dsn = 'mysql:dbname='.MYSQL_db.';host='.MYSQL_HOST;
        if(self::$db == NULL) {
            try {
                self::$db = new PDO($dsn, MYSQL_USER, MYSQL_PASSWORD);
            } catch (\Throwable $th) {
                die ('数据库连接失败');
            }
        }
    }

    public function table($name) {
        $this->table = $name;
        return $this;
    }

    public function query() {
        try {
            return self::$db->query($this->build('select'))->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $th) {
            die ('查询语句执行失败');
        }
    }

    public function field($column) {
        $this->column = $column;
        return $this;
    }

    public function insert($sql) {
        return self::$db->exec($this->sql);
    }

    public function where($key, $val, $option = '=') {
        $this->sql['where'][] = "`$key` $option \"$val\"";
        return $this;
    }

    public function getObject(){
        return self::$db;
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
                    $where_sql = "WHERE $where_sql ";
                }

                $sql = trim("
                SELECT {$column} FROM {$this->table} {$where_sql}
                ");
                break;
            default:
                # code...
                break;
        }
        return $sql;
    }

}
