<?php
namespace MJanssen\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class DocsCreateCommand extends ContainerAwareCommand
{
    protected $applicationPath;
    protected $process;

    /**
     * @param $applicationPath
     */
    public function setApplicationPath($applicationPath)
    {
        $this->applicationPath = $applicationPath;
    }

    /**
     * @return mixed
     */
    public function getApplicationPath()
    {
        return $this->applicationPath;
    }


    /**
     * @param Process $process
     */
    public function setProcess(Process $process)
    {
        $this->process = $process;
    }

    /**
     * @return Filesystem
     */
    public function getProcess()
    {
        if(null === $this->process) {
            $this->setProcess(
                new Process(
                    sprintf('cd %s && php vendor/bin/swagger ./ -o ./api-docs -e vendor/doctrine:vendor/zircote:vendor/symfony:vendor/zendframework:vendor/jms',$this->getApplicationPath())
                )
            );
        }

        return $this->process;
    }

    /**
     * Configure the command
     */
    protected function configure()
    {
        $this->setName('docs:create')
             ->setDescription('Create API docs');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $docs = $this->getProcess();
        $docs->run();

        $output->writeln(sprintf("%s <info>success</info>", 'docs:created'));
    }
}