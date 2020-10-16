<?php

namespace JeromeFitzpatrick\StartingPoint\Console\Commands;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Routing\Console\ControllerMakeCommand;

class MakeJDelete
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:j-delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $files;

    public function __construct(Filesystem $files)
    {
        $this->files = $files;
    }

    public function handle()
    {
        $this->files->delete($this->laravel->basePath('/.00'));

    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/j-controller.stub');
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__.'/../..'.$stub;
    }
}
