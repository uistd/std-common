<?php

namespace FFan\Std\Common;

/**
 * Class Factory 类工厂
 * @package FFan\Std\Common
 */
abstract class Factory
{
    /**
     * @var string 配置组名
     */
    protected static $config_group = 'ffan';

    /**
     * @var array 对象列表
     */
    protected static $object_arr;

    /**
     * @var array 类名和配置名对应关系
     */
    protected static $class_alias;

    /**
     * @param string $config_name 配置名
     */
    public static function get($config_name)
    {
        //子类实现
    }

    /**
     * @param string $config_name 配置名
     * @return object
     * @throws InvalidConfigException
     */
    protected static function getInstance($config_name)
    {
        $cache_key = self::configGroupName($config_name);
        if (isset(static::$object_arr[$cache_key])) {
            return static::$object_arr[$cache_key];
        }
        $conf_arr = Config::get($cache_key, []);
        $new_obj = static::defaultInstance($config_name, $conf_arr);
        if (null === $new_obj) {
            throw new InvalidConfigException(self::configGroupName($config_name), ' Can not instance');
        }
        static::$object_arr[$cache_key] = $new_obj;
        return $new_obj;
    }

    /**
     * 根据配置手动加载类
     * @param string $config_name 配置名
     * @param array $conf_arr 配置
     * @return object
     */
    protected static function defaultInstance($config_name, $conf_arr)
    {
        //子类实现
        return null;
    }

    /**
     * 一般用于打印某个config key
     * @param string $config_name 配置主项
     * @param null|string $sub_key 配置子项
     * @return string
     */
    public static function configGroupName($config_name, $sub_key = null)
    {
        $re_str = static::$config_group . ':' . $config_name;
        if (null !== $sub_key) {
            $re_str .= '.' . $sub_key;
        }
        return $re_str;
    }
}
