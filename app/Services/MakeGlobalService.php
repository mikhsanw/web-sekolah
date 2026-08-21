<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeGlobalService
{
    public PathsAndNamespacesService $pathsAndNamespacesService;
    public function __construct(PathsAndNamespacesService $pathsAndNamespacesService)
    {
        $this->pathsAndNamespacesService = $pathsAndNamespacesService;
    }

    public function getNamingConvention($crudName): array
    {
        return
        [
            'plural_name'       => Str::plural($crudName),
            'singular_name'     => ucwords($crudName), //Str::singular($crudName),
            'singular_low_name' => strtolower($crudName), //Str::singular(strtolower($crudName)),
            'plural_low_name'   => Str::plural(strtolower($crudName)),
            'table_name'        => Str::plural(Str::snake($crudName))
        ];
    }

    public function parseColumns($columns)
    {
        // if the columns argument is empty, we create an empty array else we explode on the comma
        return ($columns=='') ? [] : explode(',', $columns);
    }

    public function buildValidationRule(string $columnDefinition): string
    {
        $type = array_map('trim', explode(':', $columnDefinition));
        $fieldType = $type[1] ?? 'string';

        return match ($fieldType) {
            'string' => 'required|string|max:255',
            'text' => 'required|string',
            'integer' => 'required|integer',
            'bigInteger', 'numeric', 'decimal', 'float', 'double' => 'required|numeric',
            'boolean' => 'required|boolean',
            'date' => 'required|date',
            'datetime', 'timestamp' => 'required|date',
            'time' => 'required|date_format:H:i',
            'email' => 'required|email',
            'uuid' => 'required|uuid',
            'json', 'array' => 'required|array',
            'image' => 'required|image',
            'file' => 'required|file',
            'relasi' => 'required|uuid',
            'select' => 'required',
            default => 'required',
        };
    }

    public function buildPermissionNames(string $resource): array
    {
        return collect(config('master.app.level', []))
            ->map(fn ($level) => $resource . ' ' . $level)
            ->values()
            ->all();
    }

    public function buildPermissionArrayLines(string $resource): string
    {
        return collect($this->buildPermissionNames($resource))
            ->map(fn ($permission) => "        '" . $permission . "',\n")
            ->implode('');
    }

    public function buildMenuSeederEntryLines(array $namingConvention): string
    {
        $title = Str::headline($namingConvention['singular_name']);

        return "            [\n"
            . "                'title' => '" . $title . "',\n"
            . "                'subtitle' => '" . $title . "',\n"
            . "                'code' => '" . $namingConvention['plural_low_name'] . "',\n"
            . "                'url' => '" . $namingConvention['plural_low_name'] . "',\n"
            . "                'model' => 'App\\\\Models\\\\" . $namingConvention['singular_name'] . "',\n"
            . "                'icon' => 'fas fa-circle',\n"
            . "                'type' => 'backend',\n"
            . "                'show' => true,\n"
            . "                'active' => true,\n"
            . "                'sort' => 999,\n"
            . "                'children' => [],\n"
            . "            ],\n";
    }

    public function injectBeforeMarker(string $filePath, string $marker, string $content): bool
    {
        if (! File::exists($filePath)) {
            return false;
        }

        $file = File::get($filePath);

        if (str_contains($file, trim($content))) {
            return false;
        }

        if (! str_contains($file, $marker)) {
            return false;
        }

        File::put($filePath, str_replace($marker, $content . $marker, $file));

        return true;
    }

    public function removeExactContent(string $filePath, string $content): bool
    {
        if (! File::exists($filePath)) {
            return false;
        }

        $file = File::get($filePath);

        if (! str_contains($file, $content)) {
            return false;
        }

        File::put($filePath, str_replace($content, '', $file));

        return true;
    }

    public function cleanLastLineBreak($string)
    {
        return rtrim($string, "\n");
    }

    public function getCommentableNamingConvention($commentableName): array
    {
        return
        [
            'controller_name'            => Str::plural(Str::studly($commentableName)),
            'model_name'                 => ucwords(Str::studly($commentableName)), //Str::singular(Str::studly($commentableName)),
            'singular_low_variable_name' => ucwords(Str::camel(Str::lower($commentableName))), //Str::singular(Str::camel(Str::lower($commentableName))),
            'plural_low_variable_name'   => Str::plural(Str::camel(Str::lower($commentableName))),
            'table_name'                 => Str::plural(Str::snake($commentableName)),
        ];
    }

    public function getAllViewsFiles()
    {
        return glob(resource_path("views".DIRECTORY_SEPARATOR."*".DIRECTORY_SEPARATOR."*.blade.php"));
    }

    public function getCommentableParentModelConvention($commentableParentName): array
    {
        return
        [
            'singular_low_variable_name' => ucwords(Str::camel(Str::lower($commentableParentName))),// Str::singular(Str::camel(Str::lower($commentableParentName))),
            'plural_low_variable_name'   => Str::plural(Str::camel(Str::lower($commentableParentName))),
        ];
    }
}
