<?php
namespace PHPResqueBundle\Command;

use PHPResqueBundle\Resque\Status;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;

class UpdateCommand extends Command {
    
    protected function configure() {
        $this->setName('resque:update')
             ->addArgument('job_id', InputArgument::REQUIRED, 'The Job ID')
             ->addArgument('new_status', InputArgument::REQUIRED, 'New Status')
             ->setHelp("Set a new status to a Job.");
    }

    
    protected function execute(InputInterface $input, OutputInterface $output) {
        try {
            if (Status::update($input->getArgument('new_status'), $input->getArgument('job_id'))) {
                $output->write("Job updated !");
            } else {
                throw new \RuntimeException("Job could NOT updated.");
            }
        } catch (\RuntimeException $rue) {
            $output->write("ERROR while update job: {$rue->getMessage()}");
        }
    }
}
