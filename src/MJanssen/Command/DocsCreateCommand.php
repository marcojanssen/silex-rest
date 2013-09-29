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
        $docs = new Process(sprintf('cd %s && php vendor/bin/swagger ./ -o ./docs -e vendor/doctrine:vendor/zircote:vendor/symfony:vendor/zendframework:vendor/jms',$app['app_path']));
        $docs->run();

        if ($docs->isSuccessful()) {
            $output->writeln(sprintf("%s <info>success</info>", 'docs:created'));
            return true;
        }

        return $docs;
    }
}