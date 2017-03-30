<?php

namespace Yarak\Console;

use Yarak\Config\Config;
use Yarak\Helpers\Filesystem;
use Yarak\Console\Output\Output;
use Yarak\Exceptions\WriteError;

class CommandCreator
{
    use Filesystem;

    /**
     * Yarak config.
     *
     * @var Config
     */
    protected $config;

    /**
     * Output strategy.
     *
     * @var Output
     */
    protected $output;

    /**
     * Construct.
     *
     * @param Config $config
     * @param Output $output
     */
    public function __construct(Config $config, Output $output)
    {
        $this->config = $config;
        $this->output = $output;
    }

    public function create($name)
    {
        if (class_exists($name)) {
            throw WriteError::classExists($name);
        }

        $commandsDir = $this->config->getCommandsDirectory();

        $this->makeDirectoryStructure([$commandsDir]);

        $this->writeFile(
            $path = $commandsDir.$name.'.php',
            $this->getStub($name)
        );

        $this->output->writeInfo("Successfully created command {$name}.");

        return $path;
    }

    /**
     * Get the stub file and insert name.
     *
     * @param  string $name
     *
     * @return string
     */
    protected function getStub($name)
    {
        $stub = file_get_contents(__DIR__.'/Stubs/command.stub');

        return str_replace('CLASSNAME', $name, $stub);
    }
}