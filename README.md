![Tests](https://github.com/leightonthomas/validation-bundle/workflows/Tests/badge.svg?branch=master)

Symfony bundle for [`leightonthomas/validation`](https://github.com/leightonthomas/validation).

# Installation

Make sure Composer is installed globally, as explained in the
[installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

## Applications that use Symfony Flex

Open a command console, enter your project directory and execute:

```console
$ composer require leightonthomas/validation-bundle
```

## Applications that don't use Symfony Flex

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require leightonthomas/validation-bundle
```

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    \LeightonThomas\ValidationBundle\ValidationBundle::class => ['all' => true],
];
```

# Adding custom rule checkers
Custom rule checkers can be added by tagging them with the `lt_validation.checker` tag (or the relevant PHP constant at `\LeightonThomas\ValidationBundle\DependencyInjection\Compiler\CheckerPass::TAG`).

## Example
```yaml
services:
  App\Checker\MyNewChecker:
    tags:
      - name: lt_validation.checker
```

or

```yaml
services:
  App\Checker\MyNewChecker:
    tags:
      - name: !php/const LeightonThomas\ValidationBundle\DependencyInjection\Compiler\CheckerPass::TAG
```
