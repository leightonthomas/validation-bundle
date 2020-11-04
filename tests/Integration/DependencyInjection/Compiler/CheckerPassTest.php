<?php

declare(strict_types=1);

namespace Tests\LeightonThomas\ValidationBundle\Integration\DependencyInjection\Compiler;

use LeightonThomas\Validation\Checker\Scalar\IsScalarChecker;
use LeightonThomas\Validation\Checker\Scalar\Strings\LengthChecker;
use LeightonThomas\Validation\ValidatorFactory;
use LeightonThomas\ValidationBundle\DependencyInjection\Compiler\CheckerPass;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class CheckerPassTest extends AbstractCompilerPassTestCase
{

    /**
     * @test
     */
    public function itWillAddServicesTaggedWithConfiguredTagToTheValidatorFactory(): void
    {
        $this->setDefinition(ValidatorFactory::class, new Definition(ValidatorFactory::class));

        $scalarDefinition = new Definition(IsScalarChecker::class);
        $scalarDefinition->addTag(CheckerPass::TAG, []);
        $this->setDefinition(IsScalarChecker::class, $scalarDefinition);

        $lengthDefinition = new Definition(LengthChecker::class);
        $lengthDefinition->addTag(CheckerPass::TAG, []);
        $this->setDefinition(LengthChecker::class, $lengthDefinition);

        $this->container->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            ValidatorFactory::class,
            'register',
            [new Reference(IsScalarChecker::class)],
        );

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            ValidatorFactory::class,
            'register',
            [new Reference(LengthChecker::class)],
        );
    }

    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new CheckerPass());
    }
}
