<?php
namespace ffan\php\utils;

/**
 * Class Transaction 事务接口
 * @package ffan\php\utils
 */
class Transaction
{
    /**
     * @var int 优化级
     */
    private $trans_priority = 0;

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
     * 获取事务优化级
     * @return int
     */
    public function getPriority()
    {
        return $this->trans_priority;
    }

    /**
     * 设置优化级
     * @param int $priority
     */
    public function setPriority($priority)
    {
        $priority = (int)$priority;
        if ($priority < 0) {
            $priority = 0;
        }
        $this->trans_priority = $priority;
    }
}
