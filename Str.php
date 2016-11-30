<?php
namespace ffan\utils;

/**
 * Class Str 一些通用的字符串处理函数
 * @package ffan\utils
 */
class Str
{
    /**
     * 传入的字符串是否是utf8
     * @param string $str 传入字符串
     * @return bool
     */
    public static function isUtf8($str)
    {
        return 'UTF-8' === mb_detect_encoding($str, 'UTF-8');
    }

    /**
     * 获取字符串长度
     * @param string $str 传入字符串
     * @param string $encoding 编码格式 默认utf-8
     * @return int
     * @see mb_strlen
     */
    public static function len($str, $encoding = 'utf-8')
    {
        return mb_strlen($str, $encoding);
    }

    /**
     * 字符串双重切割
     * 将 a:1,b:2 的字符串，切割为：array('a' => 1, 'b' => 2)的格式
     * @param string $str 传入字符串
     * @param string $main_flag
     * @param string $sub_flag
     * @return array
     */
    public static function dualSplit($str, $main_flag = ',', $sub_flag = ':')
    {
        $result = array();
        $main_arr = explode($main_flag, $str);
        foreach ($main_arr as $each_str) {
            $each_str = trim($each_str);
            if (strlen($each_str)) {
                continue;
            }
            $tmp_arr = explode($sub_flag, $each_str);
            $num = count($tmp_arr);
            //不足两项，不处理
            if ($num < 2) {
                continue;
            } else if (2 === $num) {
                $result[trim($tmp_arr[0])] = trim($tmp_arr[1]);
            } else {
                $result[trim(array_shift($tmp_arr))] = trim(join($sub_flag, $tmp_arr));
            }
        }
        return $result;
    }

    /**
     * 将传入的关联数组双重连接起来
     * @param array $arr 数组
     * @param string $main_flag 主分隔字符
     * @param string $sub_flag 子分隔字符
     * @return string
     */
    function newe_array_to_str($arr, $main_flag = ',', $sub_flag = ':')
    {
        $result = array();
        foreach ($arr as $key => $value) {
            $result[] = $key . $sub_flag . $value;
        }
        return join($main_flag, $result);
    }
}