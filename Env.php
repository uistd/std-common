<?php
namespace ffan\utils;

/**
 * Class Env 环境
 * @package ffan\utils
 */
class Env
{
    /**
     * 开发环境
     */
    const DEV = 1;

    /**
     * 测试环境
     */
    const TEST = 2;

    /**
     * 生成环境
     */
    const PRODUCT = 3;

    /**
     * @var int 运行环境标志
     */
    private static $flag = self::PRODUCT;

    /**
     * @var bool 是否锁定
     * 环境只允许设置一次，不允许变更
     */
    private static $_is_lock = false;

    /**
     * 设置环境为 开发环境
     */
    public static function setDev()
    {
        self::set(self::DEV);
    }

    /**
     * 获取当前的运行环境标志
     * @return int
     */
    public static function get()
    {
        return self::$flag;
    }

    /**
     * 设置环境为测试环境
     */
    public static function setTest()
    {
        self::set(self::TEST);
    }

    /**
     * 设置环境为生产环境
     */
    public static function setProduct()
    {
        self::set(self::PRODUCT);
    }

    /**
     * 设置环境
     * 环境只允许设置一次
     * @param int $flag
     * @throws \Exception
     */
    private static function set($flag)
    {
        if (self::$_is_lock) {
            throw new \Exception('Can not reset run environment!');
        }
        self::$flag = $flag;
        self::$_is_lock = true;
    }

    /**
     * 是否是开发环境
     * @return bool
     */
    public static function isDev()
    {
        return self::DEV === self::$flag;
    }

    /**
     * 是否是测试环境
     * @return bool
     */
    public static function isTest()
    {
        return self::TEST === self::$flag;
    }

    /**
     * 是否是生产环境
     * @return bool
     */
    public static function isProduct()
    {
        return self::PRODUCT === self::$flag;
    }
}
