<?php
namespace PHPResqueBundle\Resque;

class Event {
    
    public static function __callstatic($method, $args) {
        if (!self::isCallableEvent($method)) {
            if (strpos($method, 'Stop') !== false) {
                return self::stopEvent($method, $args);
            }
            
            throw new PHPResqueEventException("The {$method} does not exists");
        }
        
        if (!\Resque_Event::listen($method, $args)) {
            throw new PHPResqueEventException("The {$method} cannot be registered.");
        }
    }
    
    private static function stopEvent($method, $args) {
        $callback_method = str_replace("Stop", '', $method);
        if (!self::isCallableEvent($callback_method)) {
            throw new PHPResqueEventException("The {$callback_method} does not exists. So you can't stop it.");
        }
        if (!\Resque_Event::stopListening($callback_method, $args)) {
            throw new PHPResqueEventException("The {$callback_method} can not stopped.");
        }
    }
    
    public static function tigger($event, $data = null) {
        \Resque_Event::trigger($event, $data);
    } 
    
    public static function clearListeners() {
        \Resque_Event::clearListeners();
    }
        
    private static function isCallableEvent($method) {
        switch ($method) {
            case 'beforeFirstFork':
            case 'beforeFork':
            case 'afterFork':
            case 'beforePerform':
            case 'afterPerform':
            case 'onFailure':
            case 'afterEnqueue':
                return true;
            default :
                return false;
        }
    }
}
