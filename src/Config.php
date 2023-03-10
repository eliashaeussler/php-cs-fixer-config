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

namespace EliasHaeussler\PhpCsFixerConfig;

use PhpCsFixer\ConfigInterface;
use Symfony\Component\Finder;

use function array_replace_recursive;
use function is_callable;

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
    /**
     * @phpstan-var TRulesArray
     */
    private static array $defaultRules = [
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
    ];

    public static function create(): self
    {
        $config = new self();
        $config->setRules(self::$defaultRules);
        $config->setRiskyAllowed(true);
        $config->getFinder()->ignoreDotFiles(false);
        $config->getFinder()->ignoreVCSIgnored(true);

        return $config;
    }

    public function withRule(Rules\Rule $rule): self
    {
        $mergedRuleSet = array_replace_recursive($this->getRules(), $rule->get());

        $this->setRules($mergedRuleSet);

        return $this;
    }

    /**
     * @param Finder\Finder|callable(Finder\Finder): Finder\Finder $finder
     */
    public function withFinder(Finder\Finder|callable $finder): self
    {
        if (is_callable($finder)) {
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
