<?php
namespace Goldcrest\Diff;

use Goldcrest\Diff\Algorithm\AlgorithmInterface;
use Goldcrest\Diff\Algorithm\Myers;

class Differ
{
    private $algorithm;

    public function __construct(AlgorithmInterface $algorithm = null)
    {
        if ($algorithm === null) {
            $algorithm = new Myers();
        }

        $this->algorithm = $algorithm;
    }

    public function diffLines($old, $new)
    {
        return $this->diff($old, $new, '/\R/');
    }

    public function diffWords($old, $new)
    {
        return $this->diff($old, $new, '/\S+\s*/');
    }

    private function diff($old, $new, $splitRegex)
    {
        $old = preg_split($splitRegex, $old);
        $new = preg_split($splitRegex, $new);

        $diff = new Diff();
        foreach ($this->algorithm->diff($old, $new) as $chunk) {
            $diff->addChunk($chunk);
        }

        return $diff;
    }
}