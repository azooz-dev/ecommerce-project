<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeRepositoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name : The name of the repository (e.g., User/UserRepository)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository class in the app/Repositories directory';

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
        // Extract repository name and directory structure
        $name = $this->argument('name');
        [$repositoryName, $directory] = $this->parseName($name);

        // Create the necessary directory structure
        $this->createDirectoryIfNotExists($directory);

        // Define the path for the repository file
        $filePath = $directory . DIRECTORY_SEPARATOR . $repositoryName . 'Repository.php';

        // Create the repository class file
        $this->createRepositoryFile($repositoryName, $filePath);

        $this->info('Repository created successfully!');
    }

    /**
     * Parse the input name to extract repository name and directory.
     *
     * @param string $name
     * @return array [repositoryName, directory]
     */
    protected function parseName(string $name): array
    {
        $pathParts = explode('/', $name);
        $repositoryName = array_pop($pathParts);  // Last part is the repository name
        $subdirectories = implode(DIRECTORY_SEPARATOR, $pathParts);  // Remaining is the subdirectory path

        $baseDirectory = app_path('Repositories');
        $directory = $baseDirectory . ($subdirectories ? DIRECTORY_SEPARATOR . $subdirectories : '');

        return [$repositoryName, $directory];
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
     * Create the repository class file with a stub.
     *
     * @param string $repositoryName
     * @param string $filePath
     */
    protected function createRepositoryFile(string $repositoryName, string $filePath): void
    {
        // Check if the file already exists
        if ($this->files->exists($filePath)) {
            $this->error('Repository ' . $repositoryName . ' already exists!');
            return;
        }

        // Prepare the file content using a stub
        $stub = $this->getRepositoryStub();
        $stub = str_replace('{{repositoryName}}', $repositoryName . 'Repository', $stub);
        $stub = str_replace('{{namespace}}', $this->getNamespace($filePath), $stub);

        // Write the new file
        $this->files->put($filePath, $stub);
        $this->info('Repository ' . $repositoryName . ' created successfully at ' . $filePath);
    }

    /**
     * Get the namespace for the repository based on the file path.
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
     * Get the stub content for the repository file.
     *
     * @return string
     */
    protected function getRepositoryStub(): string
    {
        return <<<'EOT'
<?php

namespace {{namespace}};

class {{repositoryName}}
{
    // Add your repository logic here.
}
EOT;
    }
}
