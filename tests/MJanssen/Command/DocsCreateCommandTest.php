<?php
namespace MJanssen\Command;

use MJanssen\Command\DocsCreateCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Process\Process;

class DocsCreateCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Check if command can be run
     */
    public function testExecute()
    {
        $application = new Application();
        $docsCreate  = new DocsCreateCommand();

        $process = new Process('');
        $docsCreate->setProcess($process);

        $application->add($docsCreate);

        $command = $application->find('docs:create');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));
    }

    /**
     * Check if application path can be set
     */
    public function testApplicationPath()
    {
        $applicationPath  = '/tmp';

        $docsCreate = new DocsCreateCommand();
        $docsCreate->setApplicationPath($applicationPath);
        $this->assertEquals($applicationPath, $docsCreate->getApplicationPath());
    }

    /**
     * Check if default Process is Symfony Process component
     */
    public function testProcess()
    {
        $logClear = new DocsCreateCommand();
        $this->assertTrue(($logClear->getProcess() instanceof Process));
    }
}