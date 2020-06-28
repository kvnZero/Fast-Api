<?php

use app\model\Base;
use Fastapi\Db\Connection\PDOConnection;
use Fastapi\Db\Schema\Builder;

class User extends Base
{
    public static function create_table(){
        $builder = new Builder('user');
        $sql = $builder->field_string('user')->field_string('password')->create();
        var_dump($sql);
        $pdo = new PDOConnection();
        $pdo->getObject()->exec($sql);
    }
}