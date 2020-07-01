<?php
namespace app\model;

use Fastapi\Db\Connection\PDOConnection;

abstract class Base
{
    public int   $code   = 1000;
    public array $data   = [];
    public array $sup_method = ['GET', 'POST'];
    public array $update_key = ['id'];

    private $method = '';

    public function result($param) {
        $pdo = new PDOConnection();
        $pdo = $pdo->table(strtolower(get_class($this)));
        if($this->method == 'GET'){
            foreach ($param as $key => $value) {
                $pdo = $pdo->where($key,$value);
            }
            $this->data[] = $pdo->query();
        }else if($this->method =='POST') {
            if($this->update_key != array_intersect($this->update_key, array_keys($param))){
                $this->data[] = $pdo->insert($param);
            }else{
                $this->data[] = $pdo->update($param, $this->update_key);
            }
        }
    }

    public function input() {
        return $_REQUEST;
    } 

    public function set_method($method) {
        $this->method = $method;
    }

}