<?php declare(strict_types=1);

namespace Blinktag\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Formatter\OutputFormatter;

class FindTodos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'find:todos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find TODO,FIXME comments in your code';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    { 
        $this->findPhpFiles()
             ->each(function ($file) {
                // Tokenize file
                $tokens = token_get_all(file_get_contents($file->getPathName()));

                // Find all comment tokens in the file
                $comments = $this->findTodoComments($tokens);
                
                if (count($comments) === 0) {
                    return;
                }

                $output = new ConsoleOutput();
                $output->setFormatter(new OutputFormatter(true));

                // Output file path
                $output->writeln("<info>{$file->getRealPath()}</info>");

                // Output all matches inside of the file
                $comments->each(function ($token) use ($output) {
                    $this->printTodoLine($output, $token);
                });
                
                $output->writeLn('');
             });
    }

    /**
     * Determine if a token represents a comment
     *
     * @param array $token Token array from token_get_all()
     * @return boolean
     */
    public function isTokenAComment(array $token): bool
    {
        return ($token[0] === T_COMMENT || $token[0] !== T_DOC_COMMENT);
    }

    /**
     * Find all user PHP files in the our project
     *
     * @return \Illuminate\Support\Collection
     */
    public function findPhpFiles(): Collection
    {
        $finder = new Finder();
        $finder->files()
               ->name("*.php")
               ->ignoreDotFiles(true)
               ->ignoreVcs(true)
               ->ignoreUnreadableDirs(true)
               ->in(base_path() . '/app');
        return collect($finder);
    }

    /**
     * Return all comment tokens with todos as a collection
     *
     * @param array $tokens
     * @return Collection
     */
    public function findTodoComments(array $tokens): Collection
    {
        return collect($tokens)
                    ->reject(function ($token) {

                        // No semi-colon tokens
                        if (!is_array($token)) {
                            return true;
                        }

                        // Verify the token is a comment and we found a matching string inside of it
                        return (
                            $this->isTokenAComment($token) === false 
                            || count($this->findTodos($token)) === 0
                        );

                    });
    }

    /**
     * Return all lines matching the strings we are looking for
     *
     * @param array $token Token array from token_get_all()
     * @return array
     */
    public function findTodos(array $token): array
    {
        $strings = config('findtodos.find_strings', 'TODO|FIXME');
        $regexp = '/(\/\/|\* )\s+?(?<prefix>' . $strings . '):\s+(?<comment>.+?)\n$/i';
        preg_match($regexp, $token[1], $matches);
        return $matches;
    }

    /**
     * Print the todo comment, and file line number to the console
     *
     * @param ConsoleOutput $output
     * @param array $token
     * @return void
     */
    public function printTodoLine(ConsoleOutput $output, array $token)
    {
        $todo = $this->findTodos($token);

        // Default color is yellow
        $style = 'comment'; 

        // Fixme comments are white text on red background
        if ($todo['prefix'] == 'FIXME') {
            $style = 'error';
        }

        $line = sprintf(
            "<%s>%5s</%s> Line %d: %s",
            $style,
            $todo['prefix'],
            $style,
            $token[2],
            $todo['comment']
        );
        $output->writeln($line);
    }
}
