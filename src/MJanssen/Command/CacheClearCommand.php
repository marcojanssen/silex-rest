<?php
namespace MJanssen\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class CacheClearCommand extends ContainerAwareCommand
{
    protected $cachePath;
    protected $finder;
    protected $filesystem;

    /**
     * @param $cachePath
     */
    public function setCachePath($cachePath)
    {
        $this->cachePath = $cachePath;
    }

    /**
     * @return mixed
     */
    public function getCachePath()
    {
        return $this->cachePath;
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
        $this->setName('cache:clear')
             ->setDescription('Clears the cache');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $finder = $this->getFinder();
        $finder->in($this->getCachePath());
        $finder->ignoreDotFiles(true);

        $filesystem = $this->getFilesystem();
        $filesystem->remove($finder);

        $output->writeln(sprintf("%s <info>success</info>", 'cache:clear'));
    }
}