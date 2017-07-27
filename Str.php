<?php
namespace ffan\php\utils;

/**
 * Class Str 一些通用的字符串处理函数
 * @package ffan\php\utils
 */
class Str
{
    /**
     * 切割的第一项都转成int
     */
    const INTVAL = 1;

    /**
     * 切割的每一项都trim
     */
    const TRIM = 2;

    /**
     * 忽略空值
     */
    const IGNORE_EMPTY = 4;

    /**
     * 传入的字符串是否是utf8
     * @param string $str 传入字符串
     * @return bool
     */
    public static function isUtf8($str)
    {
        return 'UTF-8' === mb_detect_encoding($str, 'UTF-8', true);
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
            if (0 === strlen($each_str)) {
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
    public static function dualJoin($arr, $main_flag = ',', $sub_flag = ':')
    {
        $result = array();
        foreach ($arr as $key => $value) {
            $result[] = $key . $sub_flag . $value;
        }
        return join($main_flag, $result);
    }

    /**
     * 将传入的字符串按指定字符串切割
     * @param string $str 待切字符串
     * @param string $split_flag 分割字符
     * @param int $split_mod 切割选项
     * 默认会忽略空值和移除左右空格
     *
     * @return array
     */
    public static function split($str, $split_flag = ',', $split_mod = self::TRIM | self::IGNORE_EMPTY)
    {
        if (!is_string($str)) {
            $str = (string)$str;
        }
        $result = array();
        if (empty($str)) {
            return $result;
        }
        $arr = explode($split_flag, $str);
        foreach ($arr as $item) {
            if ($split_mod & self::TRIM) {
                $item = trim($item);
            }
            if (($split_mod & self::IGNORE_EMPTY) && 0 == strlen($item)) {
                continue;
            }
            if ($split_mod & self::INTVAL) {
                $item = (int)$item;
            }
            $result[] = $item;
        }
        return $result;
    }

    /**
     * 将下划线命名转换为驼峰式命名
     * @param string $str 原始字符
     * @param bool $uppercase_first 首字母是否大写
     * @return string
     */
    public static function camelName($str, $uppercase_first = true)
    {
        if (false !== strpos($str, '_')) {
            $str = ucwords(str_replace('_', ' ', $str));
            $str = str_replace(' ', '', $str);
        }
        return $uppercase_first ? $str : lcfirst($str);
    }

    /**
     * 是否是合法的PHP类名
     * @param string $str 类名
     * @return bool
     */
    public static function isValidClassName($str)
    {
        return is_string($str) && preg_match('/^\/?[a-zA-Z_][a-zA-Z_\d]*(\/[a-zA-Z_][a-zA-Z_\d]*)*$/', $str) > 0;
    }

    /**
     * 是否是合法的变量名
     * @param string $str 变量名
     * @return bool
     */
    public static function isValidVarName($str)
    {
        return is_string($str) && preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $str) > 0;
    }

    /**
     * 将一个字符串模板替换其中变量
     * @param string $tpl_str 字符串模板
     * @param array $data_arr 变量数组
     * @param string $prefix_tag 变更前缀符
     * @param string $suffix_tag 变更后缀符
     * @return string
     */
    public static function tplReplace($tpl_str, array $data_arr = [], $prefix_tag = '{', $suffix_tag = '}')
    {
        $replace = array();
        $re = preg_match_all('/\\' . $prefix_tag . '([a-zA-Z_][a-zA-Z_0-9]*)\\' . $suffix_tag . '/', $tpl_str, $match_arr);
        if (!$re) {
            return $tpl_str;
        }
        $tmp_var_arr = $match_arr[0];
        //循环所有匹配的变量
        foreach ($match_arr[1] as $index => $name) {
            if (!isset($data_arr[$name])) {
                continue;
            }
            $val = $data_arr[$name];
            if (!is_string($val) && !method_exists($val, '__toString')) {
                $type = gettype($val);
                switch ($type) {
                    case 'bool':
                        $val = $val ? 'true' : 'false';
                        break;
                    case 'NULL':
                        $val = 'NULL';
                        break;
                    case 'integer':
                    case 'double':
                        $val = (string)$val;
                        break;
                    case 'resource':
                        $val = 'Resource:' . get_resource_type($val);
                        break;
                    default:
                        $val = json_encode($val);
                }
            }
            $replace[$tmp_var_arr[$index]] = $val;
        }
        return strtr($tpl_str, $replace);
    }
}
