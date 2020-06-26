<?php
namespace Fastapi\Db\Schema;

class Builder
{
    private $field   = [];
    private $table = '';

    public function __construct($table)
    {
        $this->table = $table;
    }

    public function create()
    {
        return $this->build_create_sql();
    }

    public function string($name, $len = 10, $defalut='') {
        $field = [];
        $field['type'] = 'VARCHAR';
        $field['name'] = $name;
        $field['len']  = $len;
        $this->field[] = $field;
        return $this;
    }

    private function build_create_sql() {
        $sql = '';
        foreach ($this->field as $val) {
            switch ($val['type']) {
                case 'VARCHAR':
                    $type = "{$val['type']}({$val['len']})";
                    break;
                default:
                    $type = $val['type'];
                    break;
            }

            $sql = "`{$val['name']}` {$type},";
        }
        return trim("
        CREATE TABLE IF NOT EXISTS `{$this->table}`(
            {$sql}
            PRIMARY KEY ( `id` )
         )ENGINE=InnoDB DEFAULT CHARSET=utf8;
         ");
    }
}