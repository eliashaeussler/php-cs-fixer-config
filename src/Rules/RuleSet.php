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

use function array_diff_key;
use function array_flip;
use function array_replace_recursive;

/**
 * RuleSet.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-3.0-or-later
 *
 * @phpstan-import-type TRulesArray from Rule
 */
final class RuleSet implements Rule
{
    /**
     * @phpstan-param TRulesArray $rules
     */
    public function __construct(
        private array $rules,
    ) {}

    public static function create(): self
    {
        return new self([]);
    }

    /**
     * @phpstan-param TRulesArray $rules
     */
    public static function fromArray(array $rules): self
    {
        return new self($rules);
    }

    /**
     * @phpstan-param TRulesArray $rules
     */
    public function add(array $rules): self
    {
        /** @var TRulesArray $mergedRules */
        $mergedRules = array_replace_recursive($this->rules, $rules);

        $this->rules = $mergedRules;

        return $this;
    }

    public function remove(string ...$rules): self
    {
        $this->rules = array_diff_key($this->rules, array_flip($rules));

        return $this;
    }

    public function get(): array
    {
        return $this->rules;
    }
}
