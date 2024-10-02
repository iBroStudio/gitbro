# Gitbro

## Installation
```bash
composer global require ibrostudio/gitbro
```

## Configuration
To communicate with Github, you need to register in the config a [Github Personal Access Token](https://github.com/settings/tokens):
```bash
gitbro config
```

## Init a new project
```bash
gitbro init
```
This will create a new Github repository, following your parameters, as visibility or ownership, and then clone it locally.

### Using templates repositories
You can use a template for your project. By default, [Spatie Package Skeleton Laravel](https://github.com/spatie/package-skeleton-laravel) and [Filament PHP Plugin Skeleton](https://github.com/filamentphp/plugin-skeleton) are available, but you can add more using the following command:
```bash
gitbro template
```

## Conventional Commits
This app follows the [Conventional Commits specification](https://www.conventionalcommits.org/en/v1.0.0/).
A commit type will prefix your message to help history comprehension and will be used by the CHANGELOG generator.
```bash
gitbro commit
```

### Running scripts before commit
You can automatically run tests or format code scripts before each commit:

Create a `gitbro.neon` file at the root of your project with:
```neon
scripts:
    format-code:
        - 'vendor/bin/pint'
        - 'npx prettier . --write'
    test-code:
        - 'composer test'
```

## Pull, push, sync
- `gibro pull` for ***git pull origin main --rebase***
- `gibro push` for ***git push origin main***
- `gibro sync` will execute ***gitbro pull*** and then ***gitbro push***

## Releases
You can easily perform a release creation by running:
```bash
gitbro release
```
This will:
- define the version, following the [semantic versionning](https://semver.org/)
- bump the new version in composer.json and/or package.json if used
- generate a note section in your CHANGELOG
- create the release on Github

## Testing

```bash
composer test
```
