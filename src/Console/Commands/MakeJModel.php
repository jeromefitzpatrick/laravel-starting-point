<?php

namespace JeromeFitzpatrick\StartingPoint\Console\Commands;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\ModelMakeCommand;

class MakeJModel extends ModelMakeCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:j-model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a new J Model class';

    /**
     * Laravel 13+ defines make:model with a signature, which takes precedence over $name,
     * so rename the inherited signature to avoid replacing make:model
     */
    public function __construct(Filesystem $files)
    {
        if (isset($this->signature)) {
            $this->signature = preg_replace('/^make:model\b/', $this->name, $this->signature);
        }

        parent::__construct($files);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/j-model.stub');
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
