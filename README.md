# PHP Differ
Library for creating line or word diffs using [Myers algorithm](http://www.xmailserver.org/diff2.pdf).

[![Build Status](https://travis-ci.org/ofmadsen/php-differ.svg?branch=master)](https://travis-ci.org/ofmadsen/php-differ)

## Installation
Install the latest version with:

```bash
$ composer require ofmadsen/php-differ
```

## Usage
```php
$algorithm = new Madsen\Diff\Algorithm\Myers();
$differ = new Madsen\Diff\Differ($algorithm);

// $a and $b are strings
$diff = $differ->diffLines($a, $b);
$diff = $differ->diffWords($a, $b);

// $diff is Madsen\Diff\Diff that can be iterated:
foreach ($diff as $chunk) {
    // $chunk is an object of Madsen\Diff\Chunk\AbstractChunk type
}

$diff->countAdded(); // Get the number of added chunks
$diff->countRemoved(); // Get the number of removed chunks
$diff->countUnchanged(); // Get the number of unchanged chunks
```

## Contribution
All are welcome to contribute to PHP Differ. Please get in touch before making large features and/or major refactoring. Needlessly to say the coding style must be followed and full test coverage is required.

## License
PHP Differ is available under the MIT License - see the [`LICENSE`](LICENSE) file for details.