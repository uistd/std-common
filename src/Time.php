<?php

namespace UiStd\Common;

/**
 * Class Time 时间处理相关
 * @package UiStd\Common
 */
class Time
{
    /**
     * @var array 语言包
     */
    private static $lang = array(
        'now' => '现在',
        'before_second' => '刚刚',
        'before_minute' => '%s分钟前',
        'before_hour' => '%s小时前',
        'before_day' => '%s天前',
        'before_long' => '很久以前',
        'after_second' => '%s秒后',
        'after_minute' => '%s分后',
        'after_hour' => '%s小时后',
        'after_day' => '%s天后',
        'after_long' => '%s天后'
    );

    /**
     * 时间显示美化
     * @param int|string $timestamp 时间戳
     * @return string
     */
    public static function beauty($timestamp)
    {
        //如果是字符串，尝试转成时间戳
        if (!is_numeric($timestamp)) {
            $timestamp = strtotime($timestamp);
        }
        $now_time = time();
        $years = ceil($timestamp / 31536000);
        if ($years > 1000) {
            $timestamp = (int)floor($timestamp / 1000);
        }
        $time_diff = abs($now_time - $timestamp);
        $var = '';
        if (0 === $time_diff) {
            $lan_key = 'now';
        } else {
            $lan_key = $now_time > $timestamp ? 'before_': 'after_';
            //超过一天
            if ($time_diff > 86400) {
                $day_diff = floor($time_diff / 86400);
                //超过1000天 很久
                if ($day_diff > 1000) {
                    $lan_key .= 'long';
                } else {
                    $lan_key .= 'day';
                }
                $var = $day_diff;
            } //超过1小时
            elseif ($time_diff > 3600) {
                $var = floor($time_diff / 3600);
                $lan_key .= 'hour';
            } elseif ($time_diff > 60) {
                $var = floor($time_diff / 60);
                $lan_key .= 'minute';
            } else {
                $var = $time_diff;
                $lan_key .= 'second';
            }
        }
        return sprintf(self::$lang[$lan_key], $var);
    }
}
