<?php

namespace UiStd\Common;

/**
 * Class Validator 数据有效性检验
 * @package UiStd\Common
 */
class Validator
{
    /**
     * 是否是电话号码
     * @param string $mobile 电话号码
     * @return bool
     */
    public static function isMobile($mobile)
    {
        return preg_match('/^1[34578]{1}\d{9}$/', $mobile) > 0;
    }

    /**
     * 是否是邮箱地址
     * @param string $email 邮箱
     * @return bool
     */
    public static function isEmail($email)
    {
        return preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', $email) > 0;
    }

    /**
     * 是否是身份证
     * @param string $id_card 身份证号
     * @return string
     */
    public static function isIdCard($id_card)
    {
        if (!is_string($id_card)) {
            return false;
        }
        $id_card = trim($id_card);
        // 只能是18位
        if (strlen($id_card) != 18) {
            return false;
        }
        // 取出本体码
        $id_card_base = substr($id_card, 0, 17);
        // 取出校验码
        $verify_code = $id_card{17};
        if ('x' === $verify_code) {
            $verify_code = 'X';
        }
        // 加权因子
        $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);

        // 校验码对应值
        $verify_code_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
        // 根据前17位计算校验码
        $total = 0;
        for ($i = 0; $i < 17; $i++) {
            $total += $id_card_base{$i} * $factor[$i];
        }
        // 取模
        $mod = $total % 11;
        // 比较校验码
        return $verify_code === $verify_code_list[$mod];
    }

    /**
     * 是否是url地址
     * @param string $url
     * @return bool
     */
    public static function isUrl($url)
    {
        return preg_match('/(http|https|ftp|file){1}(:\/\/)?([\da-z-\.]+)\.([a-z]{2,6})([\/\w \.-?&%-=]*)*\/?/', $url) > 0;
    }

    /**
     * 验证价格
     * @param float $price 价格
     * @return bool
     */
    public static function isPrice($price)
    {
        return preg_match('/^-?[\d]+(\.[\d]{0,2})?$/', $price) > 0;
    }

    /**
     * 验证车牌号是否可用
     * @param string $plateNumber
     * @return bool
     */
    public static function isPlateNumber($plateNumber)
    {
        //注意，新能源车是车牌 比 普通车牌 多一位
        return preg_match('/^[京,津,渝,沪,冀,晋,辽,吉,黑,苏,浙,皖,闽,赣,鲁,豫,鄂,湘,粤,琼,川,贵,云,陕,秦,甘,陇,青,台,蒙,桂,宁,新,藏,澳,军,海,航,警]{1}[A-Za-z][\s-]?[0-9a-zA-Z]{5,6}$/u', $plateNumber) > 0;
    }

    /**
     * 是否是日期时间
     * @param string $date_str
     * @return bool
     */
    public static function isDate($date_str)
    {
        return preg_match('/^\d{4}((-|\/)\d{1,2}){2}$/', $date_str) > 0;
    }

    /**
     * 是否是日期 时间格式
     * @param string $time_str
     * @return bool
     */
    public static function isDateTime($time_str)
    {
        return preg_match('/^\d{4}((-|\/)\d{1,2}){2} \d{1,2}:\d{1,2}:\d{1,2}$/', $time_str) > 0;
    }

    /**
     * 是否是QQ号
     * @param string $qq
     * @return bool
     */
    public static function isQQ($qq)
    {
        return preg_match('/^[1-9][0-9]{4,}$/', $qq) > 0;
    }

    /**
     * 是否是邮编
     * @param string $code
     * @return bool
     */
    public static function isZipCode($code)
    {
        return preg_match('/^[1-9]\d{5}(?!\d)$/', $code) > 0;
    }

    /**
     * 是否是IP地址
     * @param string $ip
     * @return bool
     */
    public static function isIp($ip)
    {
        return false !== ip2long($ip);
    }

    /**
     * 电话号码
     * @param string $phone_number
     * @return bool
     */
    public static function isPhone($phone_number)
    {
        return preg_match('/^\d{3}-\d{8}|\d{4}-\d{7}$/', $phone_number) > 0;
    }

    /**
     * 是否md5串
     * @param string $str
     * @return bool
     */
    public static function isMd5($str)
    {
        return preg_match('/^[a-zA-Z\d]{32}$/', $str) > 0;
    }
}
