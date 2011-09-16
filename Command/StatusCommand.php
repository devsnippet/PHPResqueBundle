<?php
namespace PHPResqueBundle\Command;

use PHPResqueBundle\Resque\Status;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;

class StatusCommand extends Command {
    
    protected function configure() {
        $this->setName('resque:status')
             ->addArgument('job_id', InputArgument::REQUIRED, 'Job ID');
    }
        
    protected function execute(InputInterface $input, OutputInterface $output) {
        $status = Status::check($input->getArgument('job_id'));
        $output->write("Job status in queue: {$status}");
    }
}

