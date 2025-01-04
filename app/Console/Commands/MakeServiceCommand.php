<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeServiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name : The name of the service (e.g., User/UserService)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class in the app/Services directory';

    /**
     * Filesystem instance for file handling.
     *
     * @var Filesystem
     */
    protected Filesystem $files;

    /**
     * Constructor.
     *
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Extract service name and directory structure
        $name = $this->argument('name');
        [$serviceName, $directory] = $this->parseName($name);

        // Create the necessary directory structure
        $this->createDirectoryIfNotExists($directory);

        // Define the path for the service file
        $filePath = $directory . DIRECTORY_SEPARATOR . $serviceName . 'Service.php';

        // Create the service class file
        $this->createServiceFile($serviceName, $filePath);
    }

    /**
     * Parse the input name to extract service name and directory.
     *
     * @param string $name
     * @return array [serviceName, directory]
     */
    protected function parseName(string $name): array
    {
        $pathParts = explode('/', $name);
        $serviceName = array_pop($pathParts);  // Last part is the service name
        $subdirectories = implode(DIRECTORY_SEPARATOR, $pathParts);  // Remaining is the subdirectory path

        $baseDirectory = app_path('Services');
        $directory = $baseDirectory . ($subdirectories ? DIRECTORY_SEPARATOR . $subdirectories : '');

        return [$serviceName, $directory];
    }

    /**
     * Create the necessary directory structure if it doesn't exist.
     *
     * @param string $directory
     */
    protected function createDirectoryIfNotExists(string $directory): void
    {
        // Normalize path separators for cross-platform compatibility
        $directory = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $directory);

        if (!$this->files->exists($directory)) {
            $this->files->makeDirectory($directory, 0777, true, true);
            $this->info('Created directory: ' . $directory);
        }
    }

    /**
     * Create the service class file with a stub.
     *
     * @param string $serviceName
     * @param string $filePath
     */
    protected function createServiceFile(string $serviceName, string $filePath): void
    {
        // Check if the file already exists
        if ($this->files->exists($filePath)) {
            $this->error('Service ' . $serviceName . ' already exists!');
            return;
        }

        // Prepare the file content using a stub
        $stub = $this->getStub();
        $stub = str_replace('{{serviceName}}', $serviceName . 'Service', $stub);
        $stub = str_replace('{{namespace}}', $this->getNamespace($filePath), $stub);

        // Write the new file
        $this->files->put($filePath, $stub);
        $this->info('Service ' . $serviceName . ' created successfully at ' . $filePath);
    }

    /**
     * Get the namespace for the service class based on the file path.
     *
     * @param string $filePath
     * @return string
     */
    protected function getNamespace(string $filePath): string
    {
        $relativePath = Str::after($filePath, app_path() . DIRECTORY_SEPARATOR);
        $namespace = str_replace(DIRECTORY_SEPARATOR, '\\', dirname($relativePath));
        return 'App\\' . $namespace;
    }

    /**
     * Get the stub content for the service file.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return <<<'EOT'
<?php

namespace {{namespace}};

class {{serviceName}}
{
    // Add your service logic here
}
EOT;
    }
}
