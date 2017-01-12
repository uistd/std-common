<?php
namespace ffan\php\utils;

/**
 * Class Factory 类工厂
 * @package ffan\php\utils
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
        if (isset(self::$object_arr[$config_name])) {
            return self::$object_arr[$config_name];
        }
        if (!is_string($config_name)) {
            throw new \InvalidArgumentException('config_name is not string');
        }
        $conf_arr = Config::get(self::$config_group . ':' . $config_name);
        if (!is_array($conf_arr)) {
            $conf_arr = [];
        }
        //如果指定了的类名
        if (isset($conf_arr['class_name'])) {
            $class_name = $conf_arr['class_name'];
            //如果有别名，使用配置的别名
            if (isset(self::$class_alias[$class_name])) {
                $class_name = self::$class_alias[$class_name];
            } elseif (!Str::isValidClassName($class_name)) {
                throw new InvalidConfigException(self::$config_group . ':' . $config_name . '.class_name', 'invalid class name!');
            }
            $new_obj = new $class_name($config_name, $conf_arr);
        } else {
            $new_obj = self::defaultInstance($conf_arr);
        }
        if (null === $new_obj) {
            throw new InvalidConfigException(self::$config_group . ':' . $config_name, 'Can not instance');
        }
        self::$object_arr[$config_name] = $new_obj;
        return $new_obj;
    }

    /**
     * 根据配置手动加载类
     * @param array $conf_arr 配置
     * @return object
     */
    protected static function defaultInstance($conf_arr)
    {
        //子类实现
        return null;
    }
}
