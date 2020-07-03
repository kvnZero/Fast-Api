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

    private function field_base($type, $name, $len = 0, $defalut = ''){
        $field = [];
        $field['type'] = $type;
        $field['name'] = $name;
        $field['len']  = $len>0 ? $len : '';
        $field['defalut'] = $defalut;
        $this->field[] = $field;
        return $this;
    }

    public function field_string($name, $len = 10, $defalut='') {
        return $this->field_base('VARCHAR', $name, $len, $defalut);
    }

    public function field_int($name, $defalut='') {
        return $this->field_base('INT', $name, 0, $defalut);
    }

    public function field_big_int($name, $defalut='') {
        return $this->field_base('BIGINT', $name, 0, $defalut);
    }

    public function field_date($name, $defalut='') {
        return $this->field_base('DATE', $name, 0, $defalut);
    }

    public function field_datetime($name, $defalut='') {
        return $this->field_base('DATETIME', $name, 0, $defalut);
    }

    public function field_longtext($name, $defalut='') {
        return $this->field_base('LONGTEXT', $name, 0, $defalut);
    }

    public function field_text($name, $defalut='') {
        return $this->field_base('TEXT', $name, 0, $defalut);
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

            $sql .= "`{$val['name']}` {$type},";
        }
        return trim("
        CREATE TABLE IF NOT EXISTS `{$this->table}`(
            `id` INT UNSIGNED AUTO_INCREMENT,
            {$sql}
            PRIMARY KEY ( `id` )
         )ENGINE=InnoDB DEFAULT CHARSET=utf8;
         ");
    }
}