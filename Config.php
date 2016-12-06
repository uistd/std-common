<?php
namespace ffan\utils;

class Config
{
    private static $_conf_arr = array();

    /**
     * 初始化一整个数组
     * 数组需要是 key => value的
     * @param array $conf_arr
     * @param bool $recover_exist_key 如果已经有key存在了，是否覆盖
     */
    public static function addArray(array $conf_arr, $recover_exist_key = false)
    {
        if ($recover_exist_key) {
            self::$_conf_arr = array_merge(self::$_conf_arr, $conf_arr);
        } else {
            self::$_conf_arr += $conf_arr;
        }
    }

    /**
     * 添加指定配置
     * @param string $key 配置名
     * @param mixed $conf
     * @param bool $recover_exist_key 如果已经有key存在了，是否覆盖
     * @return null
     */
    public static function add($key, $conf, $recover_exist_key = true)
    {
        if (!$recover_exist_key && isset(self::$_conf_arr[$key])) {
            return;
        }
        self::$_conf_arr[$key] = $conf;
    }

    /**
     * 获取一个配置
     * @param string $key 配置名
     * @param mixed $default_value 当不存在这个配置的时候，返回的默认值
     * @return mixed
     */
    public static function get($key, $default_value = null)
    {
        return isset(self::$_conf_arr[$key]) ? self::$_conf_arr[$key] : $default_value;
    }
}
