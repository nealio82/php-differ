<?php

declare(strict_types=1);

/**
 * This file is part of the PHP Differ library.
 *
 * @author Oliver Finn Madsen <mail@ofmadsen.com>
 *
 * @license MIT
 */

namespace Madsen\Diff\Algorithm;

interface AlgorithmInterface
{
    /**
     * Diff function.
     *
     * The implementation must compare the two input arrays, a and b, and return an array of
     * chunks that defines what have been added, removed and unchanged between a and b.
     *
     * @param string[] $a
     * @param string[] $b
     *
     * @return \Madsen\Diff\Chunk\AbstractChunk[]
     */
    public function diff(array $a, array $b): array;
}
