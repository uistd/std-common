<?php
namespace UiStd\Common;

/**
 * Class InvalidConfigException
 * @package UiStd\Common
 */
class InvalidConfigException extends \Exception
{
    /**
     * 配置出错的错误ID
     */
    const ERROR_CODE = 2;

    /**
     * InvalidConfigException constructor.
     * @param string $config_name 配置名
     * @param string $reason 原因
     */
    public function __construct($config_name, $reason = '')
    {
        $message = 'Invalid config:' . $config_name;
        if (!empty($reason)) {
            $message .= ' Reason:' . $reason;
        }
        parent::__construct($message, self::ERROR_CODE);
    }
}