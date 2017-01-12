<?php
namespace ffan\php\utils;
use ffan\php\event\EventManager;

/**
 * Class Transaction 事务接口
 * @package ffan\php\utils
 */
class Transaction
{
    /**
     * @var int 优化级
     */
    protected $trans_priority = 0;

    /**
     * Transaction constructor. 设置事件
     */
    public function __construct()
    {
        $event = EventManager::instance();
        $event->attach('commit', [$this, 'commit'], $this->trans_priority);
        $event->attach('rollback', [$this, 'rollback'], $this->trans_priority);
    }

    /**
     * 构造时就提交
     */
    public function __destruct()
    {
        $this->commit();
    }

    /**
     * 提交
     * @return void
     */
    public function commit()
    {

    }

    /**
     * 回滚
     * @return void
     */
    public function rollback()
    {

    }
}
