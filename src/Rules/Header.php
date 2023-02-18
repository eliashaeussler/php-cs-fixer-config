<?php

declare(strict_types=1);

/*
 * This file is part of the Composer package "eliashaeussler/php-cs-fixer-config".
 *
 * Copyright (C) 2023 Elias Häußler <elias@haeussler.dev>
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

namespace EliasHaeussler\PhpCsFixerConfig\Rules;

use EliasHaeussler\PhpCsFixerConfig\Package;

use function date;
use function trim;

/**
 * Header.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-3.0-or-later
 */
final class Header implements Rule
{
    private function __construct(
        public readonly string $packageName,
        public readonly Package\Type $packageType,
        public readonly Package\Author $packageAuthor,
        public readonly Package\License $license,
    ) {
    }

    public static function create(
        string $packageName,
        Package\Type $packageType,
        Package\Author $packageAuthor,
        Package\License $license = Package\License::Proprietary,
    ): self {
        return new self($packageName, $packageType, $packageAuthor, $license);
    }

    /**
     * @return array{
     *     header_comment: array{
     *         header: string,
     *         comment_type: string,
     *         location: string,
     *         separate: string
     *     }
     * }
     */
    public function get(): array
    {
        return [
            'header_comment' => [
                'header' => $this->toString(),
                'comment_type' => 'comment',
                'location' => 'after_declare_strict',
                'separate' => 'both',
            ],
        ];
    }

    public function toString(): string
    {
        $year = date('Y');

        return trim(<<<HEADER
This file is part of the {$this->packageType->value} "{$this->packageName}".

Copyright (C) {$year} {$this->packageAuthor->name} <{$this->packageAuthor->emailAddress}>

{$this->license->licenseText()}
HEADER);
    }
}
