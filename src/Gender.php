<?php declare(strict_types=1);

namespace KuchenName;

enum Gender: string
{
    case Masculine = 'm';
    case Feminine  = 'f';
    case Neuter    = 'n';
}
