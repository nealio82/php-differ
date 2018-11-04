<?php

declare(strict_types=1);

namespace Madsen\Diff;

use Madsen\Diff\Algorithm\AlgorithmInterface;

class Differ
{
    /** @var AlgorithmInterface */
    private $algorithm;

    /**
     * Constructor.
     *
     * @param AlgorithmInterface $algorithm
     */
    public function __construct(AlgorithmInterface $algorithm)
    {
        $this->algorithm = $algorithm;
    }

    /**
     * Diff line by line.
     *
     * @param string $a
     * @param string $b
     *
     * @return Diff
     */
    public function diffLines(string $a, string $b): Diff
    {
        return $this->diff($a, $b, '/\R/');
    }

    /**
     * Diff word by word.
     *
     * @param string $a
     * @param string $b
     *
     * @return Diff
     */
    public function diffWords(string $a, string $b): Diff
    {
        return $this->diff($a, $b, '/(\S+\s*)/', PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
    }

    /**
     * Get diff splitted by the provided regex.
     *
     * @param string $a
     * @param string $b
     * @param string $splitRegex
     * @param int $flags
     *
     * @return Diff
     */
    private function diff(string $a, string $b, string $splitRegex, int $flags = 0): Diff
    {
        $diff = new Diff();

        $chunks = $this->algorithm->diff(
            preg_split($splitRegex, $a, -1, $flags),
            preg_split($splitRegex, $b, -1, $flags)
        );

        foreach ($chunks as $chunk) {
            $diff->addChunk($chunk);
        }

        return $diff;
    }
}
