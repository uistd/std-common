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
    public static function fixWithRuntimePath($path )
    {
        $path = trim($path);
        if (DIRECTORY_SEPARATOR !== $path[strlen($path) - 1]) {
            $path .= DIRECTORY_SEPARATOR;
        }
        if (DIRECTORY_SEPARATOR !== $path[0]) {
            $path = Env::getRuntimePath() . $path;
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
}
