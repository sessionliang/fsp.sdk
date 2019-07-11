<?php

namespace FusionPay\Console\Commands;

use Illuminate\Console\Command;
use FusionPay\Facade as FusionPay;
use FusionPay\Factory as FPFactory;
use App\Models\CompanyInfo;
use App\Models\Payment;

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
        try{
            $payment = Payment::where('out_trade_no', $out_trade_no)->first();
            $company_info = CompanyInfo::where('company_uuid', $payment->company_uuid)->first();
            // $app = FusionPay::payment();
            $app = FPFactory::make('payment', array_merge(config('fusionpay.payment.default'),[
                'client_id' => $company_info->client_id,
            ]));
            $app->refund->byOutTradeNumber($out_trade_no, $amount);
        }
        catch(\GuzzleHttp\Exception\RequestException $e){
            $res = json_decode($e->getResponse()->getBody()->getContents());
            admin_error('Error', 'Refund Error. '.$res->message);
        }
        catch(\Exception $e){
            admin_error('Error', 'Refund Error. '.$e->getMessage());
        }
    }

    public function query($out_trade_no){
        try{
            $payment = Payment::where('out_trade_no', $out_trade_no)->first();
            $company_info = CompanyInfo::where('company_uuid', $payment->company_uuid)->first();
            // $app = FusionPay::payment();
            $app = FPFactory::make('payment', array_merge(config('fusionpay.payment.default'),[
                'client_id' => $company_info->client_id,
            ]));
            return $app->transaction->query($out_trade_no);
        }
        catch(\GuzzleHttp\Exception\RequestException $e){
            $res = json_decode($e->getResponse()->getBody()->getContents());
            admin_error('Error', 'Refund Error. '.$res->message);
        }
        catch(\Exception $e){
            admin_error('Error', 'Refund Error. '.$e->getMessage());
        }
    }
}
