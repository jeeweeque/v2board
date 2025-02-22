<?php

namespace App\Payments;

class EPay {
    public function __construct($config)
    {
        $this->config = $config;
    }

    public function form()
    {
        return [
            'url' => [
                'label' => 'URL',
                'description' => '',
                'type' => 'input',
            ],
            'pid' => [
                'label' => 'PID',
                'description' => '',
                'type' => 'input',
            ],
            'key' => [
                'label' => 'KEY',
                'description' => '',
                'type' => 'input',
            ]
        ];
    }

    public function pay($order)
    {
         
          $sig = [

            'm' => $this->config['pid'],
            'oa' => $order['total_amount'] / 100,
            'currency' => 'RUB', 
            'o' => $order['trade_no']
         ];

        $params = [
            'oa' => $order['total_amount'] / 100,
            'currency' => 'RUB', 
            'm' => $this->config['pid'],
            'o' => $order['trade_no']
        ];

        $signr = "16081:".$sig['oa'].":9U7&7D6ksKVmc>N:RUB:".$sig['o'];
        ksort($params);
        reset($params);
        $str = stripslashes(urldecode(http_build_query($sig)));
        $sig['s'] = md5($signr);
        $params['sign_type'] = 'MD5';
        return [
            'type' => 1, // 0:qrcode 1:url
            'data' => $this->config['url']  ."?". http_build_query($sig)
        ];
    }

    public function notify($params)
    {
        $sign = $params['sign'];
        unset($params['sign']);
        unset($params['sign_type']);
        ksort($params);
        reset($params);
        $str = stripslashes(urldecode(http_build_query($params))) . $this->config['key'];
        if ($sign !== md5($str)) {
            return false;
        }
        return [
            'trade_no' => $params['out_trade_no'],
            'callback_no' => $params['trade_no']
        ];
    }
}