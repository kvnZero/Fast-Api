<?php
namespace fastapi;

defined('CORE_PATH') or define('CORE_PATH', __DIR__);

class FastApi
{
    protected $config = [];
    protected $model  = '';
    protected $file   = '';
    protected $result = '';

    public function __construct($config)
    {
        $this->config = $config;   
    }

    public function run()
    {
        $this->route();
        $this->handle();
        $this->print();
    }

    private function route()
    {
        $model = ltrim($_SERVER['PATH_INFO'], '/');
        $arr = explode('/',$model);

        $file = APP_PATH . "model/{$arr[0]}.php";
        
        if(!file_exists($file)) $this->output(-1, 'model not found');
        
        $this->file  = $file;
        $this->model = $arr[0];
    }

    private function handle() {
           
        if(empty($this->file)) return;

        require($this->file);

        $instance = new $this->model;
        if(!in_array($_SERVER['REQUEST_METHOD'], $instance->sup_method)) {
            $this->output(500, '该类不支持该请求方法');
            return;
        }
        $instance->set_method($_SERVER['REQUEST_METHOD']);
        $instance->result($instance->input());
        $this->output($instance->code, $instance->data);
    }

    private function print() {
        echo $this->result;
    }

    private function output($code = 1000, $data = [])
    {
        $this->result = json_encode(['code' => $code, 'data'=>$data]);
    }
}