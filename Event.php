<?php
namespace ffan\utils;

use Psr\EventManager\EventInterface;

/**
 * Class Event
 * @package ffan\utils
 */
class Event implements EventInterface
{

    /**
     * @var string 事件名称
     */
    private $_name;

    /**
     * @var null|string|object
     */
    private $_target;

    /**
     * @var null|array 参数列表
     */
    private $_params = [];

    /**
     * @var bool 是否已经停止冒泡
     */
    private $_is_propagation = false;

    /**
     * Get event name
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Get target/context from which event was triggered
     *
     * @return null|string|object
     */
    public function getTarget()
    {
        return $this->_target;
    }

    /**
     * 获取通过事件传递的参数列表
     *
     * @return array
     */
    public function getParams()
    {
        return $this->_params;
    }

    /**
     * 按名称获取参数
     *
     * @param  string $name
     * @return mixed
     */
    public function getParam($name)
    {
        return isset($this->_params[$name]) ? $this->_params[$name] : null;
    }

    /**
     * 设置event的名称
     *
     * @param  string $name
     * @return void
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * 设置事件源
     *
     * @param  null|string|object $target
     * @return void
     */
    public function setTarget($target)
    {
        $this->_target = $target;
    }

    /**
     * 设置参数列表
     *
     * @param  array $params
     * @return void
     */
    public function setParams(array $params)
    {
        $this->_params = $params;
    }

    /**
     * 是否已经停止冒泡了
     *
     * @param  bool $flag
     */
    public function stopPropagation($flag)
    {
        $this->_is_propagation = (bool)$flag;
    }

    /**
     * 是否已经停止冒泡了
     *
     * @return bool
     */
    public function isPropagationStopped()
    {
        return $this->_is_propagation;
    }
}
