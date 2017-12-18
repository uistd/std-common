<?php
namespace UiStd\Common;

/**
 * Class Config
 * @package UiStd\Common
 */
class Config
{
    private static $_conf_arr = array();

    /**
     * 在现有config基础上添加一个数组
     * 数组需要是 key => value的
     * @param array $conf_arr
     * @param bool $recover_exist_key 如果已经有key存在了，是否覆盖
     */
    public static function addArray(array $conf_arr, $recover_exist_key = false)
    {
        //直接覆盖
        if ($recover_exist_key) {
            self::$_conf_arr = array_merge(self::$_conf_arr, $conf_arr);
        } //合并
        else {
            foreach ($conf_arr as $name => $value) {
                //如果value是数组
                if (is_array($value) && isset(self::$_conf_arr[$name]) && is_array(self::$_conf_arr[$name])) {
                    self::$_conf_arr[$name] = array_merge(self::$_conf_arr[$name], $value);
                } else {
                    self::$_conf_arr[$name] = $value;
                }
            }
        }
    }

    /**
     * 添加指定配置
     * @param string $key 配置名
     * @param mixed $conf
     * @param bool $recover_exist_key 如果已经有key存在了，是否覆盖
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
        if (false === strpos($key, '.')) {
            return isset(self::$_conf_arr[$key]) ? self::$_conf_arr[$key] : $default_value;
        } else {
            $groups = explode('.', $key);
            $value_node = self::$_conf_arr;
            foreach ($groups as $group_name) {
                if (!isset($value_node[$group_name])) {
                    return $default_value;
                }
                $value_node = $value_node[$group_name];
            }
            return $value_node;
        }
    }

    /**
     * 获取一个配置，并且强转成int型
     * @param string $key 配置名
     * @param int $default_value 默认值
     * @param int $min_value 最小值
     * @param int $max_value 最大值
     * @return int
     * @throws InvalidConfigException
     */
    public static function getInt($key, $default_value = 0, $min_value = 0, $max_value = PHP_INT_MAX)
    {
        $re = (int)self::get($key, $default_value);
        if ($re < $min_value || $re > $max_value) {
            throw new InvalidConfigException($key . ' value allowed ' . $min_value . ' to ' . $max_value);
        }
        return $re;
    }

    /**
     * 获取一个配置，并且移除两边的空格
     * @param string $key 配置名
     * @param string $default_value
     * @return string
     */
    public static function getString($key, $default_value = '')
    {
        $re = (string)self::get($key, $default_value);
        return trim($re);
    }

    /**
     * 初始化配置
     * @param array $init_config
     */
    public static function init(array $init_config)
    {
        //如果$_conf_arr已经不为空了，不能调用该方法初始化
        if (!empty(self::$_conf_arr)) {
            throw new \RuntimeException('Configuration has been initialized!');
        }
        self::$_conf_arr = $init_config;
    }

    /**
     * 加载配置文件
     * @param string $file
     * @param string $default_env 默认的配置, 如果不存在当前环境的配置, 使用指定的配置作为默认配置
     * @return array
     */
    public static function load($file, $default_env = 'sit')
    {
        $empty_config = array();
        //如果文件不存在
        if (!is_file($file)) {
            return $empty_config;
        }
        /** @noinspection PhpIncludeInspection */
        $config_arr = require($file);
        if (!is_array($config_arr)) {
            return $empty_config;
        }
        $env = self::get('env', $default_env);
        $env_key = 'CONFIG_'. strtoupper($env);
        //如果存在该环境下特殊的配置
        if (!isset($config_arr[$env_key])) {
            $env_key = 'CONFIG_'. strtoupper($default_env);
        }
        return isset($config_arr[$env_key]) ? $config_arr[$env_key] : $config_arr;
    }
}
