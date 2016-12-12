<?php
namespace ffan\php\utils;

/**
 * Class SignalException 模拟信号的异常，并不是真正的异常
 * @package ffan\php\utils
 */
class Signal extends \Exception implements Exception{

    /**
     * 伪装成异常的异常ID
     */
    const EXCEPTION_CODE = 1;

    /**
     * @var mixed 额外附加的数据
     */
    private $_args;

    /**
     * @var string 异常名称
     */
    private $_signal_name;

    /**
     * Signal constructor.
     * @param string $signal_name 信号名称
     * @param null $args 附加数据
     */
    public function __construct($signal_name, $args = null)
    {
        $this->_args = $args;
        $this->_signal_name = $signal_name;
        parent::__construct('signal_'. $signal_name, self::EXCEPTION_CODE);
    }

    /**
     * 获取信号名称
     * @return string
     */
    public function name()
    {
        return $this->_signal_name;
    }

    /**
     * 获取信号参数
     */
    public function args()
    {
        return $this->_args;
    }

    /**
     * @return string 字符串化
     */
    public function __toString()
    {
        return 'FFan php signal:'. $this->_signal_name;
    }
}