<?php

namespace FusionPay;

use FusionPay\Payment\Application as Payment;
use Illuminate\Support\ServiceProvider;
use FusionPay\Console\Commands\Refund;
use FusionPay\Kernel\Support\CacheBridge;

class FusionPayServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(FusionPay $extension)
    {
        if (! FusionPay::boot()) {
            return ;
        }

        //加载配置
        $this->publishes([__DIR__ . '/../config/' => config_path()]);

        //加载命令
        if ($this->app->runningInConsole()) {
            $this->commands([
                Refund::class, 
            ]);
        }
    }

    public function register()
    {
        $apps = [
            'payment' => Payment::class,
        ];

        foreach ($apps as $name => $class) {
            if (empty(config('fusionpay.'.$name))) {
                continue;
            }

            if (!empty(config('fusionpay.'.$name.'.key'))) {
                $accounts = [
                    'default' => config('fusionpay.'.$name),
                ];
                config(['fusionpay.'.$name.'.default' => $accounts['default']]);
            } else {
                $accounts = config('fusionpay.'.$name);
            }

            foreach ($accounts as $account => $config) {
                $this->app->singleton("fusionpay.{$name}.{$account}", function ($laravelApp) use ($name, $account, $config, $class) {
                    $app = new $class(array_merge(config('fusionpay.defaults', []), $config));
                    if (config('fusionpay.defaults.use_laravel_cache')) {
                        $app['cache'] = new CacheBridge($laravelApp['cache.store']);
                    }
                    $app['request'] = $laravelApp['request'];

                    return $app;
                });
            }
            $this->app->alias("fusionpay.{$name}.default", 'fusionpay.'.$name);

            $this->app->alias('fusionpay.'.$name, $class);
        }
    }
}