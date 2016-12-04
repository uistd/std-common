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
     * @var string 默认的日志目录
     */
    private static $_log_path = '/var/log/';

    /**
     * @var bool 是否检查过log_path的可用性了
     */
    private static $_log_path_check = false;

    /**
     * @var string 时区
     */
    private static $_timezone = 'Asia/Shanghai';

    /**
     * @var string 系统编码
     */
    private static $_charset = 'UTF-8';

    /**
     * @var int 运行环境标志
     */
    private static $_flag = self::PRODUCT;

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
        return self::$_flag;
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
        self::$_flag = $flag;
        self::$_is_lock = true;
    }

    /**
     * 是否是开发环境
     * @return bool
     */
    public static function isDev()
    {
        return self::DEV === self::$_flag;
    }

    /**
     * 是否是测试环境
     * @return bool
     */
    public static function isTest()
    {
        return self::TEST === self::$_flag;
    }

    /**
     * 是否是生产环境
     * @return bool
     */
    public static function isProduct()
    {
        return self::PRODUCT === self::$_flag;
    }

    /**
     * 获取时区环境变量
     * @return string 获取时区
     */
    public static function getTimezone()
    {
        return self::$_timezone;
    }

    /**
     * 设置时区环境变量
     * @param string $timezone 时区
     * @return string
     */
    public static function setTimezone($timezone)
    {
        //todo 验证时区有效性
        return self::$_timezone = $timezone;
    }

    /**
     * 获取系统默认编码
     * @return string
     */
    public static function getCharset()
    {
        return self::$_charset;
    }

    /**
     * 设置默认编码
     * @param string $charset 编码
     * @return string
     */
    public static function setCharset($charset)
    {
        //todo 验证时间有效性
        return self::$_charset;
    }

    /**
     * 设置系统的日志目录
     * @param string $log_path
     */
    public static function setLogPath($log_path)
    {
        if (!is_string($log_path)) {
            throw new \InvalidArgumentException('$log_path is not string');
        }
        $log_path = trim($log_path);
        if (0 == strlen($log_path)) {
            throw new \InvalidArgumentException('$log_path is empty');
        }
        if (DIRECTORY_SEPARATOR !== $log_path[0]) {
            throw new \InvalidArgumentException('$log_path is not absolute path!');
        }
        self::$_log_path = $log_path;
    }

    /**
     * 获取日志
     * @return string
     */
    public static function getLogPath()
    {
        //检查日志目录是否可用
        if (!self::$_log_path_check) {
            if (!is_dir(self::$_log_path) && !mkdir(self::$_log_path, 0755, true)) {
                throw new \RuntimeException('Env log_path:' . self::$_log_path . ' is not exist');
            }
            //不可写
            if (!is_writable(self::$_log_path)) {
                throw new \RuntimeException('Env log_path:' . self::$_log_path . ' is not writable');
            }
            self::$_log_path_check = true;
        }
        return self::$_log_path;
    }
}
