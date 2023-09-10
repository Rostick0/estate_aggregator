<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Util extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:util {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $folder = 'app/Utils';

        if (!(file_exists($folder) || is_dir($folder))) mkdir('App\Utils');

        $name = $this->argument('name');
        $fileName = ucfirst($name) . '.php';
        $filePath = app_path('Utils/' . $fileName);
        $namespace = 'App\Utils';

        $stub = <<<EOF
        <?php

        namespace $namespace;

        class $name
        {
            // 
        }
        EOF;

        file_put_contents($filePath, $stub);

        $this->info("Util file $fileName created successfully!");
    }
}
