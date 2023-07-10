<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class CreateRepository extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Repository';


    public function getStub()
    {
        return app_path().'/Console/Commands/Stubs/make:repository.stub';
    }

    public function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Repositories';
    }

    public function replaceClass($stub, $name)
    {
        $stub = parent::replaceClass($stub, $name);

        return str_replace("DummyRepository", $this->argument('name'), $stub);
    }
}
