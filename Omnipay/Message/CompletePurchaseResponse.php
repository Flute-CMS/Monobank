<?php

namespace Omnipay\Monobank\Message;

use Omnipay\Common\Message\AbstractResponse;

class CompletePurchaseResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        return isset($this->data['status']) && $this->data['status'] === 'success';
    }

    public function getTransactionId()
    {
        return $this->data['reference'] ?? null;
    }

    public function getMessage()
    {
        if( $this->data['status'] === 'processing' ) {
            return "Payment status is processing";
        }

        return $this->data['failureReason'] ?? "Unknown reason";
    }
}
