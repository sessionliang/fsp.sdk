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


    /**
     * Download settlement.
     *
     * @param string $settlement_date
     * @param string $gateway_config_code
     *
     * @return \Psr\Http\Message\ResponseInterface|\FusionnPay\Kernel\Support\Collection|array|object|string
     *
     * @throws \FusionnPay\Kernel\Exceptions\InvalidConfigException
     */
    public function downloadSettlement(string $settlement_date, string $gateway_config_code)
    {
        $params = [
            'client_id' => $this->app['config']->client_id,
            'settlement_date' => $settlement_date,
            'gateway_config_code' => $gateway_config_code
        ];

        return $this->requestRaw($this->wrap('/api/v2/download-settlement'), $params, 'get');
    }
}
