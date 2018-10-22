<?php
namespace Test;

use DirectoryIterator;
use Goldcrest\Diff\Algorithm\Myers;
use Goldcrest\Diff\Chunk\AddedChunk;
use Goldcrest\Diff\Chunk\RemovedChunk;
use Goldcrest\Diff\Chunk\UnchangedChunk;
use Goldcrest\Diff\Differ;
use PHPUnit\Framework\TestCase;

class DifferTest extends TestCase
{
    private $differ;

    public function setUp()
    {
        // TODO Extract to into different tests
        // TODO Rename myers file to myers-line etc
        $this->differ = new Differ(new Myers());
    }

    /**
     * @dataProvider diffLinesProvider
     */
    public function testDiffLines($folder)
    {
        $old = file_get_contents("{$folder}/old");
        $new = file_get_contents("{$folder}/new");

        $expected = explode("\n", file_get_contents("{$folder}/myers"));
        $diff = $this->differ->diffLines($old, $new);

        $changeMap = [
            AddedChunk::class => '+',
            RemovedChunk::class => '-',
            UnchangedChunk::class => ' '
        ];

        $result = [];
        foreach ($diff as $index => $chunk) {
            $result[] = $changeMap[get_class($chunk)] . $chunk->getContent();
        }

        $this->assertSame($expected, $result);
    }

    public function diffLinesProvider()
    {
        $folders = [];
        // TODO Move to lines test
        foreach (new DirectoryIterator(__DIR__ . '/data') as $fileInfo) {
            if ($fileInfo->isDot()) {
                continue;
            }

            $folders[] = [__DIR__ . '/data/' . $fileInfo->getFilename()];
        }

        return $folders;
    }
}