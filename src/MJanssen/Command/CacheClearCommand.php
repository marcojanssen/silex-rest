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
        $finder = Finder::create()->in($this->getCachePath())->notName('.gitkeep');

        $filesystem = new Filesystem();
        $filesystem->remove($finder);

        $output->writeln(sprintf("%s <info>success</info>", 'cache:clear'));
    }
}