<?php

namespace UiStd\Common;

/**
 * Class Utils 一些通用方法
 * @package UiStd\Common
 */
class Utils
{
    /**
     * 默认的trim的第二个参数
     */
    const DEFAULT_TRIM = " \t\n\r\0\x0B\"";

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
     * 大小格式化输出(10240 输出 10K )
     * @param int $size
     * @param int $precision 小数点位数
     * @return string
     */
    public static function sizeFormat($size, $precision = 1)
    {
        $size = (int)$size;
        if ($size <= 0) {
            return '0';
        }
        if ($size < 0x400) {
            $size_str = (string)$size;
            $unit = 'Byte';
        } elseif ($size < 0x100000) {
            $size_str = $size / 0x400;
            $unit = 'K';
        } elseif ($size < 0x40000000) {
            $size_str = $size / 0x100000;
            $unit = 'M';
        } elseif ($size < 0x10000000000) {
            $size_str = $size / 0x40000000;
            $unit = 'G';
        } elseif ($size < 0x4000000000000) {
            $size_str = $size / 0x10000000000;
            $unit = 'T';
        } else {
            $size_str = $size / 0x4000000000000;
            $unit = 'P';
        }
        $size_str = round($size_str, $precision);
        return $size_str . $unit;
    }

    /**
     * 删除一个目录
     * @param string $dir 目录
     * @return bool
     */
    public static function delDir($dir)
    {
        if (!is_dir($dir) || !is_writable($dir)) {
            return false;
        }
        return self::doDelDir($dir);
    }

    /**
     * 删除目录
     * @param string $dir
     * @return bool
     */
    private static function doDelDir($dir)
    {
        $dh = opendir($dir);
        while ($file = readdir($dh)) {
            if ('.' === $file || '..' === $file) {
                continue;
            }
            $full_file = $dir . '/' . $file;
            if (is_dir($full_file)) {
                self::doDelDir($full_file);
            } else {
                if (!unlink($full_file)) {
                    return false;
                }
            }
        }
        closedir($dh);
        return rmdir($dir);
    }

    /**
     * 拷贝目录
     * @param string $src
     * @param string $dst
     * @return bool
     */
    public static function copyDir($src, $dst)
    {
        if (!is_string($src) || empty($src || !is_string($dst) || empty($dst))) {
            return false;
        }
        if (!is_dir($src)) {
            return false;
        }
        $src = realpath($src);
        if (!is_dir($dst) && !mkdir($dst, 0755, true)) {
            return false;
        }
        $dst = realpath($dst);
        if ($src === $dst) {
            return false;
        }
        //如果 将 父级 目录 拷到 子目录, 不允许
        if (0 === strpos($dst, $src . '/')) {
            return false;
        }
        return self::doCopyDir($src, $dst);
    }

    /**
     * 目录拷贝
     * @param string $src
     * @param string $dst
     * @return bool
     */
    private static function doCopyDir($src, $dst)
    {
        $dir = opendir($src);
        if (!is_dir($dst) && !mkdir($dst, 0755, true)) {
            return false;
        }
        while (false !== ($file = readdir($dir))) {
            if ('.' === $file{0}) {
                continue;
            }
            $src_file = $src . '/' . $file;
            $dst_file = $dst . '/' . $file;
            if (is_dir($src_file)) {
                self::copyDir($src_file, $dst_file);
            } else {
                copy($src_file, $dst_file);
            }
        }
        closedir($dir);
        return true;
    }

    /**
     * 是否是浏览器 XMLHttpRequest发出的请求
     * @return bool
     */
    public static function isAjaxRequest()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && 'xmlhttprequest' === strtolower($_SERVER['HTTP_X_REQUESTED_WITH']);
    }

    /**
     * 将对象转数组,并且移除null值
     * @param object $object
     * @return array
     */
    public static function objectToArray($object)
    {
        $result = get_object_vars($object);
        foreach ($result as $key => $v) {
            if (null === $v) {
                unset($result[$key]);
            }
        }
        return $result;
    }

    /**
     * 获取请求的方式
     * @return string
     */
    public static function getHttpMethod()
    {
        if (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
            return strtoupper($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']);
        } else {
            return isset($_SERVER['REQUEST_METHOD']) ? strtoupper($_SERVER['REQUEST_METHOD']) : 'GET';
        }
    }

}
