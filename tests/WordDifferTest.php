<?php
namespace Test;

use Goldcrest\Diff\Differ;

class WordDifferTest extends AbstractDifferTest
{
    protected function getDataFolder()
    {
        return 'word-data';
    }

    protected function diff(Differ $differ, $a, $b)
    {
        return $differ->diffWords($a, $b);
    }
}
