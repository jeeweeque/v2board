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
        $params = [
            'money' => $order['total_amount'] / 100,
            'out_trade_no' => $order['trade_no'],
            'merchant_id' => $this->config['pid'],
            'secret_word' => $this->config['key'],
            'url_pay' => $this->config['url'],
            'currency' => 'RUB', 

        ];
      
       
        $signm = md5($params['merchant_id'].':'.$params['money'].':'.$params['secret_word'].':'.$params['currency'].':'.$params['out_trade_no']);
        $pay =  $this->config['url'] . '?m='.$params['merchant_id'].'&oa='.$params['money'].'&currency='.$params['currency'].'&o='.$params['out_trade_no'].'&s='.$sign;
       
        return $pay;



    }


}
