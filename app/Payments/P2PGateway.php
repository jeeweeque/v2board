<?php

namespace App\Payments;

use Omnipay\Common\AbstractGateway;
use Omnipay\Qiwi\Message\NotificationRequest;
use Omnipay\Qiwi\Message\PurchaseRequest;

/**
 * @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface capture(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface completePurchase(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface refund(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface void(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface createCard(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface updateCard(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface deleteCard(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface authorize(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface fetchTransaction(array $options = [])
 */
class P2PGateway extends AbstractGateway
{
    public function getName()
    {
        return 'Qiwi P2P';
    }

class P2PGateway {
    public function __construct($config)
    {
        $this->config = $config;
    }

    public function form()
    {
        return [
            'public_key' => [
                'label' => 'public_key',
                'description' => 'public key',
                'type' => 'input',
            ],
            'secret_key' => [
                'label' => 'secret_key',
                'description' => 'secret key',
                'type' => 'input',
            ],
            'success_url' => [
                'label' => 'success_url',
                'description' => 'success_url',
                'type' => 'input',
            ],
            'custom_fields' => [
                'label' => 'custom_fields',
                'description' => 'custom_fields',
                'type' => 'input',
            ],
        ];
    }

    public function getPublicKey()
    {
        return $this->getParameter('public_key');
    }

    public function setPublicKey($value)
    {
        return $this->setParameter('public_key', $value);
    }

    public function getSecretKey()
    {
        return $this->getParameter('secret_key');
    }

    public function setSecretKey($value)
    {
        return $this->setParameter('secret_key', $value);
    }

    public function getSuccessUrl()
    {
        return $this->getParameter('success_url');
    }

    public function setSuccessUrl($value)
    {
        return $this->setParameter('success_url', $value);
    }

    public function getCustomFields()
    {
        return $this->getParameter('custom_fields');
    }

    public function setCustomFields($value)
    {
        return $this->setParameter('custom_fields', $value);
    }

    /**
     * @param array $parameters
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function purchase(array $parameters = [])
    {
        return $this->createRequest(PurchaseRequest::class, $parameters);
    }

    /**
     * Handle notification callback.
     * Replaces completeAuthorize() and completePurchase()
     *
     * @return \Omnipay\Common\Message\AbstractRequest|NotificationRequest
     */
    public function acceptNotification(array $parameters = [])
    {
        return $this->createRequest(NotificationRequest::class, $parameters);
    }
}