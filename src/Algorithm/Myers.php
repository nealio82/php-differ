<?php
namespace Goldcrest\Diff\Algorithm;

use Goldcrest\Diff\Chunk\AddedChunk;
use Goldcrest\Diff\Chunk\RemovedChunk;
use Goldcrest\Diff\Chunk\UnchangedChunk;

class Myers implements AlgorithmInterface
{
    public function diff(array $a, array $b)
    {
        $shortestEdit = $this->getShortestEdit($a, $b);

        $diff = [];
        foreach ($this->backtrack($shortestEdit, $a, $b) as list($prevX, $prevY, $x, $y)) {
            if ($x === $prevX) {
                $diff[] = new AddedChunk($b[$prevY]);
            } elseif ($y === $prevY) {
                $diff[] = new RemovedChunk($a[$prevX]);
            } else {
                $diff[] = new UnchangedChunk($a[$prevX]);
            }
        }

        return array_reverse($diff);
    }

    private function getShortestEdit(array $a, array $b)
    {
        $n = count($a);
        $m = count($b);

        $max = $n + $m;

        $keys = array_keys(array_flip(range($max * -1, $max)));
        $values = array_fill(0, $max * 2 + 1, null);

        $v = array_combine($keys, $values);
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
    }

    private function backtrack(array $shortestEdit, array $a, array $b)
    {
        $x = count($a);
        $y = count($b);

        foreach (array_reverse($shortestEdit, true) as $d => $v) {
            $k = $x - $y;

            if ($k === $d * -1 || ($k !== $d && $v[$k - 1] < $v[$k + 1])) {
                $prevK = $k + 1;
            } else {
                $prevK = $k - 1;
            }

            $prevX = $v[$prevK];
            $prevY = $prevX - $prevK;

            while ($x > $prevX && $y > $prevY) {
                yield [$x - 1, $y - 1, $x, $y];

                $x--;
                $y--;
            }

            if ($d > 0) {
                yield [$prevX, $prevY, $x, $y];
            }

            $x = $prevX;
            $y = $prevY;
        }
    }
}
