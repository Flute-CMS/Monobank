<?php

namespace Omnipay\Monobank\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    public function isSuccessful()
    {
        return isset($this->data['invoiceId']) && !empty($this->data['invoiceId']);
    }

    public function isRedirect()
    {
        return isset($this->data['pageUrl']);
    }

    public function getRedirectUrl()
    {
        return $this->data['pageUrl'];
    }

    public function getTransactionId()
    {
        return $this->data['transactionId'];
    }

    public function getMessage()
    {
        return $this->data['errText'] ?? null;
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }

    public function getRedirectData()
    {
        return null;
    }
}
