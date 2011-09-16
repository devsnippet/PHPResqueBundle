<?php
namespace PHPResqueBundle\Command;

use PHPResqueBundle\PHPResqueBundle;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;


class DaemonCommand extends Command {

    protected function configure() {
        $this->setName('resque:worker')
             ->addArgument('queue', InputArgument::OPTIONAL, 'Queue name', '*')
             ->addOption('log', 'l', InputOption::VALUE_OPTIONAL, 'Verbose mode')
             ->addOption('interval', 'i', InputOption::VALUE_OPTIONAL, 'Daemon check interval (in seconds)', 5)
             ->addOption('forkCount', 'f', InputOption::VALUE_OPTIONAL, 'Fork count instances', 1);
    }
    
    protected function execute(InputInterface $input, OutputInterface $output) {
        $phpresque = new PHPResqueBundle();
        $phpresque->defineQueue($input->getArgument('queue'));
        $phpresque->verbose($input->getOption('log'));
        $phpresque->setInterval($input->getOption('interval'));
        $phpresque->forkInstances($input->getOption('forkCount'));
        $phpresque->daemon();
    }
}
