PHP-Resque Bundle for Symfony 2
===========================================
PHPResqueBundle is an derivation of php-resque project <https://github.com/chrisboulton/php-resque/>.

This bundle supports all features from php-resque project. It's includes: workers, enqueues, fork workers, events, etc.

## Install ##
    Add the following lines below into your `deps` file:
    
        [PHPResqueBundle]
            git=http://github.com/hlegius/PHPResqueBundle.git
            target=/PHPResqueBundle
            
        [php-resque]
            git=http://github.com/chrisboulton/php-resque.git
            target=/PHPResqueBundle/vendor/php-resque

    Now, run:
        $ php bin/vendors install
    
    If you have troubles on run this, try with --reinstall
    NOTE that this will download ALL your vendors again !
        $ php bin/vendors install --reinstall


    ### Configuration ###
    
    Open file app/AppKernel.php:
    Into your `$bundles` array, put at end of array:
    
        new PHPResqueBundle\PHPResqueBundle(),
        
    Open your app/autoload.php and register the namespace:
    
        'PHPResqueBundle'           => __DIR__.'/../vendor/',
    
    That's it. :)
    
## How it works ##
   ### Worker ###
   
   To start a worker you need:
        $ php app/console resque:worker
   
   This will start a worker for 'default' queue with normal log output with interval of 5 seconds between interations.
   But, of course you can change this with optional arguments:
   
        --log (verbose|normal|none)
        --interval 1..n
        --forkCount 0..n (this will require a PECL module <http://pecl.php.net/package/proctitle>)
        [queue_name] separated by commas if you want to work with more than one queue.
        
   So, if you want to work with 'mailer' and 'news_subscriptions' queues with 10 seconds of interval between interactions in a verbose output:
        $ php app/console resque:worker --log verbose --interval 10 mailer,news_subscriptions 


   ### Queue ###
   
   Attach a Job at default queue
        $ php app/console resque:queue Namespace\\Of\\Class\\SomeJob
        
   You can define which queue with optional argument [queue_name]. To put 'SomeJob' into 'mailer' queue:
        $ php app/console resque:queue Namespace\\Of\\Class\SomeJob mailer
        
    ### Status ###
    
    php-resque can us check a job status. In PHPResqueBundle this can be done by:
        $ php app/console resque:status 50b0568a3057c4d80641dcee6de7cca9
        
    Hash status was provided when you enqueue a job.
    
    
    ### Updating a job status ###
    
    If you need to update a job status, this can be done by:
        $ php app/console resque:update [job_hash] [new_status]
        
    Status can be checked at original php-resque project at <https://github.com/chrisboulton/php-resque/>
    
    
   ### Events ###
   
   With events you can perform other activities into your Job class.
   Actually, php-resque project has those events:
   
   `beforeFirstFork`, `beforeFork`, `afterFork`, `beforePerform`, `afterPerform`, `onFailure`, `afterEnqueue`. Please, refer to php-resque project
   for documentation about these events.
   
   PHPResqueBundle has a interface that ability you use them into your classes. So, if you want to add a beforePerform event into yout MyJob class:
   
        <?php
        namespace Your\ProjectBundle\SubprojectBundle;
        
        use PHPResqueBundle\Resque\Event;
        
        class MyJob {
        
            public function __construct() {
                Event::beforePerform(__CLASS__, 'doitbetter');
            }
        
            public static function doitbetter() {
                echo "An event was throwed !";
            }
        
            public function perform() {
                fwrite(STDOUT, 'My_Job Resque running on Symfony 2');
            }        
        }
        
   All events into Event (PHPResqueBundle\Resque\Event) class is mapped by a method that is a event name which is provided on the fly.
   The arguments is always: classname (use __CLASS__ always you can) and a *callback*. `Callbacks` can be anything callable by `call_user_func_array`.
   More information about what can be a `callable` should be taken at php-resque project.
   
    ### Stop an event ###
    PHP-Resque provides a way to stop an event. In PHPResqueBundle this can be done by [event]Stop reflection method:
    
         <?php
         namespace Your\ProjectBundle\SubprojectBundle;
        
         use PHPResqueBundle\Resque\Event;
        
         class MyJob {
        
             public function __construct() {
                 Event::beforePerformStop(__CLASS__, 'doNotItbetter');
             }
        
             public static function doNotItbetter() {
                 echo "An event has gone away !";
             }
        
             public function perform() {
                 fwrite(STDOUT, 'My_Job Resque running on Symfony 2');
             }        
         }

    Suggestions are welcome. If you have trouble with *PHPResqueBundle*, please, open a ticket at Github Project Issue Tracker.
    Issues or Contributions about php-resque should be open at php-resque project <https://github.com/chrisboulton/php-resque>.
    If you'd like to contribute with code, please, send a pull request !