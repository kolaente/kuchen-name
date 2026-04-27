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

    public function test_irregular_returns_pre_inflected_masculine(): void
    {
        $this->assertSame(
            'dunkler',
            Inflector::adjective('dunkel|dunkler|dunkle|dunkles', Gender::Masculine),
        );
    }

    public function test_irregular_returns_pre_inflected_feminine(): void
    {
        $this->assertSame(
            'dunkle',
            Inflector::adjective('dunkel|dunkler|dunkle|dunkles', Gender::Feminine),
        );
    }

    public function test_irregular_returns_pre_inflected_neuter(): void
    {
        $this->assertSame(
            'dunkles',
            Inflector::adjective('dunkel|dunkler|dunkle|dunkles', Gender::Neuter),
        );
    }

    public function test_irregular_rejects_wrong_field_count(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Inflector::adjective('dunkel|dunkler|dunkle', Gender::Masculine);
    }
}
