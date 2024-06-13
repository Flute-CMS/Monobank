<?php

namespace Omnipay\Monobank\Traits;

trait Parametrable
{
    public function setSecret($value)
    {
        return $this->setParameter('secret', $value);
    }

    public function getSecret()
    {
        return $this->getParameter('secret');
    }
}
