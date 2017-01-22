<?php
namespace ffan\php\utils;

use ffan\php\event\EventManager;

/**
 * Class Transaction 事务接口
 * @package ffan\php\utils
 */
class Transaction extends EventDriver
{
    /**
     * @var int 优化级
     */
    protected $trans_priority = 0;

    /**
     * @var bool 是否已经响应commit事件
     */
    private $is_catch_commit_event = false;

    /**
     * Transaction constructor. 设置事件
     */
    public function __construct()
    {
        parent::__construct();
        $event = EventManager::instance();
        $event->attach(EventDriver::EVENT_COMMIT, [$this, 'commit_event'], $this->trans_priority);
        $event->attach(EventDriver::EVENT_ROLLBACK, [$this, 'rollback']);
    }

    /**
     * 处理完请求时，确保提交一次
     */
    public function __exit()
    {
        if (!$this->is_catch_commit_event) {
            $this->commit_event();
        }
    }

    /**
     * 提交事件
     */
    public function commit_event()
    {
        $this->is_catch_commit_event = true;
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
     * 获取事务优先级
     */
    public function getTransPriority()
    {
        return $this->trans_priority;
    }

    /**
     * 设置事务优化级
     * @param int $priority 优先级
     */
    public function setTransPriority($priority)
    {
        if ($priority < 0) {
            $priority = 0;
        } elseif ($priority >= self::MAX_PRIORITY) {
            $priority = self::MAX_PRIORITY;
        }
        $event = EventManager::instance();
        $this->trans_priority = $priority;
        $callback = [$this, 'commit_event'];
        //先移除事件，重新设置事件优先级，有优化空间
        $event->detach(EventDriver::EVENT_COMMIT, $callback);
        $event->attach(EventDriver::EVENT_COMMIT, $callback, $this->trans_priority);
    }
}
