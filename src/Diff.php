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
    /** @var AbstractChunk[] */
    private $chunks = [];

    /**
     * Iterator aggregate method.
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->chunks);
    }

    /**
     * Add chunk to the diff.
     *
     * @param AbstractChunk $chunk
     */
    public function addChunk(AbstractChunk $chunk)
    {
        $this->chunks[] = $chunk;
    }

    /**
     * Count the number of added chunks of the diff.
     *
     * @return int
     */
    public function countAdded()
    {
        return $this->countChunksByType(AddedChunk::class);
    }

    /**
     * Count the number of removed chunks of the diff.
     *
     * @return int
     */
    public function countRemoved()
    {
        return $this->countChunksByType(RemovedChunk::class);
    }

    /**
     * Count the number of unchanged chunks of the diff.
     *
     * @return int
     */
    public function countUnchanged()
    {
        return $this->countChunksByType(UnchangedChunk::class);
    }

    /**
     * Count the number of chunks of a given type.
     *
     * @param string $type
     *
     * @return int
     */
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
