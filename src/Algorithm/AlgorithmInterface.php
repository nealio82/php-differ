<?php
namespace Goldcrest\Diff\Algorithm;

interface AlgorithmInterface
{
    public function diff(array $a, array $b);
}