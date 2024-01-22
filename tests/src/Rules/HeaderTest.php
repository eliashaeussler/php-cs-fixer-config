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

namespace EliasHaeussler\PhpCsFixerConfig\Tests\Rules;

use EliasHaeussler\PhpCsFixerConfig as Src;
use PHPUnit\Framework;

use function date;
use function sprintf;

/**
 * HeaderTest.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-3.0-or-later
 */
final class HeaderTest extends Framework\TestCase
{
    private Src\Rules\Header $subject;

    protected function setUp(): void
    {
        $this->subject = Src\Rules\Header::create(
            'eliashaeussler/php-cs-fixer-config',
            Src\Package\Type::ComposerPackage,
            Src\Package\Author::create('Elias Häußler', 'elias@haeussler.dev'),
            Src\Package\CopyrightRange::from(2023),
            Src\Package\License::GPL3OrLater,
        );
    }

    #[Framework\Attributes\Test]
    public function createAllowsArrayOfPackageAuthors(): void
    {
        $subject = Src\Rules\Header::create(
            'eliashaeussler/php-cs-fixer-config',
            Src\Package\Type::ComposerPackage,
            [
                Src\Package\Author::create('Elias Häußler', 'elias@haeussler.dev'),
                Src\Package\Author::create('John Doe', 'john.doe@example.com'),
                Src\Package\Author::create('Maggie Simpson', 'maggie@the-simpsons.com'),
            ],
            Src\Package\CopyrightRange::since(2023),
        );

        self::assertSame(
            <<<'HEADER'
This file is part of the Composer package "eliashaeussler/php-cs-fixer-config".

Copyright (C) since 2023 Elias Häußler <elias@haeussler.dev>,
                         John Doe <john.doe@example.com>,
                         Maggie Simpson <maggie@the-simpsons.com>
HEADER,
            $subject->toString(),
        );
    }

    #[Framework\Attributes\Test]
    public function getReturnsConfiguredRule(): void
    {
        self::assertSame(
            [
                'header_comment' => [
                    'header' => sprintf(<<<'HEADER'
This file is part of the Composer package "eliashaeussler/php-cs-fixer-config".

Copyright (C) 2023-%s Elias Häußler <elias@haeussler.dev>

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <https://www.gnu.org/licenses/>.
HEADER, date('Y')),
                    'comment_type' => 'comment',
                    'location' => 'after_declare_strict',
                    'separate' => 'both',
                ],
            ],
            $this->subject->get(),
        );
    }

    #[Framework\Attributes\Test]
    public function toStringReturnsHeaderAsString(): void
    {
        self::assertSame(
            sprintf(<<<'HEADER'
This file is part of the Composer package "eliashaeussler/php-cs-fixer-config".

Copyright (C) 2023-%s Elias Häußler <elias@haeussler.dev>

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <https://www.gnu.org/licenses/>.
HEADER, date('Y')),
            $this->subject->toString(),
        );
    }
}
