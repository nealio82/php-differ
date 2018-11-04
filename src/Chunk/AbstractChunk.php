<?php

declare(strict_types=1);

namespace Madsen\Diff\Chunk;

abstract class AbstractChunk
{
    /** @var string */
    private $content;

    /**
     * Constructor.
     *
     * @param string $content
     */
    public function __construct(string $content)
    {
        $this->content = $content;
    }

    /**
     * Get chunk content.
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }
}
