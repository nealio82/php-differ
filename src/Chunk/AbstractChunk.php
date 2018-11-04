<?php

declare(strict_types=1);

/**
 * This file is part of the PHP Differ library.
 *
 * @author Oliver Finn Madsen <mail@ofmadsen.com>
 *
 * @license MIT
 */

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
