<div align="center">

# PHP-CS-Fixer config

[![Coverage](https://codecov.io/gh/eliashaeussler/php-cs-fixer-config/branch/main/graph/badge.svg?token=QvLNVgBu6z)](https://codecov.io/gh/eliashaeussler/php-cs-fixer-config)
[![Maintainability](https://api.codeclimate.com/v1/badges/1a17977a507b73e45e03/maintainability)](https://codeclimate.com/github/eliashaeussler/php-cs-fixer-config/maintainability)
[![Tests](https://github.com/eliashaeussler/php-cs-fixer-config/actions/workflows/tests.yaml/badge.svg)](https://github.com/eliashaeussler/php-cs-fixer-config/actions/workflows/tests.yaml)
[![CGL](https://github.com/eliashaeussler/php-cs-fixer-config/actions/workflows/cgl.yaml/badge.svg)](https://github.com/eliashaeussler/php-cs-fixer-config/actions/workflows/cgl.yaml)
[![Release](https://github.com/eliashaeussler/php-cs-fixer-config/actions/workflows/release.yaml/badge.svg)](https://github.com/eliashaeussler/php-cs-fixer-config/actions/workflows/release.yaml)
[![Latest Stable Version](http://poser.pugx.org/eliashaeussler/php-cs-fixer-config/v)](https://packagist.org/packages/eliashaeussler/php-cs-fixer-config)
[![PHP Version Require](http://poser.pugx.org/eliashaeussler/php-cs-fixer-config/require/php)](https://packagist.org/packages/eliashaeussler/php-cs-fixer-config)
[![License](http://poser.pugx.org/eliashaeussler/php-cs-fixer-config/license)](LICENSE)

</div>

This package contains basic [PHP-CS-Fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer)
config for use in my personal projects. It is not meant to be used anywhere else.
I won't provide support and don't accept pull requests for this repo.

## 🔥 Installation

```bash
composer require eliashaeussler/php-cs-fixer-config
```

## ⚡ Usage

```php
# .php-cs-fixer.php

use EliasHaeussler\PhpCsFixerConfig;
use Symfony\Component\Finder;

$header = PhpCsFixerConfig\Rules\Header::create(
    'eliashaeussler/package-name',
    PhpCsFixerConfig\Package\Type::ComposerPackage,
    PhpCsFixerConfig\Package\Author::create('Elias Häußler', 'elias@haeussler.dev'),
    PhpCsFixerConfig\Package\License::GPL3OrLater,
);

return PhpCsFixerConfig\Config::create()
    ->withHeader($header)
    ->withFinder(static fn (Finder\Finder $finder) => $finder->in(__DIR__))
;
```

## ⭐ License

This project is licensed under [GNU General Public License 3.0 (or later)](LICENSE).
