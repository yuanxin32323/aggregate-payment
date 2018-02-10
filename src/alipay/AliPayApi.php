<?php

/**
 * 离骚 支付宝支付类
 * @version 1.0 @ 2018-02-05
 * @see https://github.com/yuanxin32323
 */

namespace LisaoPayment\AliPay;

class AliPayApi {

    private $url; //接口地址
    private $config; //配置
    private $sandbox_signkey; //沙箱秘钥

    /**
     * 初始化
     * @param \LisaoPayment\AliPay\AliPayConfig $config 配置参数
     * @throws AliPayException
     */

    public function __construct(AliPayConfig $config) {
        $this->config = $config;
        //判断appid是否存在
        if (empty($config->get('app_id'))) {
            throw new AliPayException('PARAM_ERROR', "缺少app_id参数");
        }
        //判断public_key是否存在
        if (empty($config->get('public_key'))) {
            throw new AliPayException('PARAM_ERROR', "缺少public_key参数");
        }
        //判断private_key是否存在
        if (empty($config->get('private_key'))) {
            throw new AliPayException('PARAM_ERROR', "缺少private_key参数");
        }
        //判断签名方式是否正确
        if ($config->get('sign_type') !== 'RSA2' && $config->get('sign_type') !== 'RSA') {
            throw new AliPayException('PARAM_ERROR', "签名方式错误");
        }
        //判断是否为沙箱环境
        if ($config->get('sand_box')) {
            $this->url = 'https://openapi.alipaydev.com/gateway.do';
        } else {
            $this->url = 'https://openapi.alipay.com/gateway.do';
        }
    }

    /**
     * 统一下单接口
     * @param \LisaoPayment\WxConfig\UnifiedOrderConfig $param 统一下单接口参数
     * @return array 返回支付宝官方文档返回值
     * @throws WxPayException
     */
    public function micro_pay(\LisaoPayment\AliPayConfig\MicroPayConfig $param) {

        //获取请求地址
        $url = $this->url;
        $biz_content = $param->get_all();

        $data['app_id'] = $this->config->get('app_id');
        $data['method'] = $param->get_method();
        $data['charset'] = 'utf-8';
        $data['sign_type'] = $this->config->get('sign_type');
        $data['timestamp'] = date('Y-m-d H:i:s');
        $data['version'] = '1.0';
        if ($this->config->get('notify_url')) {
            $data['notify'] = $this->config->get('notify_url');
        }
        if ($this->config->get('app_auth_token')) {
            $data['app_auth_token'] = $this->config->get('app_auth_token');
        }
        //参数正确性判断
        if (empty($biz_content['out_trade_no'])) {
            throw new AliPayException('PARAM_ERROR', '缺少out_trade_no参数');
        }
        if (empty($biz_content['scene'])) {
            throw new AliPayException('PARAM_ERROR', '缺少scene参数');
        }

        if (empty($biz_content['auth_code'])) {
            throw new AliPayException('PARAM_ERROR', '缺少auth_code参数');
        }
        if (empty($biz_content['subject'])) {
            throw new AliPayException('PARAM_ERROR', '缺少subject参数');
        }
        if (empty($biz_content['total_amount'])) {
            throw new AliPayException('PARAM_ERROR', '缺少total_amount参数');
        }
        $data['biz_content'] = json_encode($biz_content);
        //签名
        $data['sign'] = $this->sign($data);
        //构建get请求参数
        $get_param = '';
        foreach ($data as $k => $v) {
            $get_param .= "{$k}=" . urlencode($v) . "&";
        }
        $url .= '?' . $get_param;


        $curl = new \LisaoPayment\curl\curl();
        $curl->setUrl($url);
        $result = $curl->get();
        $result = json_decode($result, TRUE);
        $response = $result[str_replace('.', '_', $param->method) . '_response'];
        if ($response) {
            $sign = $result['sign'];
            if ($this->check_sign($response, $sign)) {
                if ($response['code'] != 10000) {
                    throw new AliPayException($response['code'], $response['msg']);
                }
            } else {
                throw new AliPayException('SIGN_ERROR', '消息来源验签失败');
            }
        } else {
            throw new AliPayException('SYSTEM_ERROR', '请求失败，网络错误');
        }

        return $response;
    }

    /**
     * 签名
     * @param type $data
     * @return string 签名
     */
    public function sign($data) {
        $private_key = "-----BEGIN RSA PRIVATE KEY-----\n" .
                wordwrap($this->config->get('private_key'), 64, "\n", true) .
                "\n-----END RSA PRIVATE KEY-----";
        $res = openssl_get_privatekey($private_key);
        if (!$res) {
            throw new AliPayException('PRIVATE_KEY_ERROR', '商户私钥格式错误');
        } else {
            openssl_free_key($res);
        }
        ksort($data);
        $str_sign = '';
        $count = count($data);
        $i = 0;
        foreach ($data as $k => $v) {
            $i++;
            if ($v && substr($v, 0, 1) != '@') {
                if ($i === $count) {
                    $str_sign .= $k . '=' . $v;
                } else {
                    $str_sign .= $k . '=' . $v . '&';
                }
            }
        }

        if (strtoupper($this->config->get('sign_type')) === "RSA2") {
            openssl_sign($str_sign, $sign, $private_key, OPENSSL_ALGO_SHA256);
        } else {
            openssl_sign($str_sign, $sign, $private_key);
        }
        $sign = base64_encode($sign);
        return $sign;
    }

    /**
     * 验签
     * @param array $data 待验签数据
     * @param string $sign 返回签名
     * @param bool $is_asyn 是否为异步回调数据
     * @return bool 是否验签通过
     */
    public function check_sign(array $data, string $sign, bool $is_asyn = false) {
        $public_key = "-----BEGIN PUBLIC KEY-----\n" .
                wordwrap($this->config->get('public_key'), 64, "\n", true) .
                "\n-----END PUBLIC KEY-----";

        $res = openssl_get_publickey($public_key);
        if (!$res) {
            throw new AliPayException('PRIVATE_KEY_ERROR', '商户私钥格式错误');
        } else {
            $str = '';
            $count = count($data);
            $i = 0;
            //判断是否回调
            if ($is_asyn) {
                ksort($data);
                $count = count($data);
                $i = 0;
                foreach ($data as $k => $v) {
                    $i++;
                    if ($k == 'sign' || $k == 'sign_type') {
                        continue;
                    }
                    if ($i === $count) {
                        $str .= $k . '=' . urldecode($v);
                    } else {
                        $str .= $k . '=' . urldecode($v) . '&';
                    }
                }
                //签名类型
                $sign_type = $data['sign_type'] ?: $this->config->get('sign_type');
            } else {
                $str = json_encode($data, JSON_UNESCAPED_UNICODE);

                //签名类型
                $sign_type = $this->config->get('sign_type');
            }


            if (strtoupper($sign_type) == "RSA2") {
                $result = (bool) openssl_verify($str, base64_decode($sign), $res, OPENSSL_ALGO_SHA256);
            } else {
                $result = (bool) openssl_verify($str, base64_decode($sign), $res);
            }
            openssl_free_key($res);
        }
        return $result;
    }

    /**
     * 取随机字符
     */
    private function random_str($length = 32, $number = false) {
        $dictionary = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz"; //62位
        if ($number) {
            $dictionary = "0123456789";
        }
        $str = '';

        $max = strlen($dictionary) - 1;

        for ($i = 0; $i < $length; $i++) {
            $str .= $dictionary[rand(0, $max)];
        }
        return $str;
    }

}
