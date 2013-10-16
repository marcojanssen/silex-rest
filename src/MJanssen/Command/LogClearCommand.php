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
    protected $finder;
    protected $filesystem;

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

    /**
     * @param Filesystem $filesystem
     */
    public function setFilesystem(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @return Filesystem
     */
    public function getFilesystem()
    {
        if(null === $this->filesystem) {
            $this->setFilesystem(
                new Filesystem()
            );
        }

        return $this->filesystem;
    }

    /**
     * @param Finder $finder
     */
    public function setFinder(Finder $finder)
    {
        $this->finder = $finder;
    }

    /**
     * @return Finder
     */
    public function getFinder()
    {
        if(null === $this->finder) {
            $this->setFinder(
                Finder::create()
            );
        }

        return $this->finder;
    }

    /**
     * Configures the command
     */
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
        $finder = $this->getFinder();
        $finder->in($this->getLogPath());
        $finder->ignoreDotFiles(true);

        $filesystem = $this->getFilesystem();
        $filesystem->remove($finder);

        $output->writeln(sprintf("%s <info>success</info>", 'log:clear'));
    }
}