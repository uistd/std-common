<?php
namespace ffan\php\utils;

/**
 * Class InvalidConfigException
 * @package ffan\php\utils
 */
class InvalidConfigException extends \Exception implements Exception
{
    const ERROR_CODE = 2;

    /**
     * InvalidConfigException constructor.
     * @param string $config_name
     */
    public function __construct($config_name)
    {
        $message = 'Invalid config:' . $config_name;
        parent::__construct($message, self::ERROR_CODE);
    }
}