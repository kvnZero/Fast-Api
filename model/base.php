<?php
class Base
{
    public int   $code   = 1000;
    public array $data   = [];
    public array $sup_method = ['GET', 'POST'];

    private $method = '';

    public function result($param) {
        if($this->method == 'GET'){
            $this->data[] = $param;
        }
    }

    public function input() {
        return $_REQUEST;
    } 

    public function set_method($method) {
        $this->method = $method;
    }

}