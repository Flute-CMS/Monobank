<?php

namespace Omnipay\Monobank;

use Omnipay\Common\AbstractGateway;
use Omnipay\Monobank\Traits\Parametrable;

class Gateway extends AbstractGateway
{
    use Parametrable;

    public function getName()
    {
        return 'Monobank';
    }

    public function getDefaultParameters()
    {
        return [
            'secret' => '',
        ];
    }

    public function purchase(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\Monobank\Message\PurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\Monobank\Message\CompletePurchaseRequest', $parameters);
    }
}
