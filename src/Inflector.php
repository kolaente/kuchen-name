<?php declare(strict_types=1);

namespace KuchenName;

final class Inflector
{
    public static function adjective(string $stem, Gender $gender): string
    {
        return $stem . match ($gender) {
            Gender::Masculine => 'er',
            Gender::Feminine  => 'e',
            Gender::Neuter    => 'es',
        };
    }
}
