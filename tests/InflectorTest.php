<?php declare(strict_types=1);

namespace KuchenName\Tests;

use KuchenName\Gender;
use KuchenName\Inflector;
use PHPUnit\Framework\TestCase;

final class InflectorTest extends TestCase
{
    public function test_masculine_gets_er_ending(): void
    {
        $this->assertSame('saftiger', Inflector::adjective('saftig', Gender::Masculine));
    }

    public function test_feminine_gets_e_ending(): void
    {
        $this->assertSame('saftige', Inflector::adjective('saftig', Gender::Feminine));
    }

    public function test_neuter_gets_es_ending(): void
    {
        $this->assertSame('saftiges', Inflector::adjective('saftig', Gender::Neuter));
    }

    public function test_handles_umlaut_stem(): void
    {
        $this->assertSame('süße', Inflector::adjective('süß', Gender::Feminine));
    }
}
