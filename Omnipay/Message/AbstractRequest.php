<?php

namespace Omnipay\Monobank\Message;

use Omnipay\Common\Message\AbstractRequest as OmnipayRequest;
use Omnipay\Monobank\Traits\Parametrable;

abstract class AbstractRequest extends OmnipayRequest
{
    use Parametrable;
}
