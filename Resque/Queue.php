<?php
namespace PHPResqueBundle\Resque;

require_once dirname(__FILE__) . '/../vendor/php-resque/lib/Resque.php';

class Queue {

    public static function add($job_name, $queue_name, $args = array()) {
        \Resque::setBackend('127.0.0.1:6379');
        
        if (strpos($queue_name, ':') !== false) {
            list($namespace, $queue_name) = explode(':', $queue_name);
            \Resque_Redis::prefix($namespace);
        }
        
        try {
            $klass = new \ReflectionClass($job_name);
            $jobId = \Resque::enqueue($queue_name, $klass->getName(), $args, true);
            
            return $jobId;
        } catch (\ReflectionException $rfe) {
            throw new \RuntimeException($rfe->getMessage());
        }
    }
}
