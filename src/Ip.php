<?php

namespace FFan\Std\Common;
/**
 * Class Ip IP操作相关函数
 * @package FFan\Std\Common
 */
class Ip
{
    /**
     * 本机 127.0.0.1
     */
    const TYPE_LOCAL = 1;
    /**
     * A类 10.255.255.255
     */
    const TYPE_A = 2;
    /**
     * B类 172.31.255.255
     */
    const TYPE_B = 4;
    /**
     * C类 192.168.255.255
     */
    const TYPE_C = 8;

    /**
     * 获取客户端IP
     * @return string
     */
    public static function get()
    {
        static $client_ip;
        if ($client_ip) {
            return $client_ip;
        }
        $except = 'unknown';
        if (isset($_SERVER['HTTP_CLIENT_IP']) && 0 != strcasecmp($_SERVER['HTTP_CLIENT_IP'], $except)) {
            $client_ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && 0 != strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $except)) {
            $client_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            //特殊的情况，ip地址中间有逗
            $pos = strpos($client_ip, ',');
            if (false !== $pos) {
                $tmp_ip = substr($client_ip, 0, $pos);
                //尝试获取得第一个， 如果 第一个是unknown，再做一个比较复杂的操作
                //没有一开始就做复杂的操作，性能更好一些
                if ('unknown' === $tmp_ip) {
                    $ip_array = explode(',', $client_ip);
                    foreach ($ip_array as $ip) {
                        if ('unknown' != $ip) {
                            $client_ip = trim($ip);
                            break;
                        }
                    }
                } else {
                    $client_ip = $tmp_ip;
                }
            }
        } elseif (isset($_SERVER['REMOTE_ADDR']) && 0 != strcasecmp($_SERVER['REMOTE_ADDR'], $except)) {
            $client_ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $client_ip = '127.0.0.1';
        }
        return $client_ip;
    }

    /**
     * 获取客户端IP地址，转换成数字
     * @return int
     */
    public static function getLong()
    {
        return ip2long(self::get());
    }

    /**
     * 是否是内网地址
     * @param string|int $ip_address 传入IP地址
     * @param int $mod 检测
     * @return bool
     */
    public static function isInternal($ip_address, $mod = self::TYPE_LOCAL | self::TYPE_A | self::TYPE_B | self::TYPE_C)
    {
        if (!is_int($ip_address)) {
            $ip_address = ip2long((string)$ip_address);
        }
        $mod = (int)$mod;
        //传入IP地址非法 或者 mod 小于等于0
        if (false === $ip_address || $mod <= 0) {
            return false;
        }
        //本机地址 127.0.0.1
        if (($mod & self::TYPE_LOCAL) && (0x7F000001 === $ip_address)) {
            return true;
        }
        //A类内网判断
        if (($mod & self::TYPE_A) && ($ip_address >> 24 === 0xAFFFFFF >> 24)) {
            return true;
        }
        //B类内网判断
        if (($mod & self::TYPE_B) && ($ip_address >> 20 === 0xAC1FFFFF >> 20)) {
            return true;
        }
        //C类内网判断
        if (($mod & self::TYPE_C) && ($ip_address >> 16 === 0xC0A8FFFF >> 16)) {
            return true;
        }
        return false;
    }
}
