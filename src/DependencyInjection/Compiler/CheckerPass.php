<?php

declare(strict_types=1);

namespace LeightonThomas\ValidationBundle\DependencyInjection\Compiler;

use LeightonThomas\Validation\ValidatorFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class CheckerPass implements CompilerPassInterface
{

    public const TAG = 'lt_validation.checker';

    public function process(ContainerBuilder $container)
    {
        $factoryClass = ValidatorFactory::class;
        if (! $container->has($factoryClass)) {
            return;
        }

        $factory = $container->getDefinition(ValidatorFactory::class);

        foreach ($container->findTaggedServiceIds(self::TAG) as $serviceId => $tags) {
            $factory->addMethodCall('register', [new Reference($serviceId)]);
        }
    }
}
