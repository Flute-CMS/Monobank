<?php

namespace Flute\Modules\Monobank\ServiceProviders;

use Flute\Core\Payments\Events\RegisterPaymentFactoriesEvent;
use Flute\Core\Support\ModuleServiceProvider;
use Flute\Modules\Monobank\Listeners\PaymentListener;

class MonobankServiceProvider extends ModuleServiceProvider
{
    public array $extensions = [];

    public function boot(\DI\Container $container): void
    {
        events()->addDeferredListener(RegisterPaymentFactoriesEvent::NAME, [PaymentListener::class, 'registerMonobank']);
    }

    public function register(\DI\Container $container): void
    {
    }
}