<?php declare(strict_types=1);

namespace KuchenName\Tests;

use KuchenName\Gender;
use PHPUnit\Framework\TestCase;

final class GenderTest extends TestCase
{
    public function test_has_three_cases(): void
    {
        $cases = array_map(fn(Gender $g) => $g->value, Gender::cases());
        sort($cases);
        $this->assertSame(['f', 'm', 'n'], $cases);
    }
}
