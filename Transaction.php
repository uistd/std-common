<?php
namespace ffan\php\utils;

/**
 * Class Transaction 事务接口
 * @package ffan\php\utils
 */
class Transaction
{
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
