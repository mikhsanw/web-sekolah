<?php

use App\Services\MakeGlobalService;
use Tests\TestCase;

uses(TestCase::class);

it('maps common column definitions to reusable validation rules', function () {
    $service = app(MakeGlobalService::class);

    expect($service->buildValidationRule('name:string'))->toBe('required|string|max:255');
    expect($service->buildValidationRule('description:text'))->toBe('required|string');
    expect($service->buildValidationRule('is_active:boolean'))->toBe('required|boolean');
    expect($service->buildValidationRule('category_id:relasi:Category'))->toBe('required|uuid');
});

it('builds reusable permission names and permission array lines', function () {
    $service = app(MakeGlobalService::class);

    expect($service->buildPermissionNames('posts'))->toBe([
        'posts list',
        'posts create',
        'posts edit',
        'posts delete',
    ]);

    expect($service->buildPermissionArrayLines('posts'))->toContain("'posts list'");
    expect($service->buildPermissionArrayLines('posts'))->toContain("'posts delete'");
});

it('builds a reusable menu seeder entry for generated backend modules', function () {
    $service = app(MakeGlobalService::class);

    $entry = $service->buildMenuSeederEntryLines([
        'singular_name' => 'Post',
        'plural_low_name' => 'posts',
    ]);

    expect($entry)->toContain("'title' => 'Post'");
    expect($entry)->toContain("'code' => 'posts'");
    expect($entry)->toContain("'model' => 'App\\\\Models\\\\Post'");
    expect($entry)->toContain("'sort' => 999");
});

it('injects generated content before a marker only once', function () {
    $service = app(MakeGlobalService::class);
    $tempFile = tempnam(sys_get_temp_dir(), 'crudgen-route-');

    file_put_contents($tempFile, "before\n//marker\nafter\n");

    $firstInsert = $service->injectBeforeMarker($tempFile, '//marker', "generated\n");
    $secondInsert = $service->injectBeforeMarker($tempFile, '//marker', "generated\n");
    $content = file_get_contents($tempFile);

    expect($firstInsert)->toBeTrue();
    expect($secondInsert)->toBeFalse();
    expect(substr_count($content, 'generated'))->toBe(1);

    unlink($tempFile);
});

it('removes exact generated content from a file', function () {
    $service = app(MakeGlobalService::class);
    $tempFile = tempnam(sys_get_temp_dir(), 'crudgen-remove-');

    file_put_contents($tempFile, "alpha\ngenerated\nomega\n");

    $removed = $service->removeExactContent($tempFile, "generated\n");
    $content = file_get_contents($tempFile);

    expect($removed)->toBeTrue();
    expect($content)->not->toContain('generated');

    unlink($tempFile);
});
