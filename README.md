# aggregate-payment
聚合支付

该项目集成支付宝、微信支付接口
提供更便利的调用方式

## 安装方式


```
composer require lisao/aggregate-payment
```

### 创建微信支付

```php
$pay_config = [
  'app_id'=>'',       //微信公众号开发者app_id
  'mch_id'=>'',       //商户号
  'key'=>'',          //商户秘钥
  'sign_type=>'MD5',  //签名方式
  'sand_box'=false,   //是否沙箱环境
];

//创建微信支付配置
$wx_config = new \LisaoPayment\WxPay\WxPayConfig($pay_config['app_id'], $pay_config['mch_id'], $pay_config['key'], $pay_config['sign_type'], $pay_config['sand_box']);

//实例化微信支付api
$wx_api = new \LisaoPayment\WxPay\WxPayApi($wx_config);

//创建订单配置
$order_config = new \LisaoPayment\WxConfig\CreateOrderConfig();
$order_config->set_out_trade_no('');//商户订单
$order_config->set_notify_url('');//回调地址
$order_config->set_body('');//订单详情
$order_config->set_nonce_str('');//随机字符串
$order_config->set_trade_type('JSAPI');//支付类型
$order_config->set_total_fee(1);//支付金额，单位分

//提交订单
try {
    $wx_order = $wx_api->create_order($order_config);
    $prepay_id = $wx_order['prepay_id'];
    print_r($wx_order);exit;
} catch (\LisaoPayment\WxPay\WxPayException $ex) {
    echo '订单创建失败';
}
```
