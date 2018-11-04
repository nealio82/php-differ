<?php

declare(strict_types=1);

namespace Madsen\Diff\Algorithm;

use Madsen\Diff\Chunk\{AbstractChunk, AddedChunk, RemovedChunk, UnchangedChunk};

/**
 * Implementation of the Myers diff algorithm without refinements.
 *
 * The algorithm is described in Eugene W. Myers' 1986 paper: "An O(ND) Difference Algorithm
 * and Its Variations".
 *
 * @see http://www.xmailserver.org/diff2.pdf
 * @see https://blog.jcoglan.com/2017/02/12/the-myers-diff-algorithm-part-1/
 */
class Myers implements AlgorithmInterface
{
    /**
     * Diff method.
     *
     * @param string[] $a
     * @param string[] $b
     *
     * @return \Madsen\Diff\Chunk\AbstractChunk[]
     */
    public function diff(array $a, array $b): array
    {
        return $this->backtrack($this->getShortestPath($a, $b), $a, $b);
    }

    /**
     * Get a trace of the shortest path in the graph, where we value deletions above insertions.
     *
     * @param string[] $a
     * @param string[] $b
     *
     * @return int[][]
     */
    private function getShortestPath(array $a, array $b): array
    {
        $n = count($a);
        $m = count($b);

        $max = $n + $m;

        $v = $this->getInitialStepArray($max);
        $v[1] = 0;

        $trace = [];
        for ($d = 0; $d <= $max; $d++) {
            $trace[] = $v;

            for ($k = $d * -1; $k <= $d; $k += 2) {
                if ($k === $d * -1 || ($k !== $d && $v[$k - 1] < $v[$k + 1])) {
                    $x = $v[$k + 1];
                } else {
                    $x = $v[$k - 1] + 1;
                }

                $y = $x - $k;

                while ($x < $n && $y < $m && $a[$x] === $b[$y]) {
                    $x++;
                    $y++;
                }

                $v[$k] = $x;

                if ($x >= $n && $y >= $m) {
                    return $trace;
                }
            }
        }
    } // @codeCoverageIgnore

    /**
     * Get array that can contain x, indexed by very possible k.
     *
     * @param int $maxSteps
     *
     * @return int[]
     */
    private function getInitialStepArray(int $maxSteps): array
    {
        $keys = range($maxSteps * -1, $maxSteps);
        $values = array_fill(0, $maxSteps * 2 + 1, null);

        return array_combine($keys, $values);
    }

    /**
     * Backtrack the path to create chunks of what has been added, removed and unchanged.
     *
     * @param int[][] $path
     * @param string[] $a
     * @param string[] $b
     *
     * @return \Madsen\Diff\Chunk\AbstractChunk[]
     */
    private function backtrack(array $path, array $a, array $b): array
    {
        $chunks = [];

        $x = count($a);
        $y = count($b);

        foreach (array_reverse($path, true) as $d => $v) {
            $k = $x - $y;

            if ($k === $d * -1 || ($k !== $d && $v[$k - 1] < $v[$k + 1])) {
                $prevK = $k + 1;
            } else {
                $prevK = $k - 1;
            }

            $prevX = $v[$prevK];
            $prevY = $prevX - $prevK;

            while ($x > $prevX && $y > $prevY) {
                $chunks[] = $this->createChunk($a, $b, $x - 1, $y - 1, $x, $y);

                $x--;
                $y--;
            }

            if ($d > 0) {
                $chunks[] = $this->createChunk($a, $b, $prevX, $prevY, $x, $y);                
            }

            $x = $prevX;
            $y = $prevY;
        }

        return array_reverse($chunks);
    }

    /**
     * Create chunk with content from one of the arrays.
     *
     * @param string[] $a
     * @param string[] $b
     * @param int $prevX
     * @param int $prevY
     * @param int $x
     * @param int $y
     *
     * @return \Madsen\Diff\Chunk\AbstractChunk
     */
    private function createChunk(array $a, array $b, int $prevX, int $prevY, int $x, int $y): AbstractChunk
    {
        if ($x === $prevX) {
            return new AddedChunk($b[$prevY]);
        }

        if ($y === $prevY) {
            return new RemovedChunk($a[$prevX]);
        }

        return new UnchangedChunk($a[$prevX]);
    }
}
