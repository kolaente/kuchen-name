# kuchen-name

Generate random German cake names — `saftige-kirsch-torte`, `süßer-apfel-kuchen`, `schlichtes-marzipan-brötchen`. Inspired by [petname](https://github.com/dustinkirkland/petname).

## Install

    composer require kolaente/kuchen-name

Requires PHP 8.2+.

## Library

```php
use KuchenName\Generator;

$gen = Generator::default();
echo $gen->generate();                              // saftige-torte
echo $gen->generate(words: 3);                      // süßer-apfel-kuchen
echo $gen->generate(words: 4, separator: '_');      // saftiger_schoko_kirsch_kuchen
```

For deterministic output (tests, fixtures), inject a seeded `Randomizer`:

```php
use Random\Randomizer;
use Random\Engine\Mt19937;

$gen = Generator::default(new Randomizer(new Mt19937(seed: 42)));
```

## How it works

Three text files under `resources/`:

- `adjektive.txt`  — adjective stems (no ending), e.g. `saftig`, `süß`
- `sorten.txt`     — flavors / ingredients, e.g. `schoko`, `kirsch`
- `kuchentypen.txt` — `Noun|gender` pairs, e.g. `Torte|f`

For 2 words: `<adjective>-<kuchentyp>`. For N≥3: `<adjective>-<sorte>×(N-2)-<kuchentyp>`. The adjective is inflected to match the noun's grammatical gender (`-er`/`-e`/`-es`).

To add words, edit the text files and send a PR.

## License

LGPL-3.0-only. See [LICENSE](LICENSE).
