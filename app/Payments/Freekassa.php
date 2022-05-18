<?php

namespace App\Payments;

class Free {
    public function __construct($config)
    {
        $this->config = $config;
    }

    public function newform()
    {
        return [
            'free_url' => [
                'label' => 'free_url',
                'description' => '',
                'type' => 'input',
            ],
            'merchant_id' => [
                'label' => 'merchant_id',
                'description' => '',
                'type' => 'input',
            ],
            'secret_word' => [
                'label' => 'secret_word',
                'description' => '',
                'type' => 'input',
            ]
        ];
    }

    public function pay($order)
    {
        try {
            $gateway = new \Library\Freekassa();
            $gateway->setMethod('free.trade.precreate');
            $gateway->setAppId($this->config['free_url']);
            $gateway->setPrivateKey($this->config['merchant_id']); // 可以是路径，也可以是密钥内容
            $gateway->setAlipayPublicKey($this->config['secret_word']); // 可以是路径，也可以是密钥内容
            $gateway->setBizContent([
                'subject' => config('v2board.app_name', 'V2Board') . ' - 订阅',
                'out_trade_no' => $order['trade_no'],
                'total_amount' => $order['total_amount'] / 100
            ]);
            $gateway->send();
            return [
                'type' => 0, // 0:qrcode 1:url
                'data' => $gateway->getQrCodeUrl()
            ];
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    }

    public function notify($params)
    {
        if ($params['trade_status'] !== 'TRADE_SUCCESS') return false;
        $gateway = new \Library\Freekassa();
        $gateway->setAppId($this->config['free_url']);
        $gateway->setPrivateKey($this->config['merchant_id']); // 可以是路径，也可以是密钥内容
        $gateway->setAlipayPublicKey($this->config['secret_word']); // 可以是路径，也可以是密钥内容
        try {
            if ($gateway->verify($params)) {
                /**
                 * Payment is successful
                 */
                return [
                    'trade_no' => $params['out_trade_no'],
                    'callback_no' => $params['trade_no']
                ];
            } else {
                /**
                 * Payment is not successful
                 */
                return false;
            }
        } catch (\Exception $e) {
            /**
             * Payment is not successful
             */
            return false;
        }
    }
}

