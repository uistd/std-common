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
     * SIT 环境
     */
    const SIT = 2;

    /**
     * uat 环境
     */
    const UAT = 4;

    /**
     * pre 预发布环境
     */
    const PRE = 8;

    /**
     * 生成环境
     */
    const PRODUCT = 16;

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
            'sit' => self::SIT,
            'uat' => self::UAT,
            'pre' => self::PRE,
            'product' => self::PRODUCT,
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
     * 是否是sit环境
     * @return bool
     */
    public static function isSit()
    {
        return self::SIT === self::getEnv();
    }

    /**
     * 是否是uat环境
     * @return bool
     */
    public static function isUat()
    {
        return self::UAT === self::getEnv();
    }

    /**
     * 是否是pre环境
     * @return bool
     */
    public static function isPre()
    {
        return self::PRE === self::getEnv();
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
        $runtime_path = Config::getString('runtime_path', 'runtime');
        if (empty($runtime_path)) {
            $runtime_path = 'runtime';
        }
        if (DIRECTORY_SEPARATOR !== $runtime_path[0]) {
            $runtime_path = Utils::joinPath(self::getRootPath(), $runtime_path);
        }
        //补全目录路径
        if (DIRECTORY_SEPARATOR !== $runtime_path[strlen($runtime_path) - 1]) {
            $runtime_path .= DIRECTORY_SEPARATOR;
        }
        $apc_flag = self::apcSupport();
        if ($apc_flag) {
            $cache_key = 'dir-chk-' . $runtime_path;
            $re = apc_fetch($cache_key);
            if (true === $re) {
                self::$_runtime_path = $runtime_path;
                return $runtime_path;
            }
        }
        if (!is_dir($runtime_path) && !mkdir($runtime_path, 0755, true)) {
            throw new \RuntimeException('Env runtime_path:' . $runtime_path . ' is not exist');
        }
        //不可写
        if (!is_writable($runtime_path)) {
            throw new \RuntimeException('Env runtime_path:' . $runtime_path . ' is not writable');
        }
        if ($apc_flag) {
            /** @var string $cache_key */
            apc_store($cache_key, true);
        }
        self::$_runtime_path = $runtime_path;
        return $runtime_path;
    }

    /**
     * 获取配置版本
     * @return string
     */
    public static function configVer()
    {
        static $ver = null;
        if (null === $ver) {
            $ver = Config::getString('config_ver', '');
        }
        return $ver;
    }

    /**
     * 是否开启的apc
     * @return bool
     */
    public static function apcSupport()
    {
        static $flag = null;
        if (null === $flag) {
            $flag = extension_loaded('apc');
        }
        return $flag;
    }

    /**
     * 获取应用的根目录
     * @return string
     */
    public static function getRootPath()
    {
        if (defined('ROOT_PATH')) {
            return ROOT_PATH;
        }
        //root_path/vendor/ffan/php/utils/Env.php
        $root_path = str_replace('vendor/ffan/php/utils', '', __DIR__);
        return $root_path;
    }

    /**
     * PHP SAPI是否是CLI方式
     * @return bool
     */
    public static function isCli()
    {
        return 'cli' === PHP_SAPI;
    }

    /**
     * 是否是浏览器 XMLHttpRequest发出的请求
     * @return bool
     */
    public static function isAjaxRequest()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && 'xmlhttprequest' === strtolower($_SERVER['HTTP_X_REQUESTED_WITH']);
    }
}
