<?php

namespace BabDev\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class LinkAppStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'babdev:storage:link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates required symlinks for the "public" directory to "storage/app"';

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();

        $this->filesystem = $filesystem;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->getStoragePaths() as $diskName => $publicPath) {
            $diskConfig = $this->laravel['config']["filesystems.disks.{$diskName}"];

            if (!isset($diskConfig['root'], $diskConfig['url'])) {
                $this->error(\sprintf('The "%s" disk is not supported by this command.', $diskName));
            }

            $diskRoot = $diskConfig['root'];

            if ($this->filesystem->exists(public_path($publicPath))) {
                $this->error(\sprintf('The [public/%s] directory already exists.', $publicPath));

                continue;
            }

            $this->filesystem->link($diskRoot, public_path($publicPath));

            $this->info(\sprintf('The [public/%s] directory has been linked.', $publicPath));
        }
    }

    /**
     * @return array Associative array where the key is the filesystem disk name/path and the value is the public path
     */
    private function getStoragePaths(): array
    {
        return [
            'logos' => 'logos',
        ];
    }
}