<?php

declare(strict_types=1);

/*
 * This file is part of the Composer package "eliashaeussler/php-cs-fixer-config".
 *
 * Copyright (C) 2024 Elias Häußler <elias@haeussler.dev>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

use EliasHaeussler\PhpCsFixerConfig\Config;
use EliasHaeussler\PhpCsFixerConfig\Package;
use EliasHaeussler\PhpCsFixerConfig\Rules;
use Symfony\Component\Finder;

$header = Rules\Header::create(
    'eliashaeussler/php-cs-fixer-config',
    Package\Type::ComposerPackage,
    Package\Author::create('Elias Häußler', 'elias@haeussler.dev'),
    Package\CopyrightRange::create(),
    Package\License::GPL3OrLater,
);

return Config::create()
    ->withRule($header)
    ->withFinder(static fn (Finder\Finder $finder) => $finder->in(__DIR__))
;
