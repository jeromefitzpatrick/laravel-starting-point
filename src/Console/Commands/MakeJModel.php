<?php

namespace JeromeFitzpatrick\StartingPoint\Console\Commands;

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
