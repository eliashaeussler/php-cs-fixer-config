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

namespace EliasHaeussler\PhpCsFixerConfig\Rules\Set;

use EliasHaeussler\PhpCsFixerConfig\Rules;
use TYPO3\CodingStandards;

use function class_exists;

/**
 * TYPO3RuleSet.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-3.0-or-later
 *
 * @phpstan-import-type TRulesArray from Rules\Rule
 */
final readonly class TYPO3RuleSet implements Rules\Rule
{
    /**
     * @var TRulesArray
     */
    private array $rules;

    public function __construct()
    {
        $rules = [];

        if (class_exists(CodingStandards\CsFixerConfig::class)) {
            $rules = CodingStandards\CsFixerConfig::create()->getRules();
        }

        $rules['no_superfluous_phpdoc_tags'] = [
            'allow_mixed' => true,
        ];
        $rules['trailing_comma_in_multiline'] = [
            'elements' => [
                'arguments',
                'arrays',
                'match',
                'parameters',
            ],
        ];

        $this->rules = $rules;
    }

    public static function create(): self
    {
        return new self();
    }

    public function get(): array
    {
        return $this->rules;
    }
}
