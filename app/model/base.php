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
                preg_match("/^(<>)|^(<=)|^(>=)|^[=<>]/",$value, $option);
                if(empty($option[0])) {
                    $option = ['='];
                }else{
                    $value = substr($value,strlen($option[0]));
                }  
                
                preg_match("/^(or_)|^(order_)|^(and_)?/",$key, $type);
                if(empty($type[0])) $key = 'and_'.$key ; //默认
                switch ($type[0]) {
                    case 'order_':
                        $key = substr($key, 6);
                        $value = (($value == '0' || $value == '1') ? ['ASC', 'DESC'][$value] : $value) ?? 'ASC';
                        $pdo = $pdo->order($key, $value);
                        break; 
                    case 'or_':
                        $key = substr($key, 3); 
                        $pdo = $pdo->where($key,$value, $option[0], 'OR');
                        break;
                    default:
                    case 'and_':
                        $key = substr($key, 4); 
                        $pdo = $pdo->where($key,$value, $option[0], 'AND');
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