<?php

namespace Walker\AbaPayway;

class PayWay
{
    public $host = 'https://checkout-sandbox.payway.com.kh/api/payment-gateway/v1/payments/';//沙箱
//    private $host = 'https://checkout.payway.com.kh/api/payment-gateway/v1/payments/';//正式
//    public $host = '';
    public $merchantId = '';//(必)商户号
    public $publicKey = '';//(必)商户号
    public $language = 'km';//(选)km 高棉语 en 英文
    public $currency = 'KHR';//(选)货币 USD – US Dollars KHR – Khmer Riel
    protected $reqTime = '';

    public function __construct()
    {
        $this->reqTime = $this->getReqTime();
    }
    private function getReqTime()
    {
        return date('YmdHis');
    }
    protected function sendPost($url, $data)
    {
        $opts = array(
            CURLOPT_TIMEOUT => 30,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_URL => $url,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_USERAGENT => 'Mozilla/5.0 (X11; U; Linux x86_64; zh-CN; rv:1.9.2.14) Gecko/20110301 Fedora/3.6.14-1.fc14 Firefox/3.6.14',
        );
        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $data = curl_exec($ch);
        curl_close($ch);
        return json_decode($data,true);
    }
}