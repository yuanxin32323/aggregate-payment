<?php

use LisaoPayment\AliPay\AliPayConfig;
use LisaoPayment\AliPay\AliPayApi;

require '../vendor/autoload.php';

require '../src/base/curl.php';
require '../src/base/config/InterfaceConfig.php';
require '../src/alipay/AliPayException.php';
require '../src/alipay/AliPayConfig.php';
require '../src/alipay/AliPayApi.php';

require '../src/alipay/config/MicroPayConfig.php';
require '../src/alipay/config/CreateOrderConfig.php';

require '../src/alipay/config/PreCreateOrderConfig.php';
require '../src/alipay/config/QueryOrderConfig.php';
//应用id
$app_id = '';
//支付宝公钥
$public_key = "";
//应用私钥
$private_key = "";
//回调地址
$notify_url = "";
//第三方授权
$app_auth_token = "";
//是否为沙箱
$sand_box = TRUE;
$config = new AliPayConfig($app_id, $public_key, $private_key, 'RSA2', $notify_url, $app_auth_token, $sand_box);

$api = new AliPayApi($config);

/*
 * 扫码枪支付
 */
$micro_pay_config = new \LisaoPayment\AliPayConfig\MicroPayConfig();
$micro_pay_config->set_auth_code('281914584696191408');
$micro_pay_config->set_body('测试内容');
$micro_pay_config->set_out_trade_no(time());
$micro_pay_config->set_scene('bar_code');
$micro_pay_config->set_subject('测试标题');
$micro_pay_config->set_total_amount(1);
/*
  try {
  $return = $api->micro_pay($micro_pay_config);
  } catch (LisaoPayment\AliPay\AliPayException $ex) {
  echo $ex->get_error_code();
  }
  print_r($return);exit;
 */
/*
 * 创建订单
 */
$create_order_config = new LisaoPayment\AliPayConfig\CreateOrderConfig();

$create_order_config->set_body('测试内容');
$create_order_config->set_out_trade_no(time());
$create_order_config->set_subject('测试标题');
$create_order_config->set_total_amount(100);
$create_order_config->set_buyer_id(2088000000000000);
/*
  try {
  $return = $api->create_order($create_order_config);
  } catch (LisaoPayment\AliPay\AliPayException $ex) {
  echo $ex->get_error_code(),$ex->get_error_msg();
  }
  print_r($return);
 * 
 */
/*
 * 创建预订单
 */
$pre_create_order_config = new LisaoPayment\AliPayConfig\PreCreateOrderConfig();

$pre_create_order_config->set_body('测试内容');
$pre_create_order_config->set_out_trade_no(time());
$pre_create_order_config->set_subject('测试标题');
$pre_create_order_config->set_total_amount(100);
/*
  try {
  $return = $api->pre_create_order($pre_create_order_config);
  } catch (LisaoPayment\AliPay\AliPayException $ex) {
  echo $ex->get_error_code(), $ex->get_error_msg();
  }
  print_r($return);
 */
$query_order_config = new \LisaoPayment\AliPayConfig\QueryOrderConfig();
$query_order_config->set_out_trade_no('1518243780');

try {
    $return = $api->query_order($query_order_config);
} catch (LisaoPayment\AliPay\AliPayException $ex) {
    echo $ex->get_error_code(), $ex->get_error_msg();
}
print_r($return);
