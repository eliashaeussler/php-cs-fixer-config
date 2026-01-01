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

namespace EliasHaeussler\PhpCsFixerConfig\Tests\Rules\Set;

use Composer\Autoload;
use EliasHaeussler\PhpCsFixerConfig as Src;
use PHPUnit\Framework;

use function array_diff_key;

/**
 * TYPO3RuleSetTest.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-3.0-or-later
 */
#[Framework\Attributes\CoversClass(Src\Rules\Set\TYPO3RuleSet::class)]
final class TYPO3RuleSetTest extends Framework\TestCase
{
    #[Framework\Attributes\Test]
    #[Framework\Attributes\RunInSeparateProcess]
    public function getReturnsCustomRulesWithoutOfficialRulesIfCodingStandardsPackageIsNotInstalled(): void
    {
        $classLoaders = Autoload\ClassLoader::getRegisteredLoaders();
        $registeredPsr4Prefixes = [];

        foreach ($classLoaders as $i => $classLoader) {
            // Back up currently registered PSR-4 prefixes for typo3/coding-standards package
            $registeredPsr4Prefixes[$i] = $classLoader->getPrefixesPsr4()['TYPO3\\CodingStandards\\'] ?? [];

            // Reset PSR-4 prefixes for typo3/coding-standards package
            $classLoader->setPsr4('TYPO3\\CodingStandards\\', []);
        }

        $subject = Src\Rules\Set\TYPO3RuleSet::create();

        $expected = [
            'no_superfluous_phpdoc_tags' => [
                'allow_mixed' => true,
            ],
            'trailing_comma_in_multiline' => [
                'elements' => [
                    'arguments',
                    'arrays',
                    'match',
                    'parameters',
                ],
            ],
        ];

        self::assertSame($expected, $subject->get());

        // Restore backed up PSR-4 prefixes for typo3/coding-standards package
        foreach ($classLoaders as $i => $classLoader) {
            $classLoader->setPsr4('TYPO3\\CodingStandards\\', $registeredPsr4Prefixes[$i]);
        }
    }

    #[Framework\Attributes\Test]
    public function getReturnsCustomRulesMergedWithOfficialRulesFromCodingStandardsPackage(): void
    {
        $subject = Src\Rules\Set\TYPO3RuleSet::create();

        $customRules = [
            'no_superfluous_phpdoc_tags' => [
                'allow_mixed' => true,
            ],
            'trailing_comma_in_multiline' => [
                'elements' => [
                    'arguments',
                    'arrays',
                    'match',
                    'parameters',
                ],
            ],
        ];

        self::assertNotSame([], array_diff_key($subject->get(), $customRules));
    }
}
