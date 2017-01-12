<?php
namespace ffan\php\utils;
use ffan\php\event\EventManager;

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
    private static $class_alias;

    /**
     * @var bool 是否已经触发事件
     */
    private static $has_trigger = false;

    /**
     * @var bool 是否事务支持
     */
    private static $is_transaction = true;

    /**
     * @param string $config_name 配置名
     */
    protected static function get($config_name)
    {
        //子类实现
    }

    /**
     * @param string $config_name 配置名
     * @return object
     * @throws InvalidConfigException
     */
    protected static function doInstance($config_name)
    {
        if (!is_string($config_name)) {
            throw new \InvalidArgumentException('config_name is not string');
        }
        $conf_arr = Config::get(self::$config_group .':' . $config_name);
        if (!is_array($conf_arr)) {
            $conf_arr = [];
        }
        //通过事件自动调用commit 和 rollback
        if (self::$is_transaction && !self::$has_trigger) {
            self::$has_trigger = true;
            $eve_manager = EventManager::instance();
            $eve_manager->attach('commit', [__CLASS__, 'commit'], PHP_INT_MAX);
            $eve_manager->attach('rollback', [__CLASS__, 'rollback'], PHP_INT_MAX);
        }
        //如果指定了的类名
        if (isset($conf_arr['class_name'])) {
            $class_name = $conf_arr['class_name'];
            //如果有别名，使用配置的别名
            if (isset(self::$class_alias[$class_name])) {
                $class_name = self::$class_alias[$class_name];
            }
            elseif (!Str::isValidClassName($class_name)) {
                throw new InvalidConfigException(self::$config_group .':' . $config_name . '.class_name', 'invalid class name!');
            }
            $new_obj = new $class_name($config_name, $conf_arr);
        }
        else {
            $new_obj = self::defaultInstance($conf_arr);
        }
        return $new_obj;
    }

    /**
     * 根据配置手动加载类
     * @param array $conf_arr 配置
     * @return object
     */
    protected static function defaultInstance($conf_arr)
    {
        return null;
    }
    
    /**
     * commit
     */
    public static function commit()
    {
        if (!self::$object_arr) {
            return;
        }

        /**
         * @var string $name
         * @var Transaction $obj
         */
        foreach (self::$object_arr as $name => $obj) {
            $obj->rollback();
        }
    }

    /**
     * 全部rollback
     */
    public static function rollback()
    {
        if (!self::$object_arr) {
            return;
        }

        /**
         * @var string $name
         * @var Transaction $obj
         */
        foreach (self::$object_arr as $name => $obj) {
            $obj->rollback();
        }
    }
}
