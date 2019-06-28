<?php

namespace FusionPay\Console\Commands;

use Illuminate\Console\Command;
use FusionPay\Facade as FusionPay;

class Refund extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:refund {type} {--out_trade_no=} {--amount=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refund Command, type: refund-退款，query-查询';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $out_trade_no = $this->option('out_trade_no');
        $amount = $this->option('amount');
        $type = $this->argument('type');
        switch($type){
            case 'refund':
                $result = $this->refund($out_trade_no, $amount);
                break;
            case 'query':
                $result = $this->query($out_trade_no);
                break;
        }
        dump($result);
    }

    public function refund($out_trade_no, $amount){
        $app = FusionPay::payment();
        return $app->refund->byOutTradeNumber($out_trade_no, $amount);
    }

    public function query($out_trade_no){
        $app = FusionPay::payment();
        return $app->transaction->query($out_trade_no);
    }
}
