<?php

namespace Hexters\Logat\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class LangGeneratorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logat:collect';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Collect language translation keys from source code into JSON files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Initialize data structure for all supported languages
        $languages = config('logat.locales');
        $translations = [];
        $existingKeys = [];

        // Collect all PHP files from configured source paths
        $files = collect([]);
        foreach (config('logat.sources', []) as $path) {
            $files = $files->merge(File::allFiles(base_path($path)));
        }

        $pattern = "/__\(['\"]([^'\"]+)['\"]/";

        // Process each file and find all translation key matches
        $files->each(function ($file) use ($pattern, &$translations, &$existingKeys) {
            $content = File::get($file->getPathname());
            preg_match_all($pattern, $content, $matches);

            // Add matched strings to the translations array
            foreach ($matches[1] as $key) {
                $existingKeys[$key] = true;
                if (! isset($translations[$key])) {
                    $translations[$key] = [
                        config('logat.default') => $key
                    ];
                }
            }
        });

        // Define output path for language JSON files
        $outputPath = lang_path('');
        File::ensureDirectoryExists($outputPath);

        foreach ($languages as $lang) {
            $langFilePath = "$outputPath/$lang.json";

            // Load existing JSON file if available
            $existingTranslations = File::exists($langFilePath)
                ? json_decode(File::get($langFilePath), true)
                : [];

            // Merge existing with newly found translations
            $mergedTranslations = [];

            foreach ($existingTranslations as $key => $value) {
                if (isset($existingKeys[$key])) {
                    // Preserve old value if the key still exists in code
                    $mergedTranslations[$key] = $value;
                }
            }

            foreach ($translations as $key => $data) {
                if (! isset($mergedTranslations[$key])) {
                    // Add new key with empty or default translation
                    $mergedTranslations[$key] = $data[$lang] ?? '';
                }
            }

            // Save final merged translations to JSON file
            File::put($langFilePath, json_encode($mergedTranslations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }

        $this->info('Language files generated successfully.');
    }
}
