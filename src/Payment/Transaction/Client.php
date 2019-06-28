<?php

namespace FusionPay\Payment\Transaction;

use FusionPay\Payment\Kernel\BaseClient;

class Client extends BaseClient
{
    /**
     * Query transaction.
     *
     * @param string $out_trade_no
     *
     * @return \Psr\Http\Message\ResponseInterface|\FusionnPay\Kernel\Support\Collection|array|object|string
     *
     * @throws \FusionnPay\Kernel\Exceptions\InvalidConfigException
     */
    public function query(string $out_trade_no)
    {
        $params = [
            'client_id' => $this->app['config']->client_id,
            'out_trade_no' => $out_trade_no,
        ];

        return $this->requestRaw($this->wrap('/api/v2/query'), $params, 'get');
    }
}
