<?php

namespace Yuyue8\TpException\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;
use think\helper\Str;

class MakeException extends Command
{
    public function configure()
    {
        $this->setName('make:exception')
        ->addArgument('name', Argument::REQUIRED, "The name of the class")
        ->setDescription('Create a new Exception class');
    }

    protected function getStub(): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'exception.stub';
    }

    protected function execute(Input $input, Output $output)
    {
        $classname = trim($input->getArgument('name'));

        $classname = ltrim(str_replace('\\', '/', $classname), '/');

        [$namespace, $class] = $this->getNamespaceName($classname);

        $class = Str::studly($class . '_Exception');

        $pathname = $this->getPathName($namespace, $class);

        if (is_file($pathname)) {
            $output->writeln('<error>' . 'Exception:' . $classname . ' already exists!</error>');
            return false;
        }

        if (!is_dir(dirname($pathname))) {
            mkdir(dirname($pathname), 0755, true);
        }

        file_put_contents($pathname, $this->buildClass($namespace, $class));

        $output->writeln('<info>' . 'Exception:' . $class . ' created successfully.</info>');
    }

    protected function buildClass(string $namespace, string $class)
    {
        $stub = file_get_contents($this->getStub());

        return str_replace(['{%className%}', '{%namespace%}'], [
            $class,
            str_replace('/', '\\', $namespace)
        ], $stub);
    }

    protected function getPathName(string $namespace, string $class): string
    {
        return $this->app->getRootPath() . str_replace('/', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR . $class . '.php';
    }

    /**
     * 获取文件名和域名空间
     *
     * @param string $classname
     * @return array [namespace, class]
     */
    public function getNamespaceName(string $classname)
    {
        $namespace = trim(implode('/', array_slice(explode('/', $classname), 0, -1)), '/');

        return [
            $namespace,
            str_replace($namespace . '/', '', $classname)
        ];
    }
}
