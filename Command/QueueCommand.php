<?php
namespace PHPResqueBundle\Command;

use PHPResqueBundle\Resque\Queue;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;

class QueueCommand extends Command {
    
    protected function configure() {
        parent::configure();
        $this->setName('resque:queue')
             ->addArgument('job_class_namespace', InputArgument::REQUIRED, 'Resque Job Class name (with namespace)')
             ->addArgument('queue_name', InputArgument::OPTIONAL, 'Queue name', 'default');
    }
        
    protected function execute(InputInterface $input, OutputInterface $output) {
        $job = Queue::add($input->getArgument('job_class_namespace'), $input->getArgument('queue_name'));
        $output->write("Job captured. Input at {$input->getArgument('queue_name')} queue. Job id {$job}");
    }
}
