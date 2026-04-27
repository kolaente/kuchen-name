<?php declare(strict_types=1);

namespace KuchenName;

use Random\Randomizer;

final class WordList
{
    /** @param list<string> $entries */
    private function __construct(private readonly array $entries) {}

    public static function fromFile(string $path): self
    {
        if (!is_readable($path)) {
            throw new \RuntimeException("WordList file not readable: {$path}");
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            throw new \RuntimeException("Failed to read WordList file: {$path}");
        }

        $entries = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }
            $entries[] = $line;
        }

        return new self($entries);
    }

    public function withMaxLetters(int $max): self
    {
        $filtered = array_values(array_filter(
            $this->entries,
            fn(string $w) => mb_strlen($w) <= $max,
        ));
        return new self($filtered);
    }

    /** @return list<string> */
    public function all(): array
    {
        return $this->entries;
    }

    public function pick(Randomizer $rng): string
    {
        if ($this->entries === []) {
            throw new \RuntimeException('Cannot pick from an empty WordList.');
        }
        $index = $rng->getInt(0, count($this->entries) - 1);
        return $this->entries[$index];
    }
}
