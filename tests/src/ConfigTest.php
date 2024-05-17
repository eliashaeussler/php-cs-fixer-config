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

namespace EliasHaeussler\PhpCsFixerConfig\Tests;

use EliasHaeussler\PhpCsFixerConfig as Src;
use Generator;
use PhpCsFixer\Config;
use PhpCsFixer\Runner;
use PHPUnit\Framework;
use Symfony\Component\Finder;

use function array_intersect_assoc;
use function class_exists;

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
            Src\Rules\Set\DefaultRuleSet::create()->get(),
            $actual->getRules(),
        );
        self::assertTrue($actual->getRiskyAllowed());
    }

    #[Framework\Attributes\Test]
    public function createReturnsConfigWithParallelExecution(): void
    {
        // Skip test if class is not available (e.g. when testing with lowest supported version)
        if (!class_exists(Runner\Parallel\ParallelConfig::class)) {
            self::markTestSkipped('Parallel execution is not available.');
        }

        $actual = Src\Config::create();

        self::assertEquals(
            Runner\Parallel\ParallelConfigFactory::detect(),
            $actual->getParallelConfig(),
        );
    }

    #[Framework\Attributes\Test]
    #[Framework\Attributes\DataProvider('withRulesAddsRuleDataProvider')]
    public function withRulesAddsRule(Src\Rules\Rule $rule): void
    {
        $this->subject->withRule($rule);

        self::assertRulesAreConfigured($rule->get());
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

    #[Framework\Attributes\Test]
    public function withConfigMergesGivenConfig(): void
    {
        $finder = new Finder\Finder();
        $config = (new Config())
            ->setRules(['foo' => true])
            ->setFinder($finder)
            ->setRiskyAllowed(false)
        ;

        $this->subject->withConfig($config);

        self::assertSame(['foo' => true], $this->subject->getRules());
        self::assertSame($finder, $this->subject->getFinder());
        self::assertFalse($this->subject->getRiskyAllowed());
    }

    /**
     * @return Generator<string, array{Src\Rules\Rule}>
     */
    public static function withRulesAddsRuleDataProvider(): Generator
    {
        $header = Src\Rules\Header::create(
            'foo/baz',
            Src\Package\Type::ComposerPackage,
            Src\Package\Author::create('foo', 'foo@baz.de'),
        );

        $ruleSet = Src\Rules\RuleSet::fromArray(['foo' => true]);

        yield 'header' => [$header];
        yield 'rule set' => [$ruleSet];
    }

    /**
     * @param array<string, array<string, mixed>|bool> $rules
     */
    private function assertRulesAreConfigured(array $rules): void
    {
        self::assertSame($rules, array_intersect_assoc($this->subject->getRules(), $rules));
    }
}
