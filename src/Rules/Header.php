<?php

declare(strict_types=1);

/*
 * This file is part of the Composer package "eliashaeussler/php-cs-fixer-config".
 *
 * Copyright (C) 2023-2024 Elias Häußler <elias@haeussler.dev>
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

use function count;
use function implode;
use function is_array;
use function sprintf;
use function str_repeat;
use function strlen;
use function trim;

/**
 * Header.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-3.0-or-later
 */
final class Header implements Rule
{
    /**
     * @param list<Package\Author> $packageAuthors
     */
    private function __construct(
        public readonly string $packageName,
        public readonly Package\Type $packageType,
        public readonly array $packageAuthors,
        public readonly Package\CopyrightRange $copyrightRange,
        public readonly Package\License $license,
    ) {}

    /**
     * @param Package\Author|list<Package\Author> $packageAuthors
     */
    public static function create(
        string $packageName,
        Package\Type $packageType,
        Package\Author|array $packageAuthors = [],
        ?Package\CopyrightRange $copyrightRange = null,
        Package\License $license = Package\License::Proprietary,
    ): self {
        if (!is_array($packageAuthors)) {
            $packageAuthors = [$packageAuthors];
        }

        return new self(
            $packageName,
            $packageType,
            $packageAuthors,
            $copyrightRange ?? Package\CopyrightRange::create(),
            $license,
        );
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
        return trim(<<<HEADER
This file is part of the {$this->packageType->value} "{$this->packageName}".

{$this->generateCopyrightLines()}{$this->license->licenseText()}
HEADER);
    }

    private function generateCopyrightLines(): string
    {
        if ([] === $this->packageAuthors) {
            return '';
        }

        $numberOfPackageAuthors = count($this->packageAuthors);
        $copyright = sprintf('Copyright (C) %s', $this->copyrightRange);
        $lines = [];

        for ($i = 0; $i < $numberOfPackageAuthors; ++$i) {
            $author = $this->packageAuthors[$i];
            $authorLine = sprintf('%s <%s>', $author->name, $author->emailAddress);

            if (0 === $i) {
                $author = sprintf('%s %s', $copyright, $authorLine);
            } else {
                $author = sprintf('%s %s', str_repeat(' ', strlen($copyright)), $authorLine);
            }

            if ($i < ($numberOfPackageAuthors - 1)) {
                $author .= ',';
            }

            $lines[] = $author;
        }

        // Add empty lines to separate copyright and license text
        $lines[] = '';
        $lines[] = '';

        return implode(PHP_EOL, $lines);
    }
}
