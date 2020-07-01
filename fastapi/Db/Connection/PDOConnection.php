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

    public function insert($value) {
        $sql = $this->build('insert', $value);
        return self::$db->exec($sql)==1 ? $value : false;
    }

    public function update($value, $where = []) {
        $value['_where'] = $where;
        $sql = $this->build('update', $value);
        return self::$db->exec($sql)==1 ? $value : false;
    }

    public function where($key, $val, $option = '=') {
        $this->sql['where'][] = "`$key` $option \"$val\"";
        return $this;
    }

    public function getObject(){
        return self::$db;
    }

    private function build($type, $datas = []) {
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
            case 'insert':  
                
                if(!isset($datas[0])){
                    //如果不是嵌套数据 代表是单行插入
                    $datas = [$datas];
                }
                $sql = '';
                foreach ($datas as $data) {
                    $k = [];
                    $v = [];
                    array_walk($data, function($value, $key) use(&$k, &$v){
                        $k[] = "`$key`";
                        $v[] = "'$value'";
                    });
                    $sql .= "INSERT INTO {$this->table} (".join(',',$k).") VALUES (".join(',',$v).");";
                }
    
                break;
                
            case 'update':

                $v = [];
                $w = [];
                $update_key = $datas['_where'];
                unset($datas['_where']);

                array_walk($datas, function($value, $key) use(&$v, &$w, $update_key){
                    if(in_array($key,$update_key)){
                        $w[] = "$key = $value";
                   
                    }else{
                        $v[] = "`$key` = '$value'";
                    }
                });
                $sql = "UPDATE {$this->table} SET ".join(' AND ', $v)." WHERE ".join(' AND ', $w);
                break;

            default:
                # code...
                break;
        }
        return $sql;
    }

}
