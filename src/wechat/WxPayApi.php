<?php

/**
 * 离骚 微信支付类
 * @version 1.0 @ 2018-02-05
 * @see https://github.com/yuanxin32323
 */

namespace LisaoPayment\WxPay;

class WxPayApi {

    private $config;
    private $sandbox_signkey; //沙箱秘钥

    /**
     * 初始化
     * @param \LisaoPayment\WxPay\WxPayConfig $config 配置参数
     */

    public function __construct(WxPayConfig $config) {
        $this->config = $config;
        //判断appid是否存在
        if (empty($config->get('appid'))) {
            throw new WxPayException('PARAM_ERROR', "缺少appid参数");
        }
        //判断mch_id是否存在
        if (empty($config->get('mch_id'))) {
            throw new WxPayException('PARAM_ERROR', "缺少mch_id参数");
        }
        //判断api_key是否存在
        if (empty($config->get('api_key'))) {
            throw new WxPayException('PARAM_ERROR', "缺少api_key参数");
        }
        //判断签名方式是否正确
        if ($config->get('sign_type') !== 'MD5' && $config->get('sign_type') !== 'HMAC-SHA256') {
            throw new WxPayException('PARAM_ERROR', "签名方式错误");
        }
        //判断是否为沙箱环境
        if ($config->get('sandbox')) {
            $this->sandbox_signkey = $this->get_sandbox_signkey();
        }
    }

    /**
     * 统一下单接口
     * @param \LisaoPayment\WxConfig\CreateOrderConfig $param 统一下单接口参数
     * @return array 返回微信官方文档的返回值
     * @throws WxPayException
     */
    public function create_order(\LisaoPayment\WxConfig\CreateOrderConfig $param) {
        $sandbox = $this->config->get('sandbox');
        if ($sandbox) {
            $api_key = $this->sandbox_signkey;
        } else {
            $api_key = $this->config->get('api_key');
        }
        //获取请求地址
        $url = $param->get_url($sandbox);
        $data = $param->get_all();
        $data['appid'] = $this->config->get('appid');
        $data['mch_id'] = $this->config->get('mch_id');
        $data['sign_type'] = $this->config->get('sign_type');
        //参数正确性判断
        if (empty($data['out_trade_no'])) {
            throw new WxPayException('PARAM_ERROR', '缺少out_trade_no参数');
        }
        if (empty($data['nonce_str'])) {
            throw new WxPayException('PARAM_ERROR', '缺少nonce_str参数');
        }

        if (empty($data['total_fee'])) {
            throw new WxPayException('PARAM_ERROR', '缺少total_fee参数');
        }
        if (empty($data['body'])) {
            throw new WxPayException('PARAM_ERROR', '缺少body参数');
        }
        if (empty($data['notify_url'])) {
            throw new WxPayException('PARAM_ERROR', '缺少notify_url参数');
        }
        if (empty($data['trade_type'])) {
            throw new WxPayException('PARAM_ERROR', '缺少trade_type参数');
        }

        //签名
        $data['sign'] = $this->sign($data, $api_key);
        $curl = new \LisaoPayment\curl\curl();
        $curl->setUrl($url);
        $result = $this->xml_to_arr($curl->post($this->arr_to_xml($data)));

        if ($result['return_code'] === 'SUCCESS') {
            //验证签名来源
            $sign = $result['sign'];
            unset($result['sign']);
            if ($sign != $this->sign($result, $api_key)) {

                throw new WxPayException('SIGN_ERROR', '消息来源验签失败');
            }
            if ($result['result_code'] === 'SUCCESS') {
                
            } else {
                throw new WxPayException($result['err_code'], $result['err_code_des']);
            }
        } else {

            throw new WxPayException($result['return_code'], $result['return_msg']);
        }
        return $result;
    }

    /**
     * 刷卡支付接口 （扫码枪支付）
     * @param \LisaoPayment\WxConfig\MicroPayConfig $param 统一下单接口参数
     * @return array 返回微信官方文档的返回值
     * @throws WxPayException
     */
    public function micro_pay(\LisaoPayment\WxConfig\MicroPayConfig $param) {
        $sandbox = $this->config->get('sandbox');
        if ($sandbox) {
            $api_key = $this->sandbox_signkey;
        } else {
            $api_key = $this->config->get('api_key');
        }
        //获取请求地址
        $url = $param->get_url($sandbox);
        $data = $param->get_all();
        $data['appid'] = $this->config->get('appid');
        $data['mch_id'] = $this->config->get('mch_id');
        $data['sign_type'] = $this->config->get('sign_type');
        //参数正确性判断
        if (empty($data['out_trade_no'])) {
            throw new WxPayException('PARAM_ERROR', '缺少out_trade_no参数');
        }
        if (empty($data['nonce_str'])) {
            throw new WxPayException('PARAM_ERROR', '缺少nonce_str参数');
        }
        if (empty($data['total_fee'])) {
            throw new WxPayException('PARAM_ERROR', '缺少total_fee参数');
        }
        if (empty($data['body'])) {
            throw new WxPayException('PARAM_ERROR', '缺少body参数');
        }
        if (empty($data['auth_code'])) {
            throw new WxPayException('PARAM_ERROR', '缺少auth_code参数');
        }

        //签名
        $data['sign'] = $this->sign($data, $api_key);
        $curl = new \LisaoPayment\curl\curl();
        $curl->setUrl($url);
        $result = $this->xml_to_arr($curl->post($this->arr_to_xml($data)));

        if ($result['return_code'] === 'SUCCESS') {
            //验证签名来源
            $sign = $result['sign'];
            unset($result['sign']);
            if ($sign != $this->sign($result, $api_key)) {

                throw new WxPayException('SIGN_ERROR', '消息来源验签失败');
            }
            if ($result['result_code'] === 'SUCCESS') {
                
            } else {
                throw new WxPayException($result['err_code'], $result['err_code_des']);
            }
        } else {

            throw new WxPayException($result['return_code'], $result['return_msg']);
        }
        return $result;
    }

    /**
     * 查询订单接口
     * @param \LisaoPayment\WxConfig\QueryOrderConfig $param 查询订单接口参数
     * @return array 返回微信官方文档的返回值
     * @throws WxPayException
     */
    public function query_order(\LisaoPayment\WxConfig\QueryOrderConfig $param) {
        $sandbox = $this->config->get('sandbox');
        if ($sandbox) {
            $api_key = $this->sandbox_signkey;
        } else {
            $api_key = $this->config->get('api_key');
        }
        //获取请求地址
        $url = $param->get_url($sandbox);
        $data = $param->get_all();
        $data['appid'] = $this->config->get('appid');
        $data['mch_id'] = $this->config->get('mch_id');
        $data['sign_type'] = $this->config->get('sign_type');
        //参数正确性判断
        if (empty($data['out_trade_no']) && empty($data['transaction_id'])) {
            throw new WxPayException('PARAM_ERROR', '缺少out_trade_no参数和transaction_id参数，二者必填其一');
        }
        if (empty($data['nonce_str'])) {
            throw new WxPayException('PARAM_ERROR', '缺少nonce_str参数');
        }


        //签名
        $data['sign'] = $this->sign($data, $api_key);
        $curl = new \LisaoPayment\curl\curl();
        $curl->setUrl($url);
        $result = $this->xml_to_arr($curl->post($this->arr_to_xml($data)));

        if ($result['return_code'] === 'SUCCESS') {
            //验证签名来源
            $sign = $result['sign'];
            unset($result['sign']);
            if ($sign != $this->sign($result, $api_key)) {

                throw new WxPayException('SIGN_ERROR', '消息来源验签失败');
            }
            if ($result['result_code'] === 'SUCCESS') {
                
            } else {
                throw new WxPayException($result['err_code'], $result['err_code_des']);
            }
        } else {

            throw new WxPayException($result['return_code'], $result['return_msg']);
        }
        return $result;
    }

    /**
     * 撤销订单接口
     * 调用支付接口后请勿立即调用撤销订单API，建议支付后至少15s后再调用撤销订单接口。
     * @param \LisaoPayment\WxConfig\RevokeOrderConfig $param 查询订单接口参数
     * @return array 返回微信官方文档的返回值
     * @throws WxPayException
     */
    public function revoke_order(\LisaoPayment\WxConfig\RevokeOrderConfig $param) {
        $sandbox = $this->config->get('sandbox');
        if ($sandbox) {
            $api_key = $this->sandbox_signkey;
        } else {
            $api_key = $this->config->get('api_key');
        }
        //获取请求地址
        $url = $param->get_url($sandbox);
        $data = $param->get_all();
        $data['appid'] = $this->config->get('appid');
        $data['mch_id'] = $this->config->get('mch_id');
        $data['sign_type'] = $this->config->get('sign_type');
        //参数正确性判断
        if (empty($data['out_trade_no']) && empty($data['transaction_id'])) {
            throw new WxPayException('PARAM_ERROR', '缺少out_trade_no参数和transaction_id参数，二者必填其一');
        }
        if (empty($data['nonce_str'])) {
            throw new WxPayException('PARAM_ERROR', '缺少nonce_str参数');
        }
        if (empty($param->cert) || !file_exists($param->cert)) {

            throw new WxPayException('PARAM_ERROR', '缺少商户证书apiclient_cert');
        }
        if (empty($param->key) || !file_exists($param->key)) {

            throw new WxPayException('PARAM_ERROR', '缺少商户证书秘钥apiclient_key');
        }
        //签名
        $data['sign'] = $this->sign($data, $api_key);
        $curl = new \LisaoPayment\curl\curl();
        $curl->setUrl($url);
        $curl->set(CURLOPT_SSLCERT, $param->cert);
        $curl->set(CURLOPT_SSLKEY, $param->key);
        $result = $this->xml_to_arr($curl->post($this->arr_to_xml($data)));

        if ($result['return_code'] === 'SUCCESS') {
            //验证签名来源
            $sign = $result['sign'];
            unset($result['sign']);
            if ($sign != $this->sign($result, $api_key)) {

                throw new WxPayException('SIGN_ERROR', '消息来源验签失败');
            }
            if ($result['result_code'] === 'SUCCESS') {
                
            } else {
                throw new WxPayException($result['err_code'], $result['err_code_des']);
            }
        } else {

            throw new WxPayException($result['return_code'], $result['return_msg']);
        }
        return $result;
    }

    /**
     * 退款接口
     * @param \LisaoPayment\WxConfig\RefundOrderConfig $param 退款接口参数
     * @return array 返回微信官方文档的返回值
     * @throws WxPayException
     */
    public function refund_order(\LisaoPayment\WxConfig\RefundOrderConfig $param) {
        $sandbox = $this->config->get('sandbox');
        if ($sandbox) {
            $api_key = $this->sandbox_signkey;
        } else {
            $api_key = $this->config->get('api_key');
        }
        //获取请求地址
        $url = $param->get_url($sandbox);
        $data = $param->get_all();
        $data['appid'] = $this->config->get('appid');
        $data['mch_id'] = $this->config->get('mch_id');
        $data['sign_type'] = $this->config->get('sign_type');
        //参数正确性判断
        if (empty($data['out_trade_no']) && empty($data['transaction_id'])) {
            throw new WxPayException('PARAM_ERROR', '缺少out_trade_no参数和transaction_id参数，二者必填其一');
        }
        if (empty($data['out_refund_no'])) {
            throw new WxPayException('PARAM_ERROR', '缺少out_refund_no参数');
        }
        if (empty($data['nonce_str'])) {
            throw new WxPayException('PARAM_ERROR', '缺少nonce_str参数');
        }
        if (empty($data['total_fee'])) {
            throw new WxPayException('PARAM_ERROR', '缺少total_fee参数');
        }
        if (empty($data['refund_fee'])) {
            throw new WxPayException('PARAM_ERROR', '缺少refund_fee参数');
        }
        if (empty($param->cert) || !file_exists($param->cert)) {

            throw new WxPayException('PARAM_ERROR', '缺少商户证书apiclient_cert');
        }
        if (empty($param->key) || !file_exists($param->key)) {

            throw new WxPayException('PARAM_ERROR', '缺少商户证书秘钥apiclient_key');
        }
        //签名
        $data['sign'] = $this->sign($data, $api_key);
        $curl = new \LisaoPayment\curl\curl();
        $curl->setUrl($url);
        $curl->set(CURLOPT_SSLCERT, $param->cert);
        $curl->set(CURLOPT_SSLKEY, $param->key);
        $result = $this->xml_to_arr($curl->post($this->arr_to_xml($data)));
        if ($result['return_code'] === 'SUCCESS') {
            //验证签名来源
            $sign = $result['sign'];
            unset($result['sign']);
            if ($sign != $this->sign($result, $api_key)) {

                throw new WxPayException('SIGN_ERROR', '消息来源验签失败');
            }
            if ($result['result_code'] === 'SUCCESS') {
                
            } else {
                throw new WxPayException($result['err_code'], $result['err_code_des']);
            }
        } else {

            throw new WxPayException($result['return_code'], $result['return_msg']);
        }
        return $result;
    }

    /**
     * 查询退款接口
     * @param \LisaoPayment\WxConfig\QueryRefundConfig $param 查询退款接口参数
     * @return array 返回微信官方文档的返回值
     * @throws WxPayException
     */
    public function query_refund(\LisaoPayment\WxConfig\QueryRefundConfig $param) {
        $sandbox = $this->config->get('sandbox');
        if ($sandbox) {
            $api_key = $this->sandbox_signkey;
        } else {
            $api_key = $this->config->get('api_key');
        }
        //获取请求地址
        $url = $param->get_url($sandbox);
        $data = $param->get_all();
        $data['appid'] = $this->config->get('appid');
        $data['mch_id'] = $this->config->get('mch_id');
        $data['sign_type'] = $this->config->get('sign_type');
        //参数正确性判断
        if (empty($data['refund_id']) && empty($data['out_refund_no']) && empty($data['out_trade_no']) && empty($data['transaction_id'])) {
            throw new WxPayException('PARAM_ERROR', '缺少out_trade_no参数、transaction_id参数、out_refund_no参数、refund_id参数，四者必填其一');
        }

        if (empty($data['nonce_str'])) {
            throw new WxPayException('PARAM_ERROR', '缺少nonce_str参数');
        }
        //签名
        $data['sign'] = $this->sign($data, $api_key);
        $curl = new \LisaoPayment\curl\curl();
        $curl->setUrl($url);
        $result = $this->xml_to_arr($curl->post($this->arr_to_xml($data)));
        if ($result['return_code'] === 'SUCCESS') {
            //验证签名来源
            $sign = $result['sign'];
            unset($result['sign']);
            if ($sign != $this->sign($result, $api_key)) {

                throw new WxPayException('SIGN_ERROR', '消息来源验签失败');
            }
            if ($result['result_code'] === 'SUCCESS') {
                
            } else {
                throw new WxPayException($result['err_code'], $result['err_code_des']);
            }
        } else {

            throw new WxPayException($result['return_code'], $result['return_msg']);
        }
        return $result;
    }

    /**
     * 签名
     * @param type $data
     * @param type $api_key
     * @return type
     */
    public function sign($data, $api_key) {
        ksort($data);
        $str_sign = '';
        foreach ($data as $k => $v) {
            if ($v) {
                $str_sign .= $k . '=' . $v . '&';
            }
        }
        if ($this->config->get('sign_type') === "MD5") {
            $sign = strtoupper(md5($str_sign . 'key=' . $api_key));
        } else {
            $sign = strtoupper(hash_hmac('sha256', $str_sign . 'key=' . $api_key, $api_key, FALSE));
        }
        return $sign;
    }

    /**
     * 数组转xml
     * @param type $data
     * @return string
     * @throws WxPayException
     */
    private function arr_to_xml($data) {
        if (!is_array($data) || count($data) <= 0) {
            throw new WxPayException("数组数据异常！");
        }
        $xml = "<xml>";
        foreach ($data as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    /**
     * xml消息解析
     */
    private function xml_to_arr($data) {
        libxml_disable_entity_loader(true);
        $postObj = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
        $temp = [];
        foreach ($postObj as $k => $v) {
            $temp[$k] = (string) $v;
        }
        return $temp;
    }

    /**
     * 获取沙箱秘钥
     */
    private function get_sandbox_signkey() {
        $url = "https://api.mch.weixin.qq.com/sandboxnew/pay/getsignkey";
        $curl = new \LisaoPayment\curl\curl();
        $curl->setUrl($url);
        $data = [
            'mch_id' => $this->config->get('mch_id'),
            'nonce_str' => $this->random_str(),
        ];
        $data['sign'] = $this->sign($data, $this->config->get('api_key'));
        $result = $this->xml_to_arr($curl->post($this->arr_to_xml($data)));

        if ($result['return_code'] === 'SUCCESS') {
            return $result['sandbox_signkey'];
        } else {
            throw new WxPayException($result['return_code'], $result['return_msg']);
        }
        return false;
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
