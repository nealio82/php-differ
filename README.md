# PHP Differ
Library for creating diff between two strings, lines or words. It was created as a learning experience for myself, and published as it may be usable for others.

[![Build Status](https://travis-ci.org/ofmadsen/php-differ.svg?branch=master)](https://travis-ci.org/ofmadsen/php-differ)

## Installation
Install the latest version with:

```bash
$ composer require ofmadsen/php-differ
```

## Algorithms

- `Madsen\Diff\Algorithm\Myers`, described in Eugene W. Myers' 1986 [paper](http://www.xmailserver.org/diff2.pdf).

## Usage
```php
$algorithm = new Madsen\Diff\Algorithm\Myers();
$differ = new Madsen\Diff\Differ($algorithm);

// $a and $b are strings
$diff = $differ->diffLines($a, $b); // Also support ::diffWords()

// $diff is Madsen\Diff\Diff that can be iterated:
foreach ($diff as $chunk) {
    // $chunk is an Madsen\Diff\Chunk\AbstractChunk object; AddedChunk, RemovedChunk or UnchangedChunk
    $content = $chunk->getContent();
}

$added = $diff->countAdded(); // Get the number of added chunks
$removed = $diff->countRemoved(); // Get the number of removed chunks
$unchanged = $diff->countUnchanged(); // Get the number of unchanged chunks
```

## Contribution
All are welcome to contribute to PHP Differ. Please get in touch before making large features and/or major refactoring. Needlessly to say the coding style must be followed and full test coverage is required.

## License
PHP Differ is available under the MIT License - see the [`LICENSE`](LICENSE) file for details.