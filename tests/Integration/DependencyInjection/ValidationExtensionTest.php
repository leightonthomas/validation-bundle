<?php

declare(strict_types=1);

namespace Tests\LeightonThomas\ValidationBundle\Integration\DependencyInjection;

use LeightonThomas\Validation\Checker\AnythingChecker;
use LeightonThomas\Validation\Checker\Arrays\IsArrayChecker;
use LeightonThomas\Validation\Checker\Arrays\IsDefinedArrayChecker;
use LeightonThomas\Validation\Checker\CallbackChecker;
use LeightonThomas\Validation\Checker\Checker;
use LeightonThomas\Validation\Checker\Combination\ComposeChecker;
use LeightonThomas\Validation\Checker\Combination\IntersectionChecker;
use LeightonThomas\Validation\Checker\Combination\UnionChecker;
use LeightonThomas\Validation\Checker\Object\IsInstanceOfChecker;
use LeightonThomas\Validation\Checker\Scalar\IsScalarChecker;
use LeightonThomas\Validation\Checker\Scalar\Numeric\IsGreaterThanChecker;
use LeightonThomas\Validation\Checker\Scalar\Numeric\IsLessThanChecker;
use LeightonThomas\Validation\Checker\Scalar\Strings\LengthChecker;
use LeightonThomas\Validation\Checker\Scalar\Strings\RegexChecker;
use LeightonThomas\Validation\Checker\StrictEqualsChecker;
use LeightonThomas\Validation\ValidatorFactory;
use LeightonThomas\ValidationBundle\DependencyInjection\Compiler\CheckerPass;
use LeightonThomas\ValidationBundle\DependencyInjection\ValidationExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

class ValidationExtensionTest extends AbstractExtensionTestCase
{

    /**
     * @test
     */
    public function itWillHaveTheExpectedServicesOnCreation()
    {
        $this->load();

        $this->container->compile();

        $this->assertContainerBuilderHasService(ValidatorFactory::class, ValidatorFactory::class);

        $this->assertChecker(IsArrayChecker::class);
        $this->assertChecker(IsDefinedArrayChecker::class);
        $this->assertChecker(ComposeChecker::class);
        $this->assertChecker(IntersectionChecker::class);
        $this->assertChecker(UnionChecker::class);
        $this->assertChecker(IsInstanceOfChecker::class);
        $this->assertChecker(IsGreaterThanChecker::class);
        $this->assertChecker(IsLessThanChecker::class);
        $this->assertChecker(LengthChecker::class);
        $this->assertChecker(RegexChecker::class);
        $this->assertChecker(IsScalarChecker::class);
        $this->assertChecker(AnythingChecker::class);
        $this->assertChecker(CallbackChecker::class);
        $this->assertChecker(StrictEqualsChecker::class);
    }

    protected function getContainerExtensions(): array
    {
        return [new ValidationExtension()];
    }

    /**
     * @param string $fqcn
     *
     * @psalm-param class-string<Checker> $fqcn
     */
    private function assertChecker(string $fqcn): void
    {
        $this->assertContainerBuilderHasService($fqcn, $fqcn);
        $this->assertContainerBuilderHasServiceDefinitionWithTag($fqcn, CheckerPass::TAG);
    }
}
