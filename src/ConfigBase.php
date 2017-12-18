<?php

namespace UiStd\Common;

/**
 * Class ConfigBase 通用的配置读取接口
 * @package UiStd\Common
 */
abstract class ConfigBase
{
    /**
     * @var array 配置项
     */
    protected $config_arr;

    /**
     * 初始配置
     * @param array $arr 所有的配置项
     */
    protected function initConfig(array $arr)
    {
        $this->config_arr = $arr;
    }

    /**
     * 设置配置
     * @param string $name 配置项
     * @param mixed $value 值
     */
    protected function setConfig($name, $value)
    {
        $this->config_arr[$name] = $value;
    }

    /**
     * 获取一项配置
     * @param string $name 配置名称
     * @param null $default 如果不存在，默认值
     * @return mixed
     */
    protected function getConfig($name, $default = null)
    {
        if (!isset($this->config_arr[$name])) {
            return $default;
        }
        return $this->config_arr[$name];
    }

    /**
     * 获取一个配置项，并且将值转成int
     * @param string $name 配置项
     * @param int $default 如果不存在的默认值
     * @return int
     */
    protected function getConfigInt($name, $default = 0)
    {
        if (!isset($this->config_arr[$name])) {
            return $default;
        }
        return (int)$this->config_arr[$name];
    }

    /**
     * 获取一个配置项，并且将值处理为字符串(会做trim处理)
     * @param string $name 配置项
     * @param string $default 如果不存在的默认值
     * @return string
     */
    protected function getConfigString($name, $default = '')
    {
        if (!isset($this->config_arr[$name])) {
            return $default;
        }
        $value = (string)$this->config_arr[$name];
        return trim($value);
    }

    /**
     * 获取一个配置荐，强转成bool
     * @param string $name
     * @param bool $default
     * @return bool
     */
    protected function getConfigBool($name, $default = false)
    {
        if (!isset($this->config_arr[$name])) {
            return (bool)$default;
        }
        return (bool)$this->config_arr[$name];
    }
}
