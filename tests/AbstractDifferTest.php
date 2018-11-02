<?php
namespace Test;

use DirectoryIterator;
use Goldcrest\Diff\Algorithm\Myers;
use Goldcrest\Diff\Chunk\AddedChunk;
use Goldcrest\Diff\Chunk\RemovedChunk;
use Goldcrest\Diff\Chunk\UnchangedChunk;
use Goldcrest\Diff\Diff;
use Goldcrest\Diff\Differ;
use PHPUnit\Framework\TestCase;

abstract class AbstractDifferTest extends TestCase
{
    abstract protected function getDataFolder();
    abstract protected function diff(Differ $differ, $a, $b);

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

        $this->assertChangesCount($expected, $diff);
        $this->assertDiffIsEqual($expected, $diff);
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

    private function assertChangesCount(array $expected, Diff $diff)
    {
        $changes = [
            '+' => 0,
            '-' => 0,
            ' ' => 0
        ];

        foreach ($expected as $line) {
            $changes[substr($line, 0, 1)]++;
        }

        $this->assertSame($changes['+'], $diff->countAdded());
        $this->assertSame($changes['-'], $diff->countRemoved());
        $this->assertSame($changes[' '], $diff->countUnchanged());
    }

    private function assertDiffIsEqual(array $expected, Diff $diff)
    {
        $chunkMap = [
            AddedChunk::class => '+',
            RemovedChunk::class => '-',
            UnchangedChunk::class => ' '
        ];

        $result = [];
        foreach ($diff as $chunk) {
            $result[] = $chunkMap[get_class($chunk)] . $chunk->getContent();
        }

        $this->assertSame($expected, $result);
    }
}
