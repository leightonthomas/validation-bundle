<?php

declare(strict_types=1);

namespace Tests\LeightonThomas\ValidationBundle\Integration;

use LeightonThomas\Validation\Checker\AnythingChecker;
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
use LeightonThomas\Validation\Exception\NoCheckersRegistered;
use LeightonThomas\Validation\Rule\Anything;
use LeightonThomas\Validation\Rule\Arrays\IsArray;
use LeightonThomas\Validation\Rule\Arrays\IsDefinedArray;
use LeightonThomas\Validation\Rule\Callback;
use LeightonThomas\Validation\Rule\Combination\Compose;
use LeightonThomas\Validation\Rule\Combination\Intersection;
use LeightonThomas\Validation\Rule\Combination\Union;
use LeightonThomas\Validation\Rule\Object\IsInstanceOf;
use LeightonThomas\Validation\Rule\Rule;
use LeightonThomas\Validation\Rule\Scalar\Integer\IsInteger;
use LeightonThomas\Validation\Rule\Scalar\Numeric\IsGreaterThan;
use LeightonThomas\Validation\Rule\Scalar\Numeric\IsLessThan;
use LeightonThomas\Validation\Rule\Scalar\Strings\IsString;
use LeightonThomas\Validation\Rule\Scalar\Strings\Length;
use LeightonThomas\Validation\Rule\Scalar\Strings\Regex;
use LeightonThomas\Validation\Rule\StrictEquals;
use LeightonThomas\Validation\ValidatorFactory;
use LeightonThomas\ValidationBundle\ValidationBundle;
use Nyholm\BundleTest\BaseBundleTestCase;
use Nyholm\BundleTest\CompilerPass\PublicServicePass;
use stdClass;

class ValidationBundleTest extends BaseBundleTestCase
{

    public function setUp(): void
    {
        parent::setUp();

        $this->addCompilerPass(new PublicServicePass('/^LeightonThomas\\\\Validation/'));
    }

    /**
     * @test
     * @dataProvider checkerProvider
     *
     * @param string $checkerFqcn
     * @param Rule $rule
     * @param mixed $validValue
     *
     * @psalm-param class-string<Checker> $checkerFqcn
     * @psalm-param Rule<mixed, mixed> $rule
     * @psalm-param mixed $validValue
     *
     * @throws NoCheckersRegistered
     */
    public function theBundleWillInitialiseCorrectlyWithAllExpectedServices(
        string $checkerFqcn,
        Rule $rule,
        $validValue
    ): void {
        $this->bootKernel();

        $container = $this->getContainer();

        self::assertTrue($container->has(ValidatorFactory::class));

        self::assertTrue($container->has($checkerFqcn));

        /** @var ValidatorFactory $factory */
        $factory = $container->get(ValidatorFactory::class);
        $factory->create($rule)->validate($validValue);
    }

    public function checkerProvider(): array
    {
        return [
            [
                IsDefinedArrayChecker::class,
                IsDefinedArray::of('a', new IsInteger()),
                ['a' => 4],
            ],
            [
                ComposeChecker::class,
                Compose::from(new IsString())->and(new Length(1)),
                'hi',
            ],
            [
                IntersectionChecker::class,
                Intersection::of(new IsArray()),
                [],
            ],
            [
                UnionChecker::class,
                Union::of(new IsArray()),
                [],
            ],
            [
                IsInstanceOfChecker::class,
                new IsInstanceOf(stdClass::class),
                new stdClass(),
            ],
            [
                IsGreaterThanChecker::class,
                new IsGreaterThan(4),
                5,
            ],
            [
                IsLessThanChecker::class,
                new IsLessThan(5),
                4,
            ],
            [
                LengthChecker::class,
                new Length(1),
                'hi',
            ],
            [
                RegexChecker::class,
                new Regex('/^hello$/'),
                'hello',
            ],
            [
                IsScalarChecker::class,
                new IsString(),
                'hi',
            ],
            [
                AnythingChecker::class,
                new Anything(),
                'hi',
            ],
            [
                CallbackChecker::class,
                new Callback(
                    function() {},
                ),
                'hi',
            ],
            [
                StrictEqualsChecker::class,
                new StrictEquals(4),
                4,
            ],
        ];
    }

    protected function getBundleClass(): string
    {
        return ValidationBundle::class;
    }
}
