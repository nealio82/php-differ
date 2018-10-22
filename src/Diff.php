<?php
namespace Goldcrest\Diff;

use ArrayIterator;
use Goldcrest\Diff\Chunk\AbstractChunk;
use Goldcrest\Diff\Chunk\AddedChunk;
use Goldcrest\Diff\Chunk\RemovedChunk;
use Goldcrest\Diff\Chunk\UnchangedChunk;
use IteratorAggregate;

class Diff implements IteratorAggregate
{
    private $chunks = [];

    public function getIterator()
    {
        return new ArrayIterator($this->chunks);
    }

    public function addChunk(AbstractChunk $chunk)
    {
        $this->chunks[] = $chunk;
    }

    public function countAdded()
    {
        return $this->countChunksByType(AddedChunk::class);
    }

    public function countRemoved()
    {
        return $this->countChunksByType(RemovedChunk::class);
    }

    public function countUnchanged()
    {
        return $this->countChunksByType(UnchangedChunk::class);
    }

    private function countChunksByType($type)
    {
        $count = 0;
        foreach ($this->chunks as $chunk) {
            if ($chunk instanceof $type) {
                $count++;
            }
        }

        return $count;
    }
}