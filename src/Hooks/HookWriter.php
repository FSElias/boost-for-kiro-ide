<?php

declare(strict_types=1);

namespace Jcf\BoostForKiro\Hooks;

use RuntimeException;

class HookWriter
{
    public const WRITTEN = 0;

    public const UPDATED = 1;

    public const FAILED = 2;

    public function __construct(protected string $hooksPath)
    {
        //
    }

    /**
     * Write a hook JSON file to the hooks directory.
     *
     * @param  array<string, mixed>  $hookData
     * @return self::WRITTEN|self::UPDATED|self::FAILED
     */
    public function write(string $filename, array $hookData): int
    {
        $directory = base_path($this->hooksPath);

        if (! is_dir($directory) && ! @mkdir($directory, 0755, true)) {
            throw new RuntimeException("Failed to create directory: {$directory}");
        }

        $filePath = $directory.DIRECTORY_SEPARATOR.$filename.'.kiro.hook';
        $existed = file_exists($filePath);

        $json = json_encode($hookData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        if ($json === false) {
            return self::FAILED;
        }

        if (file_put_contents($filePath, $json."\n") === false) {
            return self::FAILED;
        }

        return $existed ? self::UPDATED : self::WRITTEN;
    }

    /**
     * Remove hook files that are no longer needed.
     *
     * @param  array<int, string>  $currentHookFilenames
     * @return array<string, bool>
     */
    public function removeStale(array $currentHookFilenames): array
    {
        $directory = base_path($this->hooksPath);
        $results = [];

        if (! is_dir($directory)) {
            return $results;
        }

        $existingFiles = glob($directory.DIRECTORY_SEPARATOR.'boost-prompt-*.kiro.hook') ?: [];

        foreach ($existingFiles as $filePath) {
            $basename = basename($filePath, '.kiro.hook');

            if (! in_array($basename, $currentHookFilenames, true)) {
                $results[$basename] = @unlink($filePath);
            }
        }

        return $results;
    }
}
