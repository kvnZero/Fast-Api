<h1>FAST API - 本地快速对接数据库结构框架</h1>

该框架仅有M(模型)。

为了快速本地连接数据库 对接数据格式进行调试的框架。

功能：
1.  - [x] 支持创建数据表，并支持创建常用类型数据结构
2.  - [x] 增删改查功能
3.  - [ ] 更丰富的查询方法
4.  - [ ] 提供常用的数据库表, 可快速新建所需表
5.  - [ ] 安全性
6.  - [x] 脚手架

<h2>调用方法</h2>

服务启动(默认地址:localhost:8000,只可选端口)：
>>>
    php fa run [8000]
>>>

模型的数据操作都写在Base.php内, 其他类只需继承该类即可, 已经提供一个User表参考

如果需要创建一张表，在Schema目录复制一份users.php修改后通过命令指定文件名新建：
>>>
    php fa create users
>>>

如果访问app/static下的php文件, 访问地址：
>>>
    localhost:8000/base.php
>>>

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

如果更新User表数据, 访问地址和POST数据(默认id为更新查询, 可根据update_key配置，注意同时也是where条件)：
>>>
    localhost:8000/user
    id=1&password=newpassword
>>>

<hr>
<h2>目前支持的的查询结构：</h2>

如果需要某个key或运算则：
>>>
    localhost:8000/user?id=2&or_id=2
>>>

如果需要取出小于2或大于5的数据则：
>>>
    localhost:8000/user?id=<2&or_id=>5
>>>

其他目前已支持的写法 ：
>>>
    localhost:8000/user?id=2&and_id=>5
    localhost:8000/user?id=<>2
>>>

根据某个值进行排序(0,1分别代表ASC,DESC, 也可以直接输入ASC或DESC)
>>>
    localhost:8000/user?id=2&or_id=2&order_id=0
    localhost:8000/user?id=2&or_id=2&order_user_id=1
    localhost:8000/user?id=2&or_id=2&order_id=ASC
    localhost:8000/user?id=2&or_id=2&order_user_id=DESC
>>>