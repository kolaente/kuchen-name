<?php declare(strict_types=1);

namespace KuchenName;

final class Inflector
{
    public static function adjective(string $entry, Gender $gender): string
    {
        if (str_contains($entry, '|')) {
            $parts = explode('|', $entry);
            if (count($parts) !== 4) {
                throw new \InvalidArgumentException(
                    "Irregular adjective must have 4 fields (stem|m|f|n), got "
                    . count($parts) . ": '{$entry}'"
                );
            }
            return match ($gender) {
                Gender::Masculine => $parts[1],
                Gender::Feminine  => $parts[2],
                Gender::Neuter    => $parts[3],
            };
        }

        return $entry . match ($gender) {
            Gender::Masculine => 'er',
            Gender::Feminine  => 'e',
            Gender::Neuter    => 'es',
        };
    }
}
