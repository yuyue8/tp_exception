# TpException

## 安装
~~~
composer require yuyue8/tp_exception
~~~

## 使用方法

安装后默认使用 `Yuyue8\TpException\basic\BaseException` 类为异常处理类

若需要自定义，可以创建自定义异常处理类，继承`Yuyue8\TpException\basic\BaseException`，
同时在`tp_config`配置文件中设置`base_exception_class`参数，值为自定义类

创建异常类：
```
php think make:exception /app/exception/Admin
```

使用异常类：
```
throw new AdminException('错误信息');
```