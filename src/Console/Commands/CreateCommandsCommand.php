<?php

namespace Dainsys\RingCentral\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class CreateCommandsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rc:create-command 
        {name?} Command name! 
        {signature?} Command signature! 
        ';

    protected Filesystem $file_system;

    public function __construct()
    {
        parent::__construct();
        $this->file_system = new Filesystem();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name') ?: $this->ask('Please enter command name!');
        $signature = $this->argument('signature') ?: $this->ask('Please enter command signature!');
        $name = str($name)->studly();
        $signature = str($signature)->kebab();

        $stub_content = $this->file_system->get(__DIR__ . '/../../../stubs/command.stub');
        $new_content = str($stub_content)->replace('{{ name }}', $name)->replace('{{ signature }}', $signature);
        $path = app_path("Console/Commands/{$name}.php");

        $this->file_system->put($path, $new_content);

        $this->info("Command created at {$path}");

        return 0;
    }
}
