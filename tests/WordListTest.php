<?php declare(strict_types=1);

namespace KuchenName\Tests;

use KuchenName\WordList;
use PHPUnit\Framework\TestCase;
use Random\Engine\Mt19937;
use Random\Randomizer;

final class WordListTest extends TestCase
{
    private string $fixture = __DIR__ . '/fixtures/wordlist/animals.txt';

    public function test_loads_skipping_blanks_and_comments(): void
    {
        $list = WordList::fromFile($this->fixture);
        $this->assertSame(['ant', 'bear', 'cat', 'dolphin', 'elephant'], $list->all());
    }

    public function test_pick_is_deterministic_with_seeded_randomizer(): void
    {
        $list = WordList::fromFile($this->fixture);
        $rng  = new Randomizer(new Mt19937(42));

        $first  = $list->pick($rng);
        $second = $list->pick($rng);

        $this->assertContains($first,  ['ant', 'bear', 'cat', 'dolphin', 'elephant']);
        $this->assertContains($second, ['ant', 'bear', 'cat', 'dolphin', 'elephant']);
        $this->assertNotSame($first . $second, '');

        $rng2 = new Randomizer(new Mt19937(42));
        $this->assertSame($first,  $list->pick($rng2));
        $this->assertSame($second, $list->pick($rng2));
    }

    public function test_letters_filter_excludes_long_words(): void
    {
        $list = WordList::fromFile($this->fixture)->withMaxLetters(3);
        $this->assertSame(['ant', 'cat'], $list->all());
    }

    public function test_throws_when_file_missing(): void
    {
        $this->expectException(\RuntimeException::class);
        WordList::fromFile('/nonexistent/file.txt');
    }

    public function test_throws_when_picking_from_empty_list(): void
    {
        $this->expectException(\RuntimeException::class);
        WordList::fromFile($this->fixture)
            ->withMaxLetters(2)
            ->pick(new Randomizer(new Mt19937(1)));
    }
}
