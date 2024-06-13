<?php

namespace Flute\Modules\Monobank\Listeners;

class PaymentListener
{
    public static function registerMonobank()
    {
        app()->getLoader()->addPsr4('Omnipay\\Monobank\\', module_path('Monobank', 'Omnipay/'));
        app()->getLoader()->register();
    }
}