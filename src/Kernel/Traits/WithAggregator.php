<?php

declare(strict_types=1);

namespace FusionPay\Kernel\Traits;

trait WithAggregator
{
    /**
     * Aggregate.
     */
    protected function aggregate()
    {
        foreach (config('fusionpay') as $key => $value) {
            $this['config']->set($key, $value);
        }
    }
}
