<?php
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
    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * Get chunk content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
}
