<?php
namespace app\model;

use Fastapi\Db\Connection\PDOConnection;

abstract class Base
{
    public int   $code   = 1000;
    public array $data   = [];
    public array $sup_method = ['GET', 'POST'];

    private $method = '';

    public function result($param) {
        if($this->method == 'GET'){
            $pdo = new PDOConnection();
            $pdo = $pdo->table(strtolower(get_class($this)));
            foreach ($param as $key => $value) {
                $pdo = $pdo->where($key,$value);
            }
            $this->data[] = $pdo->query();
        }
    }

    public function input() {
        return $_REQUEST;
    } 

    public function set_method($method) {
        $this->method = $method;
    }

}