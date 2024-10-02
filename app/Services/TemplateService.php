<?php

namespace App\Services;

use Archetype\Facades\PHPFile;
use IBroStudio\DataRepository\ValueObjects\GitSshUrl;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;

class TemplateService
{
    protected Collection $templates;

    public function __construct()
    {
        $this->templates = collect(Config::get('git.templates'))
            ->mapInto(GitSshUrl::class);
    }

    public function get(): Collection
    {
        return $this->templates;
    }

    public function add(GitSshUrl $sshUrl): bool
    {
        $config = PHPFile::load(Config::get('app.homeConfigDirectory').'git.php');

        $templates = $config->astQuery()
            ->arrayItem()
            ->where('key->value', 'templates')
            ->value
            ->first();

        $templates->items = [
            ...$templates->items,
            new ArrayItem(
                new String_($sshUrl->value())
            ),
        ];

        $config->astQuery()
            ->return()->array()->arrayItem()
            ->where('key->value', 'templates')
            ->replaceProperty('value', $templates)
            ->commit()
            ->end()
            ->save();

        return true;
    }
}
