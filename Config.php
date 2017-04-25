<?php
namespace ffan\php\utils;

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
}
