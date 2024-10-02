<?php

use App\Services\TemplateService;
use IBroStudio\DataRepository\ValueObjects\GitSshUrl;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

it('can instantiate template service', function () {
    expect(new TemplateService)->toBeInstanceOf(TemplateService::class);
});

it('can retrieve templates from config', function () {
    $templates = (new TemplateService)->get();

    expect($templates)->toBeCollection()
        ->and($templates->first())->toBeInstanceOf(GitSshUrl::class);
});

it('can add a new template to the config', function () {
    (new TemplateService)
        ->add(GitSshUrl::from('git@github.com:iBroStudio/astro-skeleton.git'));

    Config::set('git', array_merge(
        Config::get('git', []),
        require Config::get('app.homeConfigDirectory').'git.php'
    ));

    expect(
        (new TemplateService)
            ->get()
            ->filter(function (GitSshUrl $sshUrl) {
                return $sshUrl->value() === 'git@github.com:iBroStudio/astro-skeleton.git';
            })
            ->count()
    )->toBe(1);

    File::copy(
        base_path('vendor/ibrostudio/laravel-git/config/git.php'),
        Config::get('app.homeConfigDirectory').'git.php'
    );
});
