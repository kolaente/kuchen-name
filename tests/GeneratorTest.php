<?php declare(strict_types=1);

namespace KuchenName\Tests;

use KuchenName\Gender;
use KuchenName\Generator;
use KuchenName\Kuchentyp;
use KuchenName\WordList;
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

    public function test_two_words_inflects_adjective_for_masculine(): void
    {
        $gen = $this->generatorWithFixedPicks(
            adjektiv: 'saftig',
            sorten:   [],
            kuchentyp: new Kuchentyp('Kuchen', Gender::Masculine),
        );
        $this->assertSame('saftiger-kuchen', $gen->generate(words: 2));
    }

    public function test_two_words_inflects_adjective_for_feminine(): void
    {
        $gen = $this->generatorWithFixedPicks(
            adjektiv: 'saftig',
            sorten:   [],
            kuchentyp: new Kuchentyp('Torte', Gender::Feminine),
        );
        $this->assertSame('saftige-torte', $gen->generate(words: 2));
    }

    public function test_three_words_includes_a_sorte_between_adj_and_kuchentyp(): void
    {
        $gen = $this->generatorWithFixedPicks(
            adjektiv: 'saftig',
            sorten:   ['kirsch'],
            kuchentyp: new Kuchentyp('Torte', Gender::Feminine),
        );
        $this->assertSame('saftige-kirsch-torte', $gen->generate(words: 3));
    }

    public function test_five_words_includes_three_distinct_sorten(): void
    {
        $gen = $this->generatorWithFixedPicks(
            adjektiv: 'saftig',
            sorten:   ['schoko', 'kirsch', 'mohn'],
            kuchentyp: new Kuchentyp('Kuchen', Gender::Masculine),
        );
        $out = $gen->generate(words: 5);

        $this->assertStringStartsWith('saftiger-', $out);
        $this->assertStringEndsWith('-kuchen', $out);
        foreach (['schoko', 'kirsch', 'mohn'] as $s) {
            $this->assertSame(1, substr_count($out, $s), "sorte '$s' must appear once in '$out'");
        }
    }

    public function test_custom_separator(): void
    {
        $gen = $this->generatorWithFixedPicks(
            adjektiv: 'süß',
            sorten:   ['apfel'],
            kuchentyp: new Kuchentyp('Brötchen', Gender::Neuter),
        );
        $this->assertSame('süßes_apfel_brötchen', $gen->generate(words: 3, separator: '_'));
    }

    public function test_words_below_one_throws(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Generator::default()->generate(words: 0);
    }

    /**
     * @param list<string> $sorten
     */
    private function generatorWithFixedPicks(
        string $adjektiv,
        array $sorten,
        Kuchentyp $kuchentyp,
    ): Generator {
        $tmp = sys_get_temp_dir() . '/kuchenname-' . uniqid();
        mkdir($tmp);
        file_put_contents("$tmp/adj.txt",     $adjektiv . "\n");
        file_put_contents("$tmp/sorten.txt",  implode("\n", $sorten ?: ['x']) . "\n");
        file_put_contents("$tmp/kuchen.txt",  $kuchentyp->noun . '|' . $kuchentyp->gender->value . "\n");

        $adj    = WordList::fromFile("$tmp/adj.txt");
        $sortL  = WordList::fromFile("$tmp/sorten.txt");
        $kuchen = [Kuchentyp::fromLine(file_get_contents("$tmp/kuchen.txt"))];

        return new Generator($adj, $sortL, $kuchen, new Randomizer(new Mt19937(0)));
    }
}
