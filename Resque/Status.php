<?php
namespace PHPResqueBundle\Resque;

class Status {

    public static function check($job_id) {
        \Resque::setBackend('127.0.0.1:6379');
        
        $status = new \Resque_Job_Status($job_id);
        if (!$status->isTracking()) {
            die("Resque is not tracking the status of this job.\n");
        }
        
        $class = new \ReflectionObject($status);
        
        foreach ($class->getConstants() as $constant_name => $constant_value) {
            if ($constant_value == $status->get()) {
                break;
            }
        }

        return 'Job status in queue is ' . $status->get() . " [$constant_name]";
    }
}
