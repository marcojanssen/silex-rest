<?php
namespace MJanssen\Command;

use MJanssen\Command\CacheClearCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class LogClearCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Check if command can be run
     */
    public function testExecute()
    {
        $application = new Application();
        $logClear    = new LogClearCommand();

        $filesystem = $this->getMock('Symfony\Component\Filesystem\Filesystem');
        $finder     = $this->getMock('Symfony\Component\Finder\Finder', array('in', 'ignoreDotFiles'));

        $logClear->setFilesystem($filesystem);
        $logClear->setFinder($finder);
        $logClear->setLogPath('/tmp');

        $application->add($logClear);

        $command = $application->find('log:clear');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));
    }

    /**
     * Check if log path can be set
     */
    public function testLogPath()
    {
        $logClear = new LogClearCommand();
        $logPath  = '/tmp';
        $logClear->setLogPath($logPath);
        $this->assertEquals($logPath, $logClear->getLogPath());
    }

    /**
     * Check if default Finder is Symfony Finder component
     */
    public function testFinder()
    {
        $logClear = new LogClearCommand();
        $this->assertTrue(($logClear->getFinder() instanceof Finder));
    }

    /**
     * Check if default Finder is Symfony Filesystem component
     */
    public function testFilesystem()
    {
        $logClear = new LogClearCommand();
        $this->assertTrue(($logClear->getFilesystem() instanceof Filesystem));
    }
}