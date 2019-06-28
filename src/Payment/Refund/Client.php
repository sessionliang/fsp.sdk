<?php

namespace FusionPay\Payment\Refund;

use FusionPay\Payment\Kernel\BaseClient;

class Client extends BaseClient
{
    /**
     * Refund by out trade number.
     *
     * @param string $out_trade_no
     * @param string $amount
     * @param array  $optional
     *
     * @return \Psr\Http\Message\ResponseInterface|\FusionPay\Kernel\Support\Collection|array|object|string
     *
     * @throws \FusionPay\Kernel\Exceptions\InvalidConfigException
     */
    public function byOutTradeNumber(string $out_trade_no, string $amount, array $optional = [])
    {
        return $this->refund($amount, array_merge($optional, ['out_trade_no' => $out_trade_no]));
    }

    /**
     * Refund.
     *
     * @param string $amount
     * @param array  $optional
     *
     * @return \Psr\Http\Message\ResponseInterface|\FusionPay\Kernel\Support\Collection|array|object|string
     *
     * @throws \FusionPay\Kernel\Exceptions\InvalidConfigException
     */
    protected function refund(string $amount, $optional = [])
    {
        $params = array_merge([
            'amount' => $amount,
            'client_id' => $this->app['config']->client_id,
        ], $optional);
        return $this->requestRaw($this->wrap('/api/v2/refund'), $params, 'post');
    }
}
