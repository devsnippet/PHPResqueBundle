<?php
namespace PHPResqueBundle\Resque;

class Queue {

    public function add($job_name, $queue_name) {
        \Resque::setBackend('127.0.0.1:6379');
        $args = array('time' => time(), 'array' => array('test' => 'test'));
        
        try {
            $klass = new \ReflectionClass($job_name);
            $jobId = \Resque::enqueue($queue_name, $klass->getName(), $args, true);
            
            return $jobId;
        } catch (\ReflectionException $rfe) {
            throw new \RuntimeException($rfe->getMessage());
        }
    }
}
