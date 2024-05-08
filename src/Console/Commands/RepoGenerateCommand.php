<?php

declare(strict_types=1);

namespace Pavelmgn\RepoGenerator\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class RepoGenerateCommand extends Command
{
    protected $signature = 'repo:generate
     {entityName : Entity name for generator}';

    protected $description = 'Generate';

    public function handle()
    {
        $variables = $this->getVariables($this->argument('entityName'));

        $this->makeModel($variables);
        $this->makeSearch($variables);
        $this->makeRepository($variables);
        $this->makeResponse($variables);
        $this->makeRequests($variables);
        $this->makeController($variables);
    }

    private function getStubFolder(): string
    {
        return __DIR__ . "/../../../stubs";
    }

    private function getVariables($name): array
    {
        return [
            'entityNamespace'        => 'App\\Models\\' . $name,
            'entityClass'            => $name,
            'controllerNamespace'    => config('repogenerator.controller_namespace', 'App\\Http\\Controllers') . '\\' . $name,
            'controllerClass'        => $name . 'Controller',
            'responseNamespace'      => 'App\\Http\\ResponseFields\\' . $name,
            'responseClass'          => $name . 'Fields',
            'repositoryNamespace'    => 'App\\Repositories\\' . $name,
            'repositoryClass'        => $name . 'Repository',
            'createRequestNamespace' => 'App\\Http\\Requests\\' . $name,
            'createRequestClass'     => 'Create' . $name . 'Request',
            'updateRequestNamespace' => 'App\\Http\\Requests\\' . $name,
            'updateRequestClass'     => 'Update' . $name . 'Request',
            'searchNamespace'        => 'App\\Search\\' . $name,
            'searchClass'            => $name . 'Search',
            'camelEntityClass'       => $name,
            'snakeEntityClass'       => Str::snake($name),
        ];
    }

    private function makeModel(array $variables): void
    {
        $path = app_path('Models/' . $variables['entityClass'] . '/' . $variables['entityClass'] . '.php');
        $template = $this->getStubFolder() . '/model.stub';

        $this->generate($variables, $template, $path);
    }

    private function makeSearch(array $variables): void
    {
        $path = app_path('Search/' . $variables['entityClass'] . '/' . $variables['entityClass'] . 'Search.php');
        $template = $this->getStubFolder() . '/search.stub';

        $this->generate($variables, $template, $path);
    }

    private function makeRepository(array $variables): void
    {
        $path = app_path('Repositories/' . $variables['entityClass'] . '/' . $variables['entityClass'] . 'Repository.php');
        $template = $this->getStubFolder() . '/repository.stub';

        $this->generate($variables, $template, $path);
    }

    private function makeResponse(array $variables): void
    {
        $path = app_path('Http/ResponseFields/' . $variables['entityClass'] . '/' . $variables['entityClass'] . 'Fields.php');
        $template = $this->getStubFolder() . '/response.stub';

        $this->generate($variables, $template, $path);
    }

    private function makeRequests(array $variables): void
    {
        $path = app_path('Http/Requests/' . $variables['entityClass'] . '/Create' . $variables['entityClass'] . 'Request.php');
        $template = $this->getStubFolder() . '/create_request.stub';

        $this->generate($variables, $template, $path);

        $path = app_path('Http/Requests/' . $variables['entityClass'] . '/Update' . $variables['entityClass'] . 'Request.php');
        $template = $this->getStubFolder() . '/update_request.stub';

        $this->generate($variables, $template, $path);
    }

    private function makeController(array $variables): void
    {
        $path = app_path(config('repogenerator.controller_path', 'Http/Controllers') . '/' . $variables['entityClass'] . '/' . $variables['entityClass'] . 'Controller.php');
        $template = $this->getStubFolder() . '/controller.stub';

        $this->generate($variables, $template, $path);
    }

    private function generate(array $variables, string $template, string $path): void
    {
        if (!File::exists($template)) {
            throw new \RuntimeException('The template not found in path: ' . $template);
        }

        if (File::exists($path)) {
            throw new \RuntimeException('The model already exists: ' . $path);
        }

        $content = File::get($template);

        foreach ($variables as $name => $value) {
            $content = str_replace("{{ $name }}", $value, $content);
        }

        if (!file_exists(dirname($path))) {
            File::makeDirectory(dirname($path), 0777, true);
        }

        File::copy($template, $path);
        File::put($path, $content);
    }
}
