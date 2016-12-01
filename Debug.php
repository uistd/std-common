<?php
namespace ffan\utils;

/**
 * Class Debug 调试相关函数
 * @package ffan\utils
 */
class Debug
{
    /**
     * 将php变更格式化输出（二进制安全！）
     * @param mixed $php_var 变更
     * @param int $str_cut_len 字符串截取长度
     * @return string
     */
    public static function varFormat($php_var, $str_cut_len = 1048576)
    {
        $type = gettype($php_var);
        switch ($type) {
            case 'boolean':
                $result_str = $php_var ? 'True' : 'False';
                break;
            case 'integer':
            case 'double':
                $result_str = (string)$php_var;
                break;
            case 'NULL':
                $result_str = 'NULL';
                break;
            case 'resource':
                $result_str = 'Resource:' . get_resource_type($php_var);
                break;
            case 'string':
                $result_str = self::strFormat($php_var, $str_cut_len);
                break;
            case 'object':
                $result_str = 'Class of "' . get_class($php_var) . '"';
                $result_str .= self::strFormat(print_r($php_var, true), $str_cut_len);
                break;
            case 'array':
            default:
                $result_str = self::strFormat(print_r($php_var, true), $str_cut_len);
                break;
        }
        return $result_str;
    }

    /**
     * 字符串检测，包含二进制
     * @param string $str 字符串
     * @param int $str_cut_len 字符串截取长度
     * @return string
     */
    private static function strFormat($str, $str_cut_len)
    {
        if (!Str::isUtf8($str)) {
            $str = '[BINARY STRING]' . base64_encode($str);
        }
        if (strlen($str_cut_len) > $str_cut_len) {
            $str = substr($str, 0, $str_cut_len);
        }
        return $str;
    }
}