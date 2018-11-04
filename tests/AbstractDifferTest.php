<?php

declare(strict_types=1);

/**
 * This file is part of the PHP Differ library.
 *
 * @author Oliver Finn Madsen <mail@ofmadsen.com>
 *
 * @license MIT
 */

namespace Test;

use DirectoryIterator;
use Madsen\Diff\Algorithm\Myers;
use Madsen\Diff\Chunk\{AddedChunk, RemovedChunk, UnchangedChunk};
use Madsen\Diff\Diff;
use Madsen\Diff\Differ;
use PHPUnit\Framework\TestCase;

abstract class AbstractDifferTest extends TestCase
{
    abstract protected function getDataFolder(): string;
    abstract protected function diff(Differ $differ, string $a, string $b): Diff;

    private $differ;

    public function setUp(): void
    {
        $this->differ = new Differ(new Myers());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testDiff(string $folder): void
    {
        $expected = explode("\n", file_get_contents("{$folder}/myers"));

        $a = file_get_contents("{$folder}/a");
        $b = file_get_contents("{$folder}/b");

        $diff = $this->diff($this->differ, $a, $b);

        $this->assertDiffIsEqual($expected, $diff);
        $this->assertChangesCount($expected, $diff);
    }

    public function dataProvider(): array
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

    private function assertDiffIsEqual(array $expected, Diff $diff): void
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

    private function assertChangesCount(array $expected, Diff $diff): void
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
}
