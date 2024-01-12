<?php

namespace Laravel\Repovice\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class RepoviceConsole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'repovice:create {modelName: Name of the model for repository and service create}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a repository, service and controller for a model';

    /**
     * Execute the console command.
     *
     * @param  \Laravel\Passport\ClientRepository  $clients
     * @return void
     */
    public function handle()
    {
        if($this->argument('modelName')){
            $modelName = $this->argument('modelName');

            // Check model exist or not using class_exists function
            if(!class_exists('App\\Models\\'.$modelName)){
                $this->error('Model not found');
                return;
            }

            $repositoryStub = file_get_contents(__DIR__.'/stubs/app/Repositories/DefaultRepository.php');
            $repositoryContent = str_replace('{{ modelName }}', $modelName, $repositoryStub);
            $repositoryContent = str_replace('{{ modelNameLC }}', lcfirst($modelName), $repositoryContent);

            $serviceStub = file_get_contents(__DIR__.'/stubs/app/Services/DefaultService.php');
            $serviceContent = str_replace('{{ modelName }}', $modelName, $serviceStub);
            $serviceContent = str_replace('{{ modelNameLC }}', lcfirst($modelName), $serviceContent);

            $repositoryPath = Config::get('repovice.repository_path')."/{$modelName}Repository.php";
            $servicePath = Config::get('repovice.service_path')."/{$modelName}Service.php";

            file_put_contents($repositoryPath, $repositoryContent);
            file_put_contents($servicePath, $serviceContent);

            $this->info("Repository and service classes generated successfully for {$modelName}.");
        }
    }
}
