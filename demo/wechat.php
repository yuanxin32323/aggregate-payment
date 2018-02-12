<?php
error_reporting(E_ERROR || E_PARSE);

use LisaoPayment\WxPay\WxPayConfig;
use LisaoPayment\WxPay\WxPayApi;

define('ROOT_PATH', dirname(dirname(__FILE__)));

require ROOT_PATH . '/vendor/autoload.php';

require ROOT_PATH . '/src/wechat/WxPayException.php';
require ROOT_PATH . '/src/wechat/WxPayConfig.php';
require ROOT_PATH . '/src/wechat/WxPayApi.php';

require ROOT_PATH . '/src/base/config/InterfaceConfig.php';
require ROOT_PATH . '/src/base/curl.php';

require ROOT_PATH . '/src/wechat/config/CreateOrderConfig.php';
require ROOT_PATH . '/src/wechat/config/QueryOrderConfig.php';
require ROOT_PATH . '/src/wechat/config/RefundOrderConfig.php';
require ROOT_PATH . '/src/wechat/config/QueryRefundConfig.php';
require ROOT_PATH . '/src/wechat/config/MicroPayConfig.php';
require ROOT_PATH . '/src/wechat/config/RevokeOrderConfig.php';
$wechat_config = require ROOT_PATH . '/demo/config/wechat.config.php';

//创建配置
$config = new WxPayConfig($wechat_config['app_id'], $wechat_config['mch_id'], $wechat_config['api_key'], 'MD5', $wechat_config['sand_box']);

$api = new WxPayApi($config);
$action = $_GET['action'];
switch ($action) {
    //下单
    case 'unified_order':
        $order_no = time();
        $create_order = new \LisaoPayment\WxConfig\CreateOrderConfig();
        $create_order->set_body($_POST['body']);
        $create_order->set_nonce_str(time());
        $create_order->set_notify_url('http://xxxx.xicp.net/notify/wechat.notify.php');
        $create_order->set_out_trade_no($_POST['out_trade_no']);
        $create_order->set_total_fee($_POST['total_fee']);
        $create_order->set_trade_type('NATIVE');
        try {

            $return = $api->create_order($create_order);
        } catch (\LisaoPayment\WxPay\WxPayException $ex) {
            echo '错误代码：' . $ex->get_error_code() . ' 错误信息：' . $ex->get_error_msg();
            exit;
        }
        /*
          $qrCode = new \Endroid\QrCode\QrCode();
          $qrCode->setText($text)
          ->setSize(300)
          ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
          ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
          ->setLabelFontSize(16);
          $qrCode->writeFile($file);
          print_r($return);
          exit;
         * 
         */
        break;
}


/*
 * 创建订单
 */
/*
  $order = new WxPayApi($config);
  //设置参数
  $order_config = new UnifiedOrderConfig();
  $order_config->set_nonce_str(123456);
  $order_config->set_body('hahaha');
  $order_config->set_out_trade_no(time());
  $order_config->set_total_fee(1);
  $order_config->set_notify_url('http://www.weixin.qq.com/wxpay/pay.php');
  $order_config->set_trade_type('NATIVE');
 */
?>
<!DOCTYPE html>
<html>
    <head>
        <title>index</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link href="media/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <script src="media/js/jquery.min.js" type="text/javascript"></script>
        <script src="media/js/bootstrap.min.js" type="text/javascript"></script>
    </head>
    <body>
        <div class="container">
            <div style="margin-top:100px;"></div>
            <div class="row clearfix">
                <div class="col-md-2 column">
                </div>
                <div class="col-md-8 column">
                    <div class="tabbable" id="tabs-2969">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#panel-1" data-toggle="tab">统一下单</a>
                            </li>

                            <li class="">
                                <a href="#panel-2" data-toggle="tab">刷卡支付</a>
                            </li>
                            <li class="">
                                <a href="#panel-3" data-toggle="tab">退款</a>
                            </li>
                            <li class="">
                                <a href="#panel-4" data-toggle="tab">撤单</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="panel-1">
                                <form role="form" method="post" action="?action=unified_order">
                                    <div class="form-group">
                                        <label>订单号</label><input type="text" name="out_trade_no" value="OD<?php echo time(); ?>" class="form-control" />
                                    </div>
                                    <div class="form-group">
                                        <label>价格：(单位分)</label><input type="text" name="total_fee" value="1" class="form-control" />
                                    </div>

                                    <div class="form-group">
                                        <label>详情</label><input type="text" name="body" value="测试订单" class="form-control" />
                                    </div>

                                    <button type="submit" class="btn btn-info btn-block">提交</button>
                                </form>
                            </div>
                            <div class="tab-pane" id="panel-2">

                            </div>
                            <div class="tab-pane" id="panel-3">

                            </div>
                            <div class="tab-pane" id="panel-4">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 column">
                </div>
            </div>
    </body>
</html>

