<?php

/**
 * 离骚 微信订单类
 * @version 1.0 @ 2018-02-05
 * @see https://github.com/yuanxin32323
 */

namespace LisaoPayment\WxPay;

class WxPayOrder {

    private $config;
    private $sandbox_signkey; //沙箱秘钥

    /**
     * 初始化
     * @param \LisaoPayment\WxPay\WxPayConfig $config 配置参数
     */

    public function __construct(WxPayConfig $config) {
        $this->config = $config;
        $this->sandbox_signkey = $this->get_sandbox_signkey();
    }

    public function create_order(\LisaoPayment\WxConfig\UnifiedOrderConfig $param) {
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
        //签名
        $data['sign'] = $this->sign($data, $api_key);
        $curl = new \LisaoPayment\curl\curl();
        $curl->setUrl($url);
        $result = $this->xml_to_arr($curl->post($this->arr_to_xml($data)));

        if ($result['return_code'] === 'SUCCESS') {

            if ($result['result_code'] === 'SUCCESS') {
                //验证签名来源
                $sign = $result['sign'];
                unset($result['sign']);
                if ($sign != $this->sign($result, $api_key)) {

                    throw new WxPayException('消息来源验签失败');
                }
            } else {
                throw new WxPayException($result['err_code_des']);
            }
        } else {

            throw new WxPayException($result['return_msg']);
        }
        return $result;
    }

    /**
     * 签名
     * @param type $data
     * @param type $api_key
     * @return type
     */
    private function sign($data, $api_key) {
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
            throw new WxPayException($result['return_msg']);
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
