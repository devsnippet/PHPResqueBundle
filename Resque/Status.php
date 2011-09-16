<?php
namespace PHPResqueBundle\Resque;

class Status {

    public static function check($job_id) {
        require dirname(__FILE__) . '/../vendor/php-resque/lib/Resque/Job/Status.php';
        \Resque::setBackend('127.0.0.1:6379');
        
        $status = new \Resque_Job_Status($job_id);
        if (!$status->isTracking()) {
            die("Resque is not tracking the status of this job.\n");
        }
                
        return $status->get();
    }
}
