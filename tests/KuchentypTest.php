<?php declare(strict_types=1);

namespace KuchenName\Tests;

use KuchenName\Gender;
use KuchenName\Kuchentyp;
use PHPUnit\Framework\TestCase;

final class KuchentypTest extends TestCase
{
    public function test_parses_masculine_line(): void
    {
        $k = Kuchentyp::fromLine('Kuchen|m');
        $this->assertSame('Kuchen', $k->noun);
        $this->assertSame(Gender::Masculine, $k->gender);
    }

    public function test_parses_feminine_line(): void
    {
        $k = Kuchentyp::fromLine('Torte|f');
        $this->assertSame('Torte', $k->noun);
        $this->assertSame(Gender::Feminine, $k->gender);
    }

    public function test_parses_neuter_line(): void
    {
        $k = Kuchentyp::fromLine('Brötchen|n');
        $this->assertSame('Brötchen', $k->noun);
        $this->assertSame(Gender::Neuter, $k->gender);
    }

    public function test_rejects_missing_separator(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Kuchentyp::fromLine('Kuchen');
    }

    public function test_rejects_unknown_gender(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Kuchentyp::fromLine('Kuchen|x');
    }
}
