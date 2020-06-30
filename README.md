<h1>FAST API - 本地快速对接数据库结构框架</h1>

该框架仅有M(模型)。

为了快速本地连接数据库 对接数据格式进行调试的框架。

功能：
1.  支持创建数据表，并支持创建常用类型数据结构
2.  增删改查功能
3.  更丰富的查询方法
4.  提供常用的数据库表, 可快速新建所需表
5.  安全性
6.  脚手架

<h2>调用方法</h2>

服务启动：
>>>
    php -S localhost:8000 route.php
>>>

模型的数据操作都写在Base.php内, 其他类只需继承该类即可, 已经提供一个User表参考

如果需要创建一张表，编写函数并在任意地方调用即可(未来会写成脚手架生成)：
```PHP
    public static function create_table(){
        $builder = new Builder('user'); //表名
        $sql =  $builder->field_string('user') //user字段(VARCHAR类型)
                        ->field_string('password',20) //长度为20的字段
                        ->create();
        $pdo = new PDOConnection();
        $pdo->getObject()->exec($sql); //执行生成的语句
    }
```

如果查询User数据, 访问地址：
>>>
    localhost:8000/user
>>>

如果查询User表内Id为1都数据, 访问地址：
>>>
    localhost:8000/user?id=1
>>>

如果插入数据到User表, 访问地址和POST数据(可被input接收默认是$_REQUEST,可重写)：
>>>
    localhost:8000/user
    user=admin&password=password
>>>

如果更新User表数据, 访问地址和POST数据(目前必须带id, 后续可配置)：
>>>
    localhost:8000/user
    id=1&password=newpassword
>>>
