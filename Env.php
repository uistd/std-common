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
     * @var string 时区
     */
    private static $_timezone = 'Asia/Shanghai';

    /**
     * @var string 系统编码
     */
    private static $_charset = 'UTF-8';

    /**
     * 获取运行环境
     * @return int
     */
    public static function getEnv()
    {
        static $conf_env_static;
        if ($conf_env_static) {
            return $conf_env_static;
        }
        $tmp_env = Config::get('env', 'product');
        $env_map = array(
            'dev' => self::DEV,
            'test' => self::TEST,
            'product' => self::PRODUCT
        );
        $conf_env_static = isset($env_map[$tmp_env]) ? $env_map[$tmp_env] : self::PRODUCT;
        return $conf_env_static;
    }

    /**
     * 是否是开发环境
     * @return bool
     */
    public static function isDev()
    {
        return self::DEV === self::getEnv();
    }

    /**
     * 是否是测试环境
     * @return bool
     */
    public static function isTest()
    {
        return self::TEST === self::getEnv();
    }

    /**
     * 是否是生产环境
     * @return bool
     */
    public static function isProduct()
    {
        return self::PRODUCT === self::getEnv();
    }

    /**
     * 获取时区环境变量
     * @return string 获取时区
     */
    public static function getTimezone()
    {
        static $is_init_static = false;
        if ($is_init_static) {
            return self::$_timezone;
        }
        //todo 从配置里获取时区，并验证配置的时区是否正确
        $is_init_static = true;
        return self::$_timezone;
    }

    /**
     * 获取系统默认编码
     * @return string
     */
    public static function getCharset()
    {
        static $is_init_static = false;
        if ($is_init_static) {
            return self::$_charset;
        }
        //todo 从配置里获取时区，并验证配置的时区是否正确
        $is_init_static = true;
        return self::$_charset;
    }

    /**
     * 获取日志
     * @return string
     */
    public static function getLogPath()
    {
        static $is_init_static = false;
        if ($is_init_static) {
            return self::$_log_path;
        }
        $is_init_static = true;
        $log_path = Config::get('log_path');
        if (!is_string($log_path)) {
            $log_path = self::$_log_path;
        }
        $log_path = trim($log_path);
        if (0 == strlen($log_path)) {
            throw new \InvalidArgumentException('log_path:' . $log_path . ' is empty');
        }
        if (DIRECTORY_SEPARATOR !== $log_path[0]) {
            throw new \InvalidArgumentException('log_path:' . $log_path . ' is not absolute path!');
        }
        if (!is_dir(self::$_log_path) && !mkdir(self::$_log_path, 0755, true)) {
            throw new \RuntimeException('Env log_path:' . self::$_log_path . ' is not exist');
        }
        //不可写
        if (!is_writable(self::$_log_path)) {
            throw new \RuntimeException('Env log_path:' . self::$_log_path . ' is not writable');
        }
        $len = strlen(self::$_log_path);
        //补全目录路径
        if (DIRECTORY_SEPARATOR !== self::$_log_path[$len - 1]) {
            self::$_log_path .= DIRECTORY_SEPARATOR;
        }
        self::$_log_path = $log_path;
        return $log_path;
    }
}
