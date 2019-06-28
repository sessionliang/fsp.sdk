<?php

namespace FusionPay\Payment;

use Closure;
use FusionPay\BasicService;
use FusionPay\Kernel\Exceptions\InvalidArgumentException;
use FusionPay\Kernel\ServiceContainer;
use FusionPay\Kernel\Support;

/**
 * Class Application.
 *
 * @property \FusionPay\Payment\Transaction\Client       $transaction
 * @property \FusionPay\Payment\Refund\Client            $refund
 *
 */
class Application extends ServiceContainer
{
    /**
     * @var array
     */
    protected $providers = [
        Transaction\ServiceProvider::class,
        Refund\ServiceProvider::class,
    ];

    /**
     * @var array
     */
    protected $defaultConfig = [
        'http' => [
            'base_uri' => 'https://merchant.fusionpay.co.uk/api/v2',
        ],
    ];

    /**
     * 是否是沙箱环境
     */
    public function inSandbox(): bool
    {
        return (bool) $this['config']->get('sandbox');
    }

    /**
     * @param string|null $endpoint
     *
     * @return string
     *
     * @throws \FusionPay\Kernel\Exceptions\InvalidArgumentException
     */
    public function getKey(string $endpoint = null)
    {
        $key = $this->inSandbox() ? $this['sandbox']->getKey() : $this['config']->key;

        // if (32 !== strlen($key)) {
        //     throw new InvalidArgumentException(sprintf("'%s' should be 32 chars length.", $key));
        // }

        return $key;
    }

    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this['base'], $name], $arguments);
    }
}
