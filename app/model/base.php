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
            $whereKey = [];
            foreach ($param as $key => $value) {
                preg_match("/^(<>|<=|>=|<|>)(.+)/",$value, $option);
                if(count($option)==3){
                    $value = $option[2];
                    $option = $option[1];
                }else{
                    $option = '=';
                } 

                preg_match("/^(or|order|and)_(.+)/",$key, $type);
                if(count($type)==3){
                    $key = $type[2];
                }else{
                    $type[1] = 'and'; //默认
                } 
                switch ($type[1]) {
                    case 'order':
                        $value = (($value == '0' || $value == '1') ? ['ASC', 'DESC'][$value] : $value) ?? 'ASC';
                        $pdo = $pdo->order($key, $value);
                        break; 
                    case 'or':
                        $pdo = $pdo->where($key,$value, $option, 'OR');
                        break;
                    default:
                    case 'and':
                        $pdo = $pdo->where($key,$value, $option, 'AND');
                        break;
                }
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