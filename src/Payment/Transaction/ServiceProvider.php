<?php

namespace FusionPay\Payment\Transaction;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['transaction'] = function ($app) {
            return new Client($app);
        };
    }
}
