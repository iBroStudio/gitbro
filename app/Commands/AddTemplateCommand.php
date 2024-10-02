<?php

namespace App\Commands;

use App\Services\TemplateService;
use IBroStudio\DataRepository\ValueObjects\GitSshUrl;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use LaravelZero\Framework\Commands\Command;

use function Laravel\Prompts\error;
use function Laravel\Prompts\info;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\note;
use function Laravel\Prompts\table;
use function Laravel\Prompts\text;

class AddTemplateCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'template';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Add template repository';

    /**
     * Execute the console command.
     */
    public function handle(TemplateService $templateService): int
    {
        intro('GITBRO:template');

        note('Current templates:');

        table(
            headers: ['Owner', 'Name'],
            rows: Arr::map($templateService->get()->toArray(), function (array $item) {
                return [Str::title($item['username']), $item['repository']];
            })
        );

        $url = text(
            label: 'Enter the Git SSH repository url',
            hint: 'ex: git@github.com:spatie/package-skeleton-laravel.git'
        );

        try {
            $sshUrl = GitSshUrl::from($url);
        } catch (\Exception $e) {
            error("{$url} is not a valid Git SSH url");

            return self::FAILURE;
        }

        if ($templateService->add($sshUrl)) {
            info('Template successfully added.');

            return self::SUCCESS;
        }

        return self::FAILURE;
    }
}
