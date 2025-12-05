<?php

namespace Uno\EventSdk;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Uno\EventSdk\DependencyInjection\Compiler\EventSubscriberPass;

class UnoEventSdkBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new EventSubscriberPass());
    }
}
