# Logat — Laravel Language Extractor

**Logat** is a Laravel package that helps you extract all translation keys from your application’s source code into JSON language files. It scans your Blade and PHP files for `__('...')` calls and compiles the results automatically.

Designed to simplify your localization workflow, especially when managing multi-language projects.

---

## Installation

Install via Composer:

```bash
composer require hexters/logat --dev
```

This package is meant for development use only and should not be required in production environments.

---

## Configuration

If you want to customize the behavior, you can publish the config file:

```bash
php artisan vendor:publish --provider="Hexters\Logat\LogatServiceProvider"
```

This will publish a file to `config/logat.php` with the following default contents:

```php
return [

    'default' => 'id',

    'locales' => ['en', 'ms', 'id', 'ja'],

    'sources' => [
        'resources/views',
        'app',
    ],

];
```

You can adjust the default locale, supported languages, and which folders to scan for translation keys.

---

## Usage

To scan your application and generate/update translation files, run:

```bash
php artisan logat:collect
```

This command will:

* Search for all `__('...')` keys in the specified source directories
* Merge with any existing translation files
* Save the output in the `lang` directory as individual JSON files for each language

---

## Output

After running the command, you’ll get files like:

```
lang/
├── en.json
├── id.json
├── ja.json
└── ms.json
```

Each file will contain the translation keys found in your app. Any untranslated key will have an empty string as its value.

Example:

```json
{
    "Dashboard": "Dashboard",
    "User Account": "User Account"
}
```

---

## Best Practices

It’s recommended to commit the generated JSON files to version control. This helps keep your translation keys in sync across teams, environments, and CI pipelines.

---

## License

This package is open source and licensed under the [MIT License](LICENSE).