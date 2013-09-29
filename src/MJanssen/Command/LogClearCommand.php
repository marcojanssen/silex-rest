<?php
namespace MJanssen\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class LogClearCommand extends ContainerAwareCommand
{
    protected $logPath;

    /**
     * @param $logPath
     */
    public function setLogPath($logPath)
    {
        $this->logPath = $logPath;
    }

    /**
     * @return mixed
     */
    public function getLogPath()
    {
        return $this->logPath;
    }

    protected function configure()
    {
        $this->setName('log:clear')
            ->setDescription('Clears the logs');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $finder = Finder::create()->in($this->getLogPath())->notName('.gitkeep');

        $filesystem = new Filesystem();
        $filesystem->remove($finder);

        $output->writeln(sprintf("%s <info>success</info>", 'log:clear'));
    }
}