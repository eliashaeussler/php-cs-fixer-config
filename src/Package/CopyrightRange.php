<?php

declare(strict_types=1);

/*
 * This file is part of the Composer package "eliashaeussler/php-cs-fixer-config".
 *
 * Copyright (C) 2023-2025 Elias Häußler <elias@haeussler.dev>
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

namespace EliasHaeussler\PhpCsFixerConfig\Package;

use Stringable;

use function date;
use function sprintf;

/**
 * CopyrightRange.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-3.0-or-later
 */
final class CopyrightRange implements Stringable
{
    private function __construct(
        public readonly ?int $from,
        public readonly ?int $to,
    ) {}

    public static function create(?int $year = null): self
    {
        return new self(null, $year ?? self::getCurrentYear());
    }

    public static function from(int $year, ?int $to = null): self
    {
        return new self($year, $to ?? self::getCurrentYear());
    }

    public static function since(int $year): self
    {
        return new self($year, null);
    }

    public function __toString(): string
    {
        if ($this->from === $this->to) {
            return (string) $this->to;
        }

        if (null !== $this->from && null !== $this->to) {
            return sprintf('%d-%d', $this->from, $this->to);
        }

        if (null !== $this->from) {
            return sprintf('since %d', $this->from);
        }

        return (string) $this->to;
    }

    private static function getCurrentYear(): int
    {
        return (int) date('Y');
    }
}
