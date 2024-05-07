<?php

declare(strict_types=1);

namespace Pavelmgn\RepoGenerator\Console\Commands;

use Illuminate\Support\Str;

class RepoGenerateCommand
{
    protected $signature = 'repo:generate {entityName: Entity name for generating objects}';

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
        return __DIR__ . "/../../../stubs/";
    }

    private function getVariables($name): array
    {
        $variables = [
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

    }

    private function makeSearch(array $variables): void
    {

    }

    private function makeRepository(array $variables): void
    {

    }

    private function makeResponse(array $variables): void
    {
    }

    private function makeRequests(array $variables): void
    {

    }

    private function makeController(array $variables): void
    {
    }
}
