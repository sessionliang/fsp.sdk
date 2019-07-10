<?php

namespace FusionPay\Payment\Kernel;

use FusionPay\Kernel\Support;
use FusionPay\Kernel\Traits\HasHttpRequests;
use FusionPay\Payment\Application;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Psr\Http\Message\ResponseInterface;

class BaseClient
{
    use HasHttpRequests { request as performRequest; }

    /**
     * @var \FusionPay\Payment\Application
     */
    protected $app;

    /**
     * Constructor.
     *
     * @param \FusionPay\Payment\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;

        $this->setHttpClient($this->app['http_client']);
    }

    /**
     * Extra request params.
     *
     * @return array
     */
    protected function prepends()
    {
        return [];
    }

    /**
     * Make a API request.
     *
     * @param string $endpoint
     * @param array  $params
     * @param string $method
     * @param array  $options
     * @param bool   $returnResponse
     *
     * @return \Psr\Http\Message\ResponseInterface|\FusionPay\Kernel\Support\Collection|array|object|string
     *
     * @throws \FusionPay\Kernel\Exceptions\InvalidConfigException
     * @throws \FusionPay\Kernel\Exceptions\InvalidArgumentException
     */
    protected function request(string $endpoint, array $params = [], $method = 'post', array $options = [], $returnResponse = false)
    {
        $base = [
            'partner_id' => $this->app['config']['partner_id'],
            'client_id' => $this->app['config']['client_id'],
        ];

        $params = array_merge($base, $this->prepends(), $params);
        // 去除null, '', array, object key=sign的参数
        $params = array_filter($params, function($val){
            if($val == '' || $val == null || is_array($val) || is_object($val)){
                return 0;
            }
            return 1;
        });
        // 使用md5生成sign
        $encryptMethod = 'md5';
        // 获取api_key
        $secretKey = $this->app->getKey($endpoint);
        // 获取签名
        $params['sign'] = Support\generate_sign($params, $secretKey, $encryptMethod);

        $options = array_merge([
            'body' => Support\XML::build($params),
        ], $options);

        if(strtoupper($method) == 'GET'){
            $options = array_merge([
                'query' => http_build_query($params),
            ], $options);
        } else if(strtoupper($method) == 'POST'){
            $options = array_merge([
                'form_params' => $params,
            ], $options);
        }
        
        $this->pushMiddleware($this->logMiddleware(), 'log');
        $response = $this->performRequest($endpoint, $method, $options);
        return $returnResponse ? $response : $this->castResponseToType($response, $this->app->config->get('response_type'));
    }

    /**
     * Log the request.
     *
     * @return \Closure
     */
    protected function logMiddleware()
    {
        $formatter = new MessageFormatter($this->app['config']['http.log_template'] ?? MessageFormatter::DEBUG);

        return Middleware::log($this->app['logger'], $formatter);
    }

    /**
     * Make a request and return raw response.
     *
     * @param string $endpoint
     * @param array  $params
     * @param string $method
     * @param array  $options
     *
     * @return ResponseInterface
     *
     * @throws \FusionPay\Kernel\Exceptions\InvalidConfigException
     * @throws \FusionPay\Kernel\Exceptions\InvalidArgumentException
     */
    protected function requestRaw($endpoint, array $params = [], $method = 'post', array $options = [])
    {
        return $this->request($endpoint, $params, $method, $options, false);
    }

    /**
     * Request with SSL.
     *
     * @param string $endpoint
     * @param array  $params
     * @param string $method
     * @param array  $options
     *
     * @return \Psr\Http\Message\ResponseInterface|\FusionPay\Kernel\Support\Collection|array|object|string
     *
     * @throws \FusionPay\Kernel\Exceptions\InvalidConfigException
     * @throws \FusionPay\Kernel\Exceptions\InvalidArgumentException
     */
    protected function safeRequest($endpoint, array $params, $method = 'post', array $options = [])
    {
        $options = array_merge([
            'cert' => $this->app['config']->get('cert_path'),
            'ssl_key' => $this->app['config']->get('key_path'),
        ], $options);

        return $this->request($endpoint, $params, $method, $options);
    }

    /**
     * Wrapping an API endpoint.
     *
     * @param string $endpoint
     *
     * @return string
     */
    protected function wrap(string $endpoint): string
    {
        return $this->app->inSandbox() ? "sandbox/{$endpoint}" : $endpoint;
    }
}
