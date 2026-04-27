<?php declare(strict_types=1);

namespace KuchenName;

use Random\Randomizer;
use Random\Engine\Mt19937;

final class Generator
{
    /**
     * @param list<Kuchentyp> $kuchentypen
     */
    public function __construct(
        private readonly WordList $adjektive,
        private readonly WordList $sorten,
        private readonly array $kuchentypen,
        private readonly Randomizer $rng,
    ) {}

    public static function default(?Randomizer $rng = null): self
    {
        $base = dirname(__DIR__) . '/resources';

        $kuchen = [];
        foreach (WordList::fromFile($base . '/kuchentypen.txt')->all() as $line) {
            $kuchen[] = Kuchentyp::fromLine($line);
        }

        return new self(
            WordList::fromFile($base . '/adjektive.txt'),
            WordList::fromFile($base . '/sorten.txt'),
            $kuchen,
            $rng ?? new Randomizer(new Mt19937()),
        );
    }

    public function generate(int $words = 2, string $separator = '-'): string
    {
        if ($words < 1) {
            throw new \InvalidArgumentException("words must be >= 1, got {$words}");
        }

        $kuchen = $this->pickKuchentyp();

        if ($words === 1) {
            return mb_strtolower($kuchen->noun);
        }

        throw new \LogicException('words >= 2 not yet implemented');
    }

    private function pickKuchentyp(): Kuchentyp
    {
        $index = $this->rng->getInt(0, count($this->kuchentypen) - 1);
        return $this->kuchentypen[$index];
    }
}
