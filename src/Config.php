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

namespace EliasHaeussler\PhpCsFixerConfig;

use PhpCsFixer\ConfigInterface;
use PhpCsFixer\Runner;
use Symfony\Component\Finder;

use function array_replace_recursive;
use function class_exists;

/**
 * Config.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-3.0-or-later
 *
 * @phpstan-import-type TRulesArray from Rules\Rule
 */
final class Config extends \PhpCsFixer\Config
{
    public static function create(): self
    {
        $config = new self();
        $config->withRule(Rules\Set\DefaultRuleSet::create(), false);
        $config->setRiskyAllowed(true);
        $config->getFinder()->ignoreDotFiles(false);
        $config->getFinder()->ignoreVCSIgnored(true);

        // Enable parallel execution (PHP-CS-Fixer >= 3.57)
        if (class_exists(Runner\Parallel\ParallelConfig::class)) {
            $config->setParallelConfig(Runner\Parallel\ParallelConfigFactory::detect());
        }

        return $config;
    }

    public function withRule(Rules\Rule $rule, bool $merge = true): self
    {
        if ($merge) {
            /** @var TRulesArray $rules */
            $rules = array_replace_recursive($this->getRules(), $rule->get());
        } else {
            $rules = $rule->get();
        }

        $this->setRules($rules);

        return $this;
    }

    /**
     * @param Finder\Finder|callable(Finder\Finder): Finder\Finder $finder
     */
    public function withFinder(Finder\Finder|callable $finder): self
    {
        if (!($finder instanceof Finder\Finder)) {
            $finder = $finder($this->getFinder());
        }

        $this->setFinder($finder);

        return $this;
    }

    public function withConfig(ConfigInterface $config): self
    {
        $this->setRules($config->getRules());
        $this->setRiskyAllowed($config->getRiskyAllowed());
        $this->setFinder($config->getFinder());

        return $this;
    }
}
