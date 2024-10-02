<?php

use IBroStudio\NeonConfig\NeonConfigServiceProvider;
use Spatie\LaravelData\LaravelDataServiceProvider;

return [
    App\Providers\AppServiceProvider::class,
    Illuminate\Filesystem\FilesystemServiceProvider::class,
    IBroStudio\DataRepository\DataRepositoryServiceProvider::class,
    IBroStudio\Git\GitServiceProvider::class,
    NeonConfigServiceProvider::class,
    GrahamCampbell\GitHub\GitHubServiceProvider::class,
    LaravelDataServiceProvider::class,
    MichaelRubel\ValueObjects\ValueObjectServiceProvider::class,
    MichaelRubel\EnhancedContainer\LecServiceProvider::class,
    Archetype\ServiceProvider::class,
];
