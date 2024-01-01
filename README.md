<div align="center">

# PHP-CS-Fixer config

[![Coverage](https://img.shields.io/codecov/c/github/eliashaeussler/php-cs-fixer-config?logo=codecov&token=QvLNVgBu6z)](https://codecov.io/gh/eliashaeussler/php-cs-fixer-config)
[![Maintainability](https://img.shields.io/codeclimate/maintainability/eliashaeussler/php-cs-fixer-config?logo=codeclimate)](https://codeclimate.com/github/eliashaeussler/php-cs-fixer-config/maintainability)
[![CGL](https://img.shields.io/github/actions/workflow/status/eliashaeussler/php-cs-fixer-config/cgl.yaml?label=cgl&logo=github)](https://github.com/eliashaeussler/php-cs-fixer-config/actions/workflows/cgl.yaml)
[![Tests](https://img.shields.io/github/actions/workflow/status/eliashaeussler/php-cs-fixer-config/tests.yaml?label=tests&logo=github)](https://github.com/eliashaeussler/php-cs-fixer-config/actions/workflows/tests.yaml)
[![Supported PHP Versions](https://img.shields.io/packagist/dependency-v/eliashaeussler/php-cs-fixer-config/php?logo=php)](https://packagist.org/packages/eliashaeussler/php-cs-fixer-config)

</div>

This package contains basic [PHP-CS-Fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer)
config for use in my personal projects. It is not meant to be used anywhere else.
I won't provide support and don't accept pull requests for this repo.

## 🔥 Installation

[![Packagist](https://img.shields.io/packagist/v/eliashaeussler/php-cs-fixer-config?label=version&logo=packagist)](https://packagist.org/packages/eliashaeussler/php-cs-fixer-config)
[![Packagist Downloads](https://img.shields.io/packagist/dt/eliashaeussler/php-cs-fixer-config?color=brightgreen)](https://packagist.org/packages/eliashaeussler/php-cs-fixer-config)

```bash
composer require eliashaeussler/php-cs-fixer-config
```

## ⚡ Usage

Configure PHP-CS-Fixer in your `.php-cs-fixer.php` file:

```php
use EliasHaeussler\PhpCsFixerConfig;
use Symfony\Component\Finder;

// Create header rule
$header = PhpCsFixerConfig\Rules\Header::create(
    'eliashaeussler/package-name',
    PhpCsFixerConfig\Package\Type::ComposerPackage,
    PhpCsFixerConfig\Package\Author::create('Elias Häußler', 'elias@haeussler.dev'),
    PhpCsFixerConfig\Package\CopyrightRange::from(2021),
    PhpCsFixerConfig\Package\License::GPL3OrLater,
);

// Create custom rule set
$ruleSet = PhpCsFixerConfig\Rules\RuleSet::fromArray([
    'modernize_types_casting' => true,
    'php_unit_test_case_static_method_calls' => [
        'call_type' => 'self',
    ],
]);

return PhpCsFixerConfig\Config::create()
    ->withRule($header)
    ->withRule($ruleSet)
    // You can also overwrite all rules
    ->withRule($ruleSet, false)
    ->withFinder(static fn (Finder\Finder $finder) => $finder->in(__DIR__))
    // You can also inject your own Finder instance
    ->withFinder($finder)
;
```

## ⭐ License

This project is licensed under [GNU General Public License 3.0 (or later)](LICENSE).
