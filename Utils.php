<?php

namespace ffan\php\utils;

/**
 * Class Utils 一些通用方法
 * @package ffan\php\utils
 */
class Utils
{
    /**
     * 默认的trim的第二个参数
     */
    const DEFAULT_TRIM = "\t\n\r\0\x0B\"";

    /**
     * 连接两个目录
     * @param string $base_path 基础路径
     * @param string $sub_path 子路径
     * @return string
     */
    public static function joinPath($base_path, $sub_path)
    {
        $base_path = trim($base_path);
        //移除trim默认的字符以外，还需要移除 / 号
        $sub_path = trim($sub_path, self::DEFAULT_TRIM . DIRECTORY_SEPARATOR);
        $base_len = strlen($base_path);
        $base_has_div = $base_len > 0 && DIRECTORY_SEPARATOR === $base_path[$base_len - 1];
        //base_path 自带 / 号
        if ($base_has_div) {
            $re_str = $base_path . $sub_path;
        } else {
            $re_str = $base_path . DIRECTORY_SEPARATOR . $sub_path;
        }
        return $re_str . DIRECTORY_SEPARATOR;
    }

    /**
     * 连接目录和文件名
     * @param string $base_path 基础目录
     * @param string $file 文件名
     * @return string
     */
    public static function joinFilePath($base_path, $file)
    {
        $base_path = trim($base_path);
        $file = trim($file, self::DEFAULT_TRIM . DIRECTORY_SEPARATOR);
        $base_len = strlen($base_path);
        $base_has_div = $base_len > 0 && DIRECTORY_SEPARATOR === $base_path[$base_len - 1];
        //base_path 自带 / 号
        if ($base_has_div) {
            $re_str = $base_path . $file;
        } else {
            $re_str = $base_path . DIRECTORY_SEPARATOR . $file;
        }
        return $re_str;
    }

    /**
     * 当传入的路径不是绝对路径时，前路径使用 runtime 路径补全
     * @param string $path 目录
     * @return string
     */
    public static function fixWithRuntimePath($path)
    {
        $path = trim($path);
        if (0 === strlen($path)) {
            return Env::getRuntimePath();
        }
        if (DIRECTORY_SEPARATOR !== $path[strlen($path) - 1]) {
            $path .= DIRECTORY_SEPARATOR;
        }
        if (DIRECTORY_SEPARATOR !== $path[0]) {
            $path = Env::getRuntimePath() . $path;
        }
        return $path;
    }

    /**
     * 当传入的路径不是绝对路径时，前路径使用 root_path 路径补全
     * @param string $path 目录
     * @return string
     */
    public static function fixWithRootPath($path)
    {
        $path = trim($path);
        if (0 === strlen($path)) {
            return Env::getRootPath();
        }
        if (DIRECTORY_SEPARATOR !== $path[strlen($path) - 1]) {
            $path .= DIRECTORY_SEPARATOR;
        }
        if (DIRECTORY_SEPARATOR !== $path[0]) {
            $path = Env::getRootPath() . $path;
        }
        return $path;
    }
    
    /**
     * 修正路径、保证最后一位是 / 号
     * @param string $path
     * @return string
     */
    public static function fixPath($path)
    {
        if (!is_string($path)) {
            return '';
        }
        $path = trim($path);
        $len = strlen($path);
        //如果是空，不处理
        if (0 === $len) {
            return $path;
        }
        if (DIRECTORY_SEPARATOR !== $path[$len - 1]) {
            $path .= DIRECTORY_SEPARATOR;
        }
        return $path;
    }

    /**
     * 判断目录是否有写入权限
     * @param string $path 目录
     * @param bool $auto_make 是否自动创建目录
     */
    public static function pathWriteCheck($path, $auto_make = true)
    {
        if (!is_dir($path) && $auto_make && !mkdir($path, 0755, true)) {
            throw new \RuntimeException('Path:' . $path . ' is not exist');
        }
        //不可写
        if (!is_writable($path)) {
            throw new \RuntimeException('Path:' . $path . ' is not writable');
        }
    }

    /**
     * 将文件格式化表示转换成byte(10Kb 输出 10240)
     * @param string|int $size_str 大小
     * @return int
     */
    public static function fileSizeToByte($size_str)
    {
        if (is_numeric($size_str)) {
            return (int)$size_str;
        }
        $unit_arr = array(
            'P' => 5,
            'T' => 4,
            'G' => 3,
            'M' => 2,
            'K' => 1,
            'BYTE' => 0,
        );
        $size_str = trim(rtrim(strtoupper($size_str), 'B'));
        if (!preg_match('/^([0-9]+)([A-Z]+)$/', $size_str, $match)) {
            return -1;
        }
        $size = $match[1];
        $unit = $match[2];
        if (!isset($unit_arr[$unit])) {
            return -1;
        }
        $result = $size * pow(1024, $unit_arr[$unit]);
        return (int)$result;
    }

    /**
     * 文件大小格式化输出(10240 输出 10K )
     * @param int $file_size
     * @param int $precision 小数点位数
     * @param int $unit_type 单位格式 1：首字母大写 2：全部大写 其它：全部小写
     * @return string
     */
    public static function fileSizeFormat($file_size, $precision = 2, $unit_type = 1)
    {
        $file_size = (int)$file_size;
        if ($file_size <= 0) {
            return '0';
        }
        if ($file_size < 0x400) {
            $size_str = (string)$file_size;
            $unit = 'byte';
        } elseif ($file_size < 0x100000) {
            $size_str = $file_size / 0x400;
            $unit = 'kb';
        } elseif ($file_size < 0x40000000) {
            $size_str = $file_size / 0x100000;
            $unit = 'mb';
        } elseif ($file_size < 0x10000000000) {
            $size_str = $file_size / 0x40000000;
            $unit = 'gb';
        } elseif ($file_size < 0x4000000000000) {
            $size_str = $file_size / 0x10000000000;
            $unit = 'tb';
        } else {
            $size_str = $file_size / 0x4000000000000;
            $unit = 'pb';
        }
        $size_str = round($size_str, $precision);
        if (1 === $unit_type) {
            $unit = ucwords($unit);
        } elseif (2 === $unit_type) {
            $unit = strtoupper($unit);
        }
        return $size_str . $unit;
    }
}
