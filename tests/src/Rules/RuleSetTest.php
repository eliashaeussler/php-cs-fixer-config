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

namespace EliasHaeussler\PhpCsFixerConfig\Tests\Rules;

use EliasHaeussler\PhpCsFixerConfig as Src;
use PHPUnit\Framework;

/**
 * RuleSetTest.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-3.0-or-later
 */
final class RuleSetTest extends Framework\TestCase
{
    private Src\Rules\RuleSet $subject;

    protected function setUp(): void
    {
        $this->subject = Src\Rules\RuleSet::fromArray([
            'foo' => true,
            'baz' => [
                'hello' => 'world',
            ],
        ]);
    }

    #[Framework\Attributes\Test]
    public function createReturnsRuleSetWithEmptyArray(): void
    {
        $actual = Src\Rules\RuleSet::create();

        self::assertSame([], $actual->get());
    }

    #[Framework\Attributes\Test]
    public function fromArrayReturnsRuleSetWithGivenRules(): void
    {
        $actual = Src\Rules\RuleSet::fromArray(['foo' => true]);

        self::assertSame(['foo' => true], $actual->get());
    }

    #[Framework\Attributes\Test]
    public function addMergesGivenRulesWithConfiguredRules(): void
    {
        $this->subject->add([
            'foo' => false,
            'baz' => [
                'dummy' => false,
                ],
            ],
        );

        self::assertSame(
            [
                'foo' => false,
                'baz' => [
                    'hello' => 'world',
                    'dummy' => false,
                ],
            ],
            $this->subject->get(),
        );
    }

    #[Framework\Attributes\Test]
    public function removeRemovesGivenRules(): void
    {
        $this->subject->remove('baz', 'dummy');

        self::assertSame(['foo' => true], $this->subject->get());
    }

    #[Framework\Attributes\Test]
    public function getReturnsConfiguredRuleSet(): void
    {
        self::assertSame(
            [
                'foo' => true,
                'baz' => [
                    'hello' => 'world',
                ],
            ],
            $this->subject->get(),
        );
    }
}
