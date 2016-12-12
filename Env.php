<?php
namespace ffan\php\utils;

/**
 * Class Env 环境
 * @package ffan\php\utils
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
     * @var string runtime目录
     */
    private static $_runtime_path;

    /**
     * @var int|null 环境
     */
    private static $_env;

    /**
     * 获取运行环境
     * @return int
     */
    public static function getEnv()
    {
        if (null !== self::$_env) {
            return self::$_env;
        }
        $tmp_env = Config::get('env', 'product');
        $env_map = array(
            'dev' => self::DEV,
            'test' => self::TEST,
            'product' => self::PRODUCT
        );
        self::$_env = isset($env_map[$tmp_env]) ? $env_map[$tmp_env] : self::PRODUCT;
        return self::$_env;
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
        return Config::get('timezone', 'Asia/Shanghai');
    }

    /**
     * 获取系统默认编码
     * @return string
     */
    public static function getCharset()
    {
        return Config::get('charset', 'UTF-8');
    }

    /**
     * 获取运行目录
     * @return string
     */
    public static function getRuntimePath()
    {
        if (null !== self::$_runtime_path) {
            return self::$_runtime_path;
        }
        $runtime_path = Config::get('runtime_path', '/var/log/');
        if (!is_string($runtime_path)) {
            $runtime_path = self::$_runtime_path;
        }
        $runtime_path = trim($runtime_path);
        if (0 == strlen($runtime_path)) {
            throw new \RuntimeException('runtime_path:' . $runtime_path . ' is empty');
        }
        if (DIRECTORY_SEPARATOR !== $runtime_path[0]) {
            throw new \RuntimeException('runtime_path:' . $runtime_path . ' is not absolute path!');
        }
        if (!is_dir($runtime_path) && !mkdir($runtime_path, 0755, true)) {
            throw new \RuntimeException('Env runtime_path:' . $runtime_path . ' is not exist');
        }
        //不可写
        if (!is_writable($runtime_path)) {
            throw new \RuntimeException('Env runtime_path:' . $runtime_path . ' is not writable');
        }
        $len = strlen($runtime_path);
        //补全目录路径
        if (DIRECTORY_SEPARATOR !== $runtime_path[$len - 1]) {
            $runtime_path .= DIRECTORY_SEPARATOR;
        }
        self::$_runtime_path = $runtime_path;
        return $runtime_path;
    }

    /**
     * PHP SAPI是否是CLI方式
     * @return bool
     */
    public static function isCli()
    {
        return 'cli' === PHP_SAPI;
    }
}
