## 基于ThinkPHP5.1和AdminLte封装的CMS基础框架

### step 1 
    拷贝 .env.example 为 .env 文件，并且更改.env文件里面的数据配置信息
    
### step 2

    composer install 加载依赖库
    
### step 3

    创建基础数据表 php think migrate:run
    
### step 4
    恭喜完成了，访问你自己的域名即可 如： http://localhost  
    
    
### 其他
    
   创建一个新curd页面 可以使用命令 php think admin:make 控制器名 数据表名
   
   如：
   php think admin:make Index cy_wait_caiji
   
   会在 application/admin/controller 目录下生成 Index.php 文件
   同时也会在 application/common/model 目录下生成 CyWaitCaiji.php 文件
   
### 案例可参考 Index.php          