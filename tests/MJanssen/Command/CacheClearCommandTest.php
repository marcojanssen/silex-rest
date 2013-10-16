<?php
namespace MJanssen\Command;

use MJanssen\Command\CacheClearCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class CacheClearCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Check if command can be run
     */
    public function testExecute()
    {
        $application = new Application();
        $cacheClear  = new CacheClearCommand();

        $filesystem = $this->getMock('Symfony\Component\Filesystem\Filesystem');
        $finder     = $this->getMock('Symfony\Component\Finder\Finder', array('in', 'ignoreDotFiles'));

        $cacheClear->setFilesystem($filesystem);
        $cacheClear->setFinder($finder);
        $cacheClear->setCachePath('/tmp');

        $application->add($cacheClear);

        $command = $application->find('cache:clear');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));
    }

    /**
     * Check if default Finder is Symfony Finder component
     */
    public function testFinder()
    {
        $cacheClear = new CacheClearCommand();
        $this->assertTrue(($cacheClear->getFinder() instanceof Finder));
    }

    /**
     * Check if default Finder is Symfony Filesystem component
     */
    public function testFilesystem()
    {
        $cacheClear = new CacheClearCommand();
        $this->assertTrue(($cacheClear->getFilesystem() instanceof Filesystem));
    }
}