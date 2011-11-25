<?php
namespace PHPResqueBundle\Command;

use Symfony\Component\Console\Input\InputOption;

use PHPResqueBundle\Resque\Status;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;

class StatusCommand extends Command {
    
    protected function configure() {
        $this->setName('resque:status')
             ->setDescription('Check Job status')
             ->addArgument('job_id', InputArgument::REQUIRED, 'Job ID')
             ->addOption("namespace", 'ns', InputOption::VALUE_OPTIONAL, 'Redis Namespace (prefix)')
             ->setHelp("Check a Job status");
    }
        
    protected function execute(InputInterface $input, OutputInterface $output) {
        $status = Status::check($input->getArgument('job_id'), $input->getOption('namespace'));
        $output->write($status);
    }
}

