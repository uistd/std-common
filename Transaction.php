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
     * @var bool 是否已经设置监听事件
     */
    private $has_attach_flag = false;

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

    /**
     * 设置监听事件
     */
    public function attachEvent()
    {
        if ($this->has_attach_flag) {
            return;
        }
        $event = EventManager::instance();
        $event->attach('commit', [$this, 'commit'], $this->trans_priority);
        $event->attach('rollback', [$this, 'rollback'], $this->trans_priority);
    }
}
