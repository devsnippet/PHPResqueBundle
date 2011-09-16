<?php
namespace PHPResqueBundle\Resque;

class Queue {

    public function __construct($job_name) {        
        require dirname(__FILE__) . '/../vendor/php-resque/lib/Resque.php';
        Resque::setBackend('127.0.0.1:6379');
        
        $args = array('time' => time(), 'array' => array('test' => 'test'));        
        $jobId = Resque::enqueue('default', $job_name, $args, true);
        
        return $jobId;
    }
}
