<?php declare(strict_types=1);

namespace KuchenName\Tests;

use KuchenName\Generator;
use PHPUnit\Framework\TestCase;
use Random\Engine\Mt19937;
use Random\Randomizer;

final class GeneratorTest extends TestCase
{
    public function test_one_word_returns_lowercased_kuchentyp(): void
    {
        $gen    = Generator::default(new Randomizer(new Mt19937(7)));
        $result = $gen->generate(words: 1);

        $this->assertDoesNotMatchRegularExpression('/[-_ ]/', $result);
        $this->assertSame(mb_strtolower($result), $result, 'output must be lowercase');
        $this->assertNotSame('', $result);
    }
}
