<?php
namespace ffan\php\utils;

use ffan\php\event\EventManager;

require_once '../vendor/autoload.php';

/**
 * Class TestEvent
 * @package ffan\php\utils
 */
class TransEvent extends Transaction
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
        parent::__exit();
        echo $this->name, ' exit!', PHP_EOL;
    }

    public function __destruct()
    {
        echo $this->name, ' destruct', PHP_EOL;
    }
    
    public function commit()
    {
        echo $this->name, ' commit', PHP_EOL;
    }

    public function work()
    {
        echo $this->name, ' work', PHP_EOL;
    }
}

$test_a = new TransEvent('A');
$test_b = new TransEvent('B');
$test_c = new TransEvent('C');

$test_a->work();
$test_b->work();
$test_c->work();

$test_d = new TransEvent('d');
$test_e = new TransEvent('e');
$test_f = new TransEvent('f');

$test_d->setTransPriority(100);
$test_e->setTransPriority(60);
$test_f->setTransPriority(20);

$test_d->work();
$test_e->work();
$test_f->work();

EventManager::instance()->trigger(EventDriver::EVENT_COMMIT);