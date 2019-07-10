<?php

return [
    /*
     * 默认配置，将会合并到各模块中
     */
    'defaults' => [
        /*
         * 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
         */
        'response_type' => 'array',

        
        /*
         * 使用 Laravel 的缓存系统
         */
        'use_laravel_cache' => true,

        /*
         * 日志配置
         *
         * level: 日志级别，可选为：
         *                 debug/info/notice/warning/error/critical/alert/emergency
         * file：日志文件位置(绝对路径!!!)，要求可写权限
         */
        'log' => [
            'level' => env('FUSIONPAY_LOG_LEVEL', 'debug'),
            'file' => env('FUSIONPAY_LOG_FILE', storage_path('logs/fusionpay.log')),
        ],
    ],
    
    /**
     * api基础配置
     */
    'http' => [
        'base_uri' => 'https://merchant.fusionpay.co.uk',
    ],

    /*
     * 支付
     */
    'payment' => [
        'default' => [
            'sandbox'            => env('FUSIONPAY_PAYMENT_SANDBOX', false),
            'partner_id'         => env('FUSIONPAY_PAYMENT_PARTNER_ID', ''),
            'client_id'          => env('FUSIONPAY_PAYMENT_CLIENT_ID', ''),
            'key'                => env('FUSIONPAY_PAYMENT_KEY', 'key-for-signature'),
            'cert_path'          => env('FUSIONPAY_PAYMENT_CERT_PATH', 'path/to/cert/apiclient_cert.pem'),    // XXX: 绝对路径！！！！
            'key_path'           => env('FUSIONPAY_PAYMENT_KEY_PATH', 'path/to/cert/apiclient_key.pem'),      // XXX: 绝对路径！！！！
            'notify_url'         => 'http://example.com/payments/wechat-notify',                           // 默认支付结果通知地址
        ],
    ],
];