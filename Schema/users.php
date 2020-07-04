<?php

use Fastapi\Db\Connection\PDOConnection;
use Fastapi\Db\Schema\Builder;

function create_table(){
    //统一写在这个方法里面
    $builder = new Builder('myusers');
    $sql = $builder->field_string('user')
                    ->field_string('password')
                    ->create();
    $pdo = new PDOConnection();
    return $pdo->getObject()->exec($sql);
}

