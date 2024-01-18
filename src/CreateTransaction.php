<?php

namespace Walker\AbaPayway;

class CreateTransaction extends PayWay
{
    /**
     * 必填项
     */
    public $tranId = '';//交易编号
    public $amount = 0;//订单金额 decimal USD 1.00   KHR 100
    public $paymentOption = 'abapay_deeplink';//(必)付款方式 cards abapay abapay_deeplink wechat alipay bakong


    /**
     * 选填
     */
    public $items = '';// [{'name':'test','quantity':'1','price':'1.00'}] 需要 base64 encode
    public $customFields = '';//自定义参数 数组
    public $returnParam = '';//string 或 json string – 可用于备注或为交易添加注释。return_params 将包含在向商户支付成功的回调中。“这是交易备注”或“mxBorGgaom778oliLLimoBhosD”或“{'key': 'value', 'Key': 'value', 'key': 'value' }”
    public $cancelUrl = '';//取消地址
    public $returnUrl = '';//回调地址
    public $viewType= '';//包含在获取付款页面的请求中hosted_view – 用于本机应用程序 Web 视图。checkout – 加载结帐弹出窗口而不重定向
    public $continueSuccessUrl = '';//支付成功跳转地址
    public $returnDeeplinkUrl = '';//商户可以使用此链接传递深层链接，以便aba移动应用程序可以将成功消息传递给商户应用程序。[链接] [参数值]

    public $email = '';//邮箱
    public $phone = '';//电话
    public $firstName = '';
    public $lastName = '';
    public $ctid = '';//消费者令牌 ID – 在 PayWay 上使用凭证存档功能时使用 仅在第二次购买交易时使用此参数
    public $pwt = '';//PayWay 令牌 – 在 PayWay 上使用凭证存档功能时使用
    public $type = 'purcahse';//支付方式 如果值留空，则“购买”设置为默认值；商户需要将值作为“预验证”传递以创建预验证交易。Pre-auth Purchase
    public $shipping = '';//邮费
    private function base64Encode()
    {
        if (!empty($this->items))$this->items = base64_encode(json_encode($this->items,true));
        if (!empty($this->returnDeeplinkUrl))$this->returnDeeplinkUrl = base64_encode($this->returnDeeplinkUrl);
//        if (!empty($this->returnParam))$this->returnParam = json_encode($this->returnParam,true);
        if (!empty($this->returnUrl))$this->returnUrl = base64_encode($this->returnUrl);
        if (!empty($this->customFields))$this->customFields = base64_encode(json_encode($this->customFields,true));
    }


    private function getParams()
    {
        $params  =  [
            'language'=>$this->language,
            'req_time'=>$this->reqTime,
            'merchant_id'=>$this->merchantId,
            'tran_id'=>$this->tranId,
            'ctid'=>$this->ctid,
            'pwt'=>$this->pwt,
            'firstname'=>$this->firstName,
            'lastname'=>$this->lastName,
            'email'=>$this->email,
            'phone'=>$this->phone,
            'amount'=>$this->amount,
            'type'=>$this->type,
            'payment_option'=>$this->paymentOption,
            'items'=>$this->items,
            'currency'=>$this->currency,
            'return_url'=>$this->returnUrl,
            'cancel_url'=>$this->cancelUrl,
            'continue_success_url'=>$this->continueSuccessUrl,
            'return_deeplink'=>$this->returnDeeplinkUrl,
            'custom_fields'=>$this->customFields,
            'return_param'=>$this->returnParam,
            'view_type'=>$this->viewType,
            'hash'=>$this->getHashToken()
        ];
        return $params;
    }

    /**
     * 获取 token
     * @return string
     */
    private function getHashToken()
    {

        $needEncodeParams = [
            $this->reqTime,
            $this->merchantId,
            $this->tranId,
            $this->amount,
            $this->items,
            $this->shipping,
            $this->ctid,
            $this->pwt,
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->phone,
            $this->type,
            $this->paymentOption,
            $this->returnUrl,
            $this->cancelUrl,
            $this->continueSuccessUrl,
            $this->returnDeeplinkUrl,
            $this->currency,
            $this->customFields,
            $this->returnParam,
        ];
        return base64_encode(hash_hmac('sha512', implode($needEncodeParams), $this->publicKey, true));
    }

    /**
     * @return bool|string
     */
    public function create()
    {
        $this->base64Encode();
        $params = $this->getParams();
        $response =  $this->sendPost($this->host.'purchase',$params);
        return $response;
    }
}