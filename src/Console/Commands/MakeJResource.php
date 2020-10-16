<?php

namespace JeromeFitzpatrick\StartingPoint\Console\Commands;

use Illuminate\Console\Command;

class MakeJResource extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:j-resource {name}';

    public function handle()
    {
        $this->call(
            'make:j-model',
            [
                'name' => $this->argument('name'),
                '--factory' => true,
                '--seed' => true,
                '--migration' => true,
            ]
        );

        $this->call(
            'make:j-response',
            [
                'name' => $this->argument('name') . 'Response',
                '--model' => $this->argument('name'),
            ]
        );

        $this->call(
            'make:j-request',
            [
                'name' => $this->argument('name') . 'Request',
            ]
        );

        $this->call(
            'make:j-transformer',
            [
                'name' => $this->argument('name') . 'Transformer',
                '--model' => $this->argument('name'),
            ]
        );

        $this->call(
            'make:j-controller',
            [
                'name' => $this->argument('name') . 'Controller',
                '--model' => $this->argument('name'),
            ]
        );
    }
}
