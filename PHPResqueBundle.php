<?php
namespace PHPResqueBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;

require_once dirname(__FILE__) . '/vendor/php-resque/lib/Resque.php';
require_once dirname(__FILE__) . '/vendor/php-resque/lib/Resque/Worker.php';

class PHPResqueBundle extends Bundle {
    private $queue = '*';
    private $logging = 'normal';
    private $checker_interval = 5;
    private $fork_count = 1;

    public function defineQueue($name) {
        $this->queue = $name;
    }

    public function verbose($mode) {
        $this->logging = $mode;
    }

    public function setInterval($interval) {
        $this->checker_interval = (int)$interval;
    }

    public function forkInstances($count) {
        settype($count, 'int');
        
        if ($count > 1) {
            if (function_exists('pcntl_fork')) {
                $this->fork_count = $count;
            } else {
                fwrite(STDOUT, "*** Fork could not initialized. PHP function pcntl_fork() does NOT exists \n");
                $this->fork_count = 1;
            }
        } else {
            $this->fork_count = 1;
        }
    }

    public function getForkInstances() {
        return $this->fork_count;
    }

    private function loglevel() {
        switch ($this->logging) {
            case 'verbose' :
                return \Resque_Worker::LOG_VERBOSE;
            case 'normal' :
                return \Resque_Worker::LOG_NONE;
            default :
                return \Resque_Worker::LOG_NONE;
        }
    }

    private function work() {
        $worker = new \Resque_Worker(explode(',', $this->queue));
        $worker->logLevel = $this->loglevel();
        $worker->work($this->checker_interval);
        fwrite(STDOUT, '*** Starting worker ' . $worker . "\n");    
    }

    public function daemon() {
        \Resque::setBackend('127.0.0.1:6379');
        
        if (strpos($this->queue, ':') !== false) {
            list($namespace, $queue) = explode(':', $this->queue);
            \Resque_Redis::prefix($namespace);
            $this->queue = $queue;
        }
        
        if ($this->getForkInstances() > 1) {
            for ($i = 0; $i < $this->getForkInstances(); ++$i) {
                $pid = pcntl_fork();
                
                if ($pid == -1) {
                    throw new \RuntimeException("Could not fork worker {$i}");
                }
                
                $this->work();
                break;
            }
        } else {
            $this->work();
        }
    }
}
