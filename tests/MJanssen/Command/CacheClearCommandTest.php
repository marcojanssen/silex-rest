<?php
namespace MJanssen\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use MJanssen\Command\CacheClearCommand;

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
     * Check if cache clear path is set
     */
    public function testCachePath()
    {
        $cacheClear = new CacheClearCommand();
        $cachePath  = '/tmp';

        $cacheClear->setCachePath($cachePath);
        $this->assertEquals($cachePath, $cacheClear->getCachePath());
    }
}