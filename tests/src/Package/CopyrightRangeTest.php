<?php

declare(strict_types=1);

/*
 * This file is part of the Composer package "eliashaeussler/php-cs-fixer-config".
 *
 * Copyright (C) 2023-2026 Elias Häußler <elias@haeussler.dev>
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

namespace EliasHaeussler\PhpCsFixerConfig\Tests\Package;

use EliasHaeussler\PhpCsFixerConfig as Src;
use PHPUnit\Framework;

use function date;

/**
 * CopyrightRangeTest.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-3.0-or-later
 */
#[Framework\Attributes\CoversClass(Src\Package\CopyrightRange::class)]
final class CopyrightRangeTest extends Framework\TestCase
{
    #[Framework\Attributes\Test]
    public function createUsesOnlyCurrentYear(): void
    {
        $currentYear = self::getCurrentYear();
        $subject = Src\Package\CopyrightRange::create();

        self::assertNull($subject->from);
        self::assertSame($currentYear, $subject->to);
        self::assertSame((string) $currentYear, (string) $subject);
    }

    #[Framework\Attributes\Test]
    public function createUsesGivenYear(): void
    {
        $subject = Src\Package\CopyrightRange::create(2021);

        self::assertNull($subject->from);
        self::assertSame(2021, $subject->to);
        self::assertSame('2021', (string) $subject);
    }

    #[Framework\Attributes\Test]
    public function fromUsesGivenStartAndCurrentYear(): void
    {
        $currentYear = self::getCurrentYear();
        $subject = Src\Package\CopyrightRange::from(2021);

        self::assertSame(2021, $subject->from);
        self::assertSame($currentYear, $subject->to);
        self::assertSame('2021-'.$currentYear, (string) $subject);
    }

    #[Framework\Attributes\Test]
    public function fromUsesGivenStartAndGivenEnd(): void
    {
        $subject = Src\Package\CopyrightRange::from(2021, 2023);

        self::assertSame(2021, $subject->from);
        self::assertSame(2023, $subject->to);
        self::assertSame('2021-2023', (string) $subject);
    }

    #[Framework\Attributes\Test]
    public function sinceUsesGivenYear(): void
    {
        $subject = Src\Package\CopyrightRange::since(2021);

        self::assertSame(2021, $subject->from);
        self::assertNull($subject->to);
        self::assertSame('since 2021', (string) $subject);
    }

    #[Framework\Attributes\Test]
    public function stringRepresentationDoesNotShowRangeOnIdenticalStartAndEndYear(): void
    {
        $subject = Src\Package\CopyrightRange::from(2024, 2024);

        self::assertSame('2024', (string) $subject);
    }

    private static function getCurrentYear(): int
    {
        return (int) date('Y');
    }
}
