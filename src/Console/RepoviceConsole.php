<?php

namespace Laravel\Repovices\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class RepoviceConsole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'repovice:create {modelName : Name of the model for repository and service create}';

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

            // Get the model details like fields, fillable, hidden, etc.
            $class = 'App\\Models\\'.$modelName;
            $model = new $class;

            $fillableProperitesArray = implode(",\n\t\t\t", array_map(function ($fillableAttribute) {
                return '"' . $fillableAttribute . '" => $data["' . $this->toCamelCase($fillableAttribute) . '"]';
            }, $model->getFillable()));

            $packageDirectory = dirname(__DIR__, 2);
            $repositoryStub = file_get_contents($packageDirectory.'/stubs/Repositories/DefaultRepository.stub');
            $repositoryContent = str_replace('{{ modelName }}', $modelName, $repositoryStub);
            $repositoryContent = str_replace('{{ modelNameLC }}', lcfirst($modelName), $repositoryContent);
            $repositoryContent = str_replace('{{ fieldsArray }}', $fillableProperitesArray, $repositoryContent);

            $serviceStub = file_get_contents($packageDirectory.'/stubs/Services/DefaultService.stub');
            $serviceContent = str_replace('{{ modelName }}', $modelName, $serviceStub);
            $serviceContent = str_replace('{{ modelNameLC }}', lcfirst($modelName), $serviceContent);

            $repo_directory = Config::get('repovice.repository_path');
            if(!is_dir($repo_directory)){
                mkdir($repo_directory, 0777, true);
            }
            $repositoryPath = Config::get('repovice.repository_path')."/{$modelName}Repository.php";

            $service_directory = Config::get('repovice.service_path');
            if(!is_dir($service_directory)){
                mkdir($service_directory, 0777, true);
            }
            $servicePath = Config::get('repovice.service_path')."/{$modelName}Service.php";

            file_put_contents($repositoryPath, $repositoryContent);
            file_put_contents($servicePath, $serviceContent);

            $this->info("Repository and service classes generated successfully for {$modelName}.");
        }
    }

    /**
     * Convert string to camel case
     *
     * @param  string $string
     * @return string
     */
    protected function toCamelCase($text)
    {
        if (empty($text)) {
            return $text;
        }

        $words = explode('_', $text);

        if (count($words) === 1) {
            return $text;
        }

        return lcfirst(implode('', array_map('ucfirst', $words)));
    }
}
