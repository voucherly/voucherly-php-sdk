<?php

namespace VoucherlyApi\Tests\Core;

use PHPUnit\Framework\TestCase;

final class PackageTest extends TestCase
{
    private const LIB = __DIR__ . '/../../lib';

    public function testEveryDirectoryOfTheLibraryHasAnIndexPhp(): void
    {
        $directories = [self::LIB];
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(self::LIB, \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::SELF_FIRST) as $entry) {
            if ($entry->isDir()) {
                $directories[] = $entry->getPathname();
            }
        }

        foreach ($directories as $directory) {
            self::assertFileExists($directory . '/index.php', 'PrestaShop rejects a module whose directories have no index.php.');
        }
    }

    public function testInitPhpLoadsEveryClassOfTheLibrary(): void
    {
        $script = <<<'PHP'
            <?php
            require $argv[1] . '/init.php';
            $lib = realpath($argv[1] . '/lib');
            $missing = [];
            foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($lib, FilesystemIterator::SKIP_DOTS)) as $file) {
                if ('php' !== $file->getExtension() || 'index.php' === $file->getFilename()) {
                    continue;
                }
                $class = 'VoucherlyApi\\' . str_replace(['/', '\\'], '\\', substr($file->getPathname(), strlen($lib) + 1, -4));
                if (!class_exists($class) && !interface_exists($class)) {
                    $missing[] = $class;
                }
            }
            echo json_encode($missing);
            PHP;

        $file = tempnam(sys_get_temp_dir(), 'voucherly');
        file_put_contents($file, $script);

        try {
            $output = shell_exec(escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg($file) . ' ' . escapeshellarg((string) realpath(\dirname(self::LIB))));
        } finally {
            unlink($file);
        }

        self::assertSame('[]', $output);
    }
}
