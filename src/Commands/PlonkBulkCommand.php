<?php

namespace Metrique\Plonk\Commands;

use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;
use RecursiveRegexIterator;
use Metrique\Plonk\Repositories\PlonkStoreInterface;

class PlonkBulkCommand extends Command
{
    private $dir;
    private $images = [];
    private $plonk;
    private $tags = [];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'metrique:plonk-bulk
                            {--dir= : Set the directory to scan for images.}
                            {--title= : Set title tag, defaults to file name.}
                            {--alt= : Set alt tag, defaults to file name.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload files to plonk in bulk.';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->plonk = \App::make(PlonkStoreInterface::class);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->setOptions();

        if (!is_dir($this->dir)) {
            throw new \Exception('Could not find the directory, aborting. ('.$this->dir.')');
        }

        if (!$this->compileImages()) {
            throw new \Exception('Could not find any images within the specified directory, aboring. ('.$this->dir.')');
        }

        $this->sendImagesToPlonk();
    }

    private function setOptions()
    {
        $this->dir = storage_path().'/plonk/bulk';

        if (is_string($this->option('dir'))) {
            $this->dir = $this->option('dir');
        }

        if (is_string($this->option('title'))) {
            $this->tags['title'] = $this->option('title');
        }

        if (is_string($this->option('alt'))) {
            $this->tags['alt'] = $this->option('alt');
        }
    }

    private function compileImages()
    {
        $directory = new RecursiveDirectoryIterator($this->dir);
        $iterator = new RecursiveIteratorIterator($directory);
        $regexIterator = new RegexIterator($iterator, '/^.+\.(jpg|png|gif)$/i', RecursiveRegexIterator::GET_MATCH);

        $this->images = iterator_to_array($regexIterator, false);
        if (count($this->images) < 1) {
            return false;
        }

        $this->info(sprintf('Found %s images...', count($this->images)));

        return true;
    }

    private function sendImagesToPlonk()
    {
        foreach ($this->images as $key => $value) {
            array_key_exists('title', $this->tags) ? $title = $this->tags['title'] : $title = $value[0];
            array_key_exists('alt', $this->tags) ? $alt = $this->tags['alt'] : $title = $value[0];

            $this->info('Plonking '.$title.'...');
            $this->plonk->storeCli($value[0], $title, $alt, '');
            $this->info('Done...');
        }
    }
}
