<?php

namespace Hexters\Logat\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class LangCleanCommand extends Command
{
    protected $signature = 'logat:clean';

    protected $description = 'Remove translation keys from JSON files that are no longer found in source code';

    public function handle()
    {
        $languages = config('logat.locales', []);
        $defaultLang = config('logat.default', 'en');

        $existingKeys = [];

        // Kumpulkan semua key dari source code
        $files = collect();
        foreach (config('logat.sources', []) as $path) {
            $files = $files->merge(File::allFiles(base_path($path)));
        }

        $files->each(function ($file) use (&$existingKeys) {
            $content = File::get($file->getPathname());
            foreach(config('logat.functions', ['__']) as $function) {
                $pattern = "/{$function}\(['\"]([^'\"]+)['\"]/";
                preg_match_all($pattern, $content, $matches);
                
                foreach ($matches[1] as $key) {
                    $existingKeys[$key] = true;
                }
            }
        });

        // Path ke folder lang
        $outputPath = lang_path('');

        foreach ($languages as $lang) {
            $langFilePath = "$outputPath/$lang.json";

            if (!File::exists($langFilePath)) {
                continue;
            }

            $translations = json_decode(File::get($langFilePath), true);

            // Filter hanya key yang masih ada di source code
            $cleaned = collect($translations)->filter(function ($value, $key) use ($existingKeys) {
                return isset($existingKeys[$key]);
            })->all();

            File::put($langFilePath, json_encode($cleaned, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            $this->info("Cleaned: $langFilePath (" . count($cleaned) . " keys)");
        }

        $this->info('All language files cleaned successfully.');
    }
}
