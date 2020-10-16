<?php

namespace JeromeFitzpatrick\StartingPoint\Console\Commands;

use Illuminate\Routing\Console\ControllerMakeCommand;

class MakeJTransformer extends ControllerMakeCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:j-transformer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Jerome fractal transformer class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Transformer';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/j-transformer.stub');
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

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Transformers';
    }
}
