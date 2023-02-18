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

namespace EliasHaeussler\PhpCsFixerConfig\Tests;

use EliasHaeussler\PhpCsFixerConfig as Src;
use PHPUnit\Framework;
use Symfony\Component\Finder;

use function array_intersect_assoc;

/**
 * ConfigTest.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-3.0-or-later
 */
final class ConfigTest extends Framework\TestCase
{
    private Src\Config $subject;

    protected function setUp(): void
    {
        $this->subject = Src\Config::create();
    }

    #[Framework\Attributes\Test]
    public function createReturnsConfigWithDefaultRules(): void
    {
        $actual = Src\Config::create();

        self::assertSame(
            [
                '@PSR2' => true,
                '@Symfony' => true,
                'global_namespace_import' => [
                    'import_classes' => true,
                    'import_functions' => true,
                ],
                'no_superfluous_phpdoc_tags' => [
                    'allow_mixed' => true,
                ],
                'ordered_imports' => [
                    'imports_order' => [
                        'const',
                        'class',
                        'function',
                    ],
                ],
                'trailing_comma_in_multiline' => [
                    'elements' => [
                        'arguments',
                        'arrays',
                        'match',
                        'parameters',
                    ],
                ],
            ],
            $actual->getRules(),
        );
        self::assertTrue($actual->getRiskyAllowed());
    }

    #[Framework\Attributes\Test]
    public function withHeaderAddsHeaderRule(): void
    {
        $header = Src\Rules\Header::create(
            'foo/baz',
            Src\Package\Type::ComposerPackage,
            Src\Package\Author::create('foo', 'foo@baz.de'),
        );

        $this->subject->withHeader($header);

        self::assertRulesAreConfigured($header->get());
    }

    #[Framework\Attributes\Test]
    public function withFinderCanPassCustomFinderInstance(): void
    {
        $finder = Finder\Finder::create();

        $this->subject->withFinder($finder);

        self::assertSame($finder, $this->subject->getFinder());
    }

    #[Framework\Attributes\Test]
    public function withFinderCanPassCallableThatConfiguresCustomFinderInstance(): void
    {
        $finder = Finder\Finder::create();

        $this->subject->withFinder(static fn () => $finder);

        self::assertSame($finder, $this->subject->getFinder());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function withRulesAddsGivenRules(): void
    {
        $this->subject->withRules(['foo' => true]);

        self::assertRulesAreConfigured(['foo' => true]);
    }

    /**
     * @param array<string, array<string, mixed>|bool> $rules
     */
    protected function assertRulesAreConfigured(array $rules): void
    {
        self::assertSame($rules, array_intersect_assoc($this->subject->getRules(), $rules));
    }
}
