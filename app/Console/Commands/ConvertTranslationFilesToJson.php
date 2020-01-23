<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class ConvertTranslationFilesToJson extends Command
{
    protected $file;
    protected $signature = 'trans:js';
    protected $sourcePath = 'resources/lang';
    protected $description = 'Convert Laravel PHP translation files to JSON to be used with Javascript.';

    public function __construct(Filesystem $file)
    {
        parent::__construct();

        $this->file = $file;
    }

    public function handle()
    {
        $style = new OutputFormatterStyle(null, null, ['bold']);
        $this->output->getFormatter()->setStyle('bold', $style);
        // Ask user for new Permission info
        $path = base_path($this->sourcePath);

        if (!$this->file->exists($path)) {
            throw new \Exception("${path} doesn't exists!");
        }

        foreach($this->file->directories($path) as $folder) {
            $messages = [];
            $lang = array_reverse(explode('/', $folder))[0];

            foreach ($this->file->allFiles($folder) as $file) {
                $extension = $file->getExtension();
                if ($extension != 'php' && $extension != 'json') continue;

                $pathName = $file->getRelativePathName();
                $filename = $file->getFilenameWithoutExtension();
                if (!isset($messages[$filename])) $messages[$filename] = [];

                $fullPath = $folder.DIRECTORY_SEPARATOR.$pathName;

                $messages[$filename] = array_merge($messages[$filename], (include $fullPath));
            }

            $destination = '/js/translations/packs/' . $lang . '.js';
            $content = 'window.translations = '.json_encode(Arr::dot(array_filter($messages))). ';';
            Storage::disk('public_assets')->put($destination, $content);

            $this->info('Language file for <bold>' . $lang . '</bold> created successfully in <bold>/public/' . ltrim($destination, '/') . '</bold>');
        }
    }
}
