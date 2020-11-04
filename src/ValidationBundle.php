<?php

declare(strict_types=1);

namespace LeightonThomas\ValidationBundle;

use LeightonThomas\ValidationBundle\DependencyInjection\Compiler\CheckerPass;
use LeightonThomas\ValidationBundle\DependencyInjection\ValidationExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ValidationBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new CheckerPass());
        $container->registerExtension(new ValidationExtension());
    }
}
