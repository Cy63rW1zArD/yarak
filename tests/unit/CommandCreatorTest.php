<?php

namespace Yarak\tests\unit;

use Yarak\Console\Output\Logger;

class CommandCreatorTest extends \Codeception\Test\Unit
{
    /**
     * Setup the class.
     */
    public function setUp()
    {
        parent::setUp();

        $this->tester->setUp();
    }

    /**
     * @test
     */
    public function command_creator_creates_directory_structure_if_not_present()
    {
        $this->tester->removeCommandsDirectory();
        
        $commandsDir = $this->tester->getConfig()->getCommandsDirectory();

        $this->assertFileNotExists($commandsDir);

        $path = $this->tester
            ->getCommandCreator()
            ->create('DoSomething');

        $this->assertFileExists($path);
    }

    /**
     * @test
     */
    public function command_creator_inserts_correct_class_name()
    {
        $path = $this->tester
            ->getCommandCreator()
            ->create('DoSomething');

        $data = file_get_contents($path);

        $this->assertContains('DoSomething', $data);
    }

    /**
     * @test
     */
    public function seeder_creator_outputs_success_message()
    {
        $logger = new Logger();

        $path = $this->tester
            ->getCommandCreator($logger)
            ->create('DoSomething');

        $this->assertCount(1, $logger->getLog());

        $this->assertTrue(
            $logger->hasMessage('<info>Successfully created command DoSomething.</info>')
        );
    }
}