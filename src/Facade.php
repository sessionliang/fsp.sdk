<?php

namespace FusionPay;

use Illuminate\Support\Facades\Facade as LaravelFacade;

/**
 * Class Facade.
 *
 */
class Facade extends LaravelFacade
{
    /**
     * 默认为 Server.
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'fusionpay.payment';
    }

    /**
     * @return \FusionPay\Payment\Application
     */
    public static function payment($name = '')
    {
        return $name ? app('fusionpay.payment.'.$name) : app('fusionpay.payment');
    }
}
