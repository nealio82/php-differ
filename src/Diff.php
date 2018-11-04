<?php

declare(strict_types=1);

namespace Madsen\Diff;

use ArrayIterator;
use Madsen\Diff\Chunk\{AbstractChunk, AddedChunk, RemovedChunk, UnchangedChunk};
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
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->chunks);
    }

    /**
     * Add chunk to the diff.
     *
     * @param AbstractChunk $chunk
     */
    public function addChunk(AbstractChunk $chunk): void
    {
        $this->chunks[] = $chunk;
    }

    /**
     * Count the number of added chunks of the diff.
     *
     * @return int
     */
    public function countAdded(): int
    {
        return $this->countChunksByType(AddedChunk::class);
    }

    /**
     * Count the number of removed chunks of the diff.
     *
     * @return int
     */
    public function countRemoved(): int
    {
        return $this->countChunksByType(RemovedChunk::class);
    }

    /**
     * Count the number of unchanged chunks of the diff.
     *
     * @return int
     */
    public function countUnchanged(): int
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
    private function countChunksByType(string $type): int
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
