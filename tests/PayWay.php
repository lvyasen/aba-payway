<?php
use PHPUnit\Framework\TestCase;
use Walker\AbaPayway\CreateTransaction;

class PayWay extends TestCase
{
    public function testCreateTransaction()
    {
        $createTransaction = new CreateTransaction();
//        $createTransaction->host = 'https://checkout-sandbox.payway.com.kh/api/payment-gateway/v1/payments/';
        $createTransaction->merchantId = 'your merchantId';
        $createTransaction->publicKey = 'your publicKey';
        $createTransaction->tranId='wx202020222222';
        $createTransaction->amount = 100;
        $createTransaction->items = [
            ['name'=>'test','quantity'=>1,'price'=>'100']
        ];
        $res = $createTransaction->create();
        print_r($res);
    }
}