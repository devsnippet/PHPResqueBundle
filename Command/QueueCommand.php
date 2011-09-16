<?php
namespace PHPResqueBundle\Command;

use PHPResqueBundle\Resque\Queue;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;

class QueueCommand extends Command {
    const NEW_LINE = true;
    
    protected function configure() {
        parent::configure();
        $this->setName('resque:queue')
             ->addArgument('job_name', InputArgument::REQUIRED, 'Resque Job name');
    }
    
    
    protected function execute(InputInterface $input, OutputInterface $output) {
        $job = new Queue($input->getArgument('job_name'));
        $output->write('Job as captured. Job id ' . $job);
    }
}
