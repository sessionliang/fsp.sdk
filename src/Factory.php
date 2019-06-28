<?php

namespace FusionPay;

/**
 * Class Factory.
 *
 * @method static \FusionPay\Payment\Application            payment(array $config)
 */
class Factory
{
    /**
     * @param string $name
     * @param array  $config
     *
     * @return \FusionPay\Kernel\ServiceContainer
     */
    public static function make($name, array $config)
    {
        $namespace = Kernel\Support\Str::studly($name);
        $application = "\\FusionPay\\{$namespace}\\Application";

        return new $application($config);
    }

    /**
     * Dynamically pass methods to the application.
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return self::make($name, ...$arguments);
    }
}
