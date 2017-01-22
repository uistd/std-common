<?php
namespace ffan\php\utils;

require_once '../vendor/autoload.php';

/**
 * Class TestEvent
 * @package ffan\php\utils
 */
class TestEvent extends EventDriver
{
    private $name;

    /**
     * TestEvent constructor.
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct();
        $this->name = $name;
        echo $this->name, ' construct', PHP_EOL;
    }
    
    public function __exit()
    {
        echo $this->name, ' exit!', PHP_EOL;
    }
    
    public function __destruct()
    {
        echo $this->name, ' destruct', PHP_EOL;
    }
    
    public function work()
    {
        echo $this->name, ' work', PHP_EOL;
    }
}

$test_a = new TestEvent('A');
$test_b = new TestEvent('B');
$test_c = new TestEvent('C');

$test_a->work();
$test_b->work();
$test_c->work();
