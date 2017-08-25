<?php

namespace ffan\php\utils;

/**
 * Class Debug 调试相关函数
 * @package ffan\php\utils
 */
class Debug
{
    /**
     * @var array 计时器数组
     */
    private static $timer_arr = [];

    /**
     * 开始计时
     */
    public static function timerStart()
    {
        self::$timer_arr[] = microtime(true);
    }

    /**
     * 计时器结束
     * @param int $precision 小数点后位数
     * @return string
     */
    public static function timerStop($precision = 2)
    {
        if (empty(self::$timer_arr)) {
            return '';
        }
        $beg_time = array_pop(self::$timer_arr);
        $end_time = microtime(true);
        $result = round(($end_time - $beg_time) * 1000, $precision) . 'ms';
        return $result;
    }

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

    /**
     * 代码回溯信息
     * @param array|null 代码回溯信息 ，如果为null，立即获取
     * @param bool $trace_arg 是否打印参数
     * @return string
     */
    public static function codeTrace($trace_list = null, $trace_arg = false)
    {
        if (!is_array($trace_list)) {
            $trace_list = debug_backtrace();
            //第一条信息就是codeTrace，没意义
            array_shift($trace_list);
        }
        $array_format = array();
        $array_count = 0;
        $error_arr = array();
        //第一次循环，生成参数信息，做了一点优化，相同内容的数组或者对象已经打印过了，就不再打印
        for ($i = count($trace_list) - 1; $i >= 0; --$i) {
            $tmp_info = &$trace_list[$i];
            if (!isset($tmp_info['args'])) {
                $tmp_info['arg_info'] = '';
                continue;
            }
            if ($trace_arg) {
                $arg_info = '';
                foreach ($tmp_info['args'] as $arg_id => $each_arg) {
                    $param_type = gettype($each_arg);
                    $param_format = self::varFormat($each_arg, 4096);
                    if ('array' === $param_type || 'object' === $param_type) {
                        $md5_param = md5($param_format);
                        if (isset($array_format[$md5_param])) {
                            $param_format = '[...]';
                            $param_type = $array_format[$md5_param];
                        } else {
                            $arr_name = $param_type . '_' . $array_count;
                            $array_format[$md5_param] = $arr_name;
                            $param_type = $arr_name;
                            $array_count++;
                        }
                    }
                    $arg_info .= PHP_EOL . '[Arg_' . $arg_id . '] => (' . $param_type . ')' . $param_format;
                }
                $tmp_info['arg_info'] = $arg_info;
            }
        }
        $index = 0;
        foreach ($trace_list as $step_info) {
            $error_msg = '#' . $index++ . ' ';
            if (isset($step_info['file'])) {
                $error_msg .= $step_info['file'];
            }
            if (isset($step_info['line'])) {
                $error_msg .= '(line ' . $step_info['line'] . ') ';
            }
            if (isset($step_info['class'])) {
                $error_msg .= $step_info['class'];
            }
            if (isset($step_info['type'])) {
                $error_msg .= $step_info['type'];
            }
            if (isset($step_info['function'])) {
                $error_msg .= $step_info['function'] . '()';
            }
            if ($trace_arg) {
                $error_msg .= $step_info['arg_info'] . PHP_EOL;
            }
            $error_arr[] = $error_msg;
        }
        return join(PHP_EOL, $error_arr) . PHP_EOL;
    }
}
