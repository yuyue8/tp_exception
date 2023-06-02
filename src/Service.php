<?php

namespace Yuyue8\TpException;

use think\facade\Config;

class Service extends \think\Service
{

    /**
     * 服务启动
     *
     * @return void
     */
    public function boot()
    {
        $config = include(__DIR__ . '/config/tp_config.php');

        $tp_config = Config::get('tp_config', []);

        $base_exception = $tp_config['base_exception_class'] ?? $config['base_exception_class'];

        $this->app->bind('think\exception\Handle', $base_exception);

        $this->commands(
            \Yuyue8\TpException\command\MakeException::class
        );
    }

}
