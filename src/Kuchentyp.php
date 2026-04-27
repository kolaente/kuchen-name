<?php declare(strict_types=1);

namespace KuchenName;

final readonly class Kuchentyp
{
    public function __construct(
        public string $noun,
        public Gender $gender,
    ) {}

    public static function fromLine(string $line): self
    {
        $parts = explode('|', trim($line), 2);
        if (count($parts) !== 2 || $parts[0] === '' || $parts[1] === '') {
            throw new \InvalidArgumentException("Malformed kuchentyp line: '{$line}' (expected 'Noun|m|f|n')");
        }

        $gender = Gender::tryFrom($parts[1])
            ?? throw new \InvalidArgumentException("Unknown gender '{$parts[1]}' in line: '{$line}'");

        return new self($parts[0], $gender);
    }
}
