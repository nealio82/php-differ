<?php
namespace Test;

use DirectoryIterator;
use Goldcrest\Diff\Algorithm\Myers;
use Goldcrest\Diff\Chunk\AddedChunk;
use Goldcrest\Diff\Chunk\RemovedChunk;
use Goldcrest\Diff\Chunk\UnchangedChunk;
use Goldcrest\Diff\Differ;
use PHPUnit\Framework\TestCase;

abstract class AbstractDifferTest extends TestCase
{
    private $chunkMap = [
        AddedChunk::class => '+',
        RemovedChunk::class => '-',
        UnchangedChunk::class => ' '
    ];

    private $differ;

    public function setUp()
    {
        $this->differ = new Differ(new Myers());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testDiff($folder)
    {
        $expected = explode("\n", file_get_contents("{$folder}/myers"));

        $a = file_get_contents("{$folder}/a");
        $b = file_get_contents("{$folder}/b");

        $diff = $this->diff($this->differ, $a, $b);

        $result = [];
        foreach ($diff as $index => $chunk) {
            $result[] = $this->chunkMap[get_class($chunk)] . $chunk->getContent();
        }

        $this->assertSame($expected, $result);
    }

    public function dataProvider()
    {
        $folder = $this->getDataFolder();

        $folders = [];
        foreach (new DirectoryIterator(__DIR__ . "/{$folder}") as $fileInfo) {
            if ($fileInfo->isDot()) {
                continue;
            }

            $folders[] = [__DIR__ . "/{$folder}/{$fileInfo->getFilename()}"];
        }

        return $folders;
    }

    abstract protected function getDataFolder();
    abstract protected function diff(Differ $differ, $a, $b);
}
