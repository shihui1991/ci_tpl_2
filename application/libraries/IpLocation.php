<?php
/**
 *  IP获取位置信息
 * @user 罗仕辉
 * @create 2019-02-11
 */

namespace libraries;

class IpLocation {

    private $fp; # 文件句柄
    private $firstip;
    private $lastip;
    private $totalip;
    private $provinces = array(
        "黑龙江省",
        "辽宁省",
        "吉林省",
        "河北省",
        "河南省",
        "湖北省",
        "湖南省",
        "山东省",
        "山西省",
        "陕西省",
        "安徽省",
        "浙江省",
        "江苏省",
        "福建省",
        "广东省",
        "海南省",
        "四川省",
        "云南省",
        "贵州省",
        "青海省",
        "甘肃省",
        "江西省",
        "台湾省",
        "内蒙古",
        "宁夏",
        "新疆",
        "西藏",
        "广西",
        "北京市",
        "上海市",
        "天津市",
        "重庆市",
        "香港",
        "澳门",
    );

    public function __construct() {

        $file = __DIR__.'/qqwry.dat';

        $this->fp = 0;
        if (($this->fp = fopen($file, 'rb')) !== false) {
            $this->firstip = $this->getlong();
            $this->lastip = $this->getlong();
            $this->totalip = ($this->lastip - $this->firstip) / 7;
        }
    }

    /** 静态方法生成实例
     * @return IpLocation
     */
    static public function instance()
    {
        return new static();
    }

    /** 将读取的little-endian编码的4个字节转化为长整型数
     * @return mixed
     */
    private function getlong() {
        $result = unpack('Vlong', fread($this->fp, 4));
        return $result['long'];
    }

    /** 将读取的little-endian编码的3个字节转化为长整型数
     * @return mixed
     */
    private function getlong3() {
        $result = unpack('Vlong', fread($this->fp, 3) . chr(0));
        return $result['long'];
    }

    /** 将ip打包为二进制
     * @param $ip
     * @return string
     */
    private function packip($ip) {
        return pack('N', intval(ip2long($ip)));
    }

    /**
     * @param string $data
     * @return string
     */
    private function getstring($data = "") {
        do{
            $char = fread($this->fp, 1);
            $data .= $char;
        }while(ord($char) > 0);

        return $data;
    }

    /**
     * @return string
     */
    private function getarea() {
        $byte = fread($this->fp, 1);
        switch (ord($byte)) {
            case 0:
                $area = "";
                break;
            case 1:
            case 2:
                fseek($this->fp, $this->getlong3());
                $area = $this->getstring();
                break;
            default:
                $area = $this->getstring($byte);
                break;
        }
        return $area;
    }

    /** 通过IP获取位置信息
     * @param string $ip
     * @return null
     */
    public function getlocation($ip = '') {
        if (!$this->fp){
            return null;
        }
        if (empty($ip)){
            $ip = get_client_ip();
        }
        $location['ip'] = gethostbyname($ip);
        $ip = $this->packip($location['ip']);
        $l = 0;
        $u = $this->totalip;
        $findip = $this->lastip;
        while ($l <= $u) {
            $i = floor(($l + $u) / 2);
            fseek($this->fp, $this->firstip + $i * 7);
            $beginip = strrev(fread($this->fp, 4));
            if ($ip < $beginip) {
                $u = $i - 1;
            } else {
                fseek($this->fp, $this->getlong3());
                $endip = strrev(fread($this->fp, 4));
                if ($ip > $endip) {
                    $l = $i + 1;
                } else {
                    $findip = $this->firstip + $i * 7;
                    break;
                }
            }
        }
        fseek($this->fp, $findip);
        $location['beginip'] = long2ip($this->getlong());
        $offset = $this->getlong3();
        fseek($this->fp, $offset);
        $location['endip'] = long2ip($this->getlong());
        $byte = fread($this->fp, 1);
        switch (ord($byte)) {
            case 1:
                $countryOffset = $this->getlong3();
                fseek($this->fp, $countryOffset);
                $byte = fread($this->fp, 1);
                switch (ord($byte)) {
                    case 2:
                        fseek($this->fp, $this->getlong3());
                        $location['country'] = $this->getstring();
                        fseek($this->fp, $countryOffset + 4);
                        $location['area'] = $this->getarea();
                        break;
                    default:
                        $location['country'] = $this->getstring($byte);
                        $location['area'] = $this->getarea();
                        break;
                }
                break;
            case 2:
                fseek($this->fp, $this->getlong3());
                $location['country'] = $this->getstring();
                fseek($this->fp, $offset + 8);
                $location['area'] = $this->getarea();
                break;
            default:
                $location['country'] = $this->getstring($byte);
                $location['area'] = $this->getarea();
                break;
        }
        if (trim($location['country']) == 'CZ88.NET') {
            $location['country'] = '未知';
        }
        if (trim($location['area']) == 'CZ88.NET') {
            $location['area'] = '';
        }
        $location['country'] = @iconv('gbk', 'utf-8', $location['country']);
        $location['area'] = @iconv('gbk', 'utf-8', $location['area']);
        foreach ($this->provinces as $v) {
            if (strpos($location['country'], $v) === 0) {
                $location['province'] = $v;
                $location['city'] = str_replace($v, '', $location['country']);
                break;
            }
        }
        if (empty($location['province']))
            $location['province'] = $location['country'];
        if (empty($location['city']))
            $location['city'] = $location['country'];
        if (in_array($location['province'], $this->provinces)) {
            $location['country'] = "中国";
        }
        return $location;
    }

    /**
     * 关闭文件
     */
    public function __destruct() {
        if ($this->fp) {
            fclose($this->fp);
        }
        $this->fp = 0;
    }
}


/** 获取IP
 * @return array|false|string
 */
function get_client_ip() {
    if (getenv('HTTP_CLIENT_IP')) {
        $onlineip = getenv('HTTP_CLIENT_IP');
    } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
        $onlineip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif (getenv('REMOTE_ADDR')) {
        $onlineip = getenv('REMOTE_ADDR');
    } else {
        $onlineip = $_SERVER['REMOTE_ADDR'];
    }
    return $onlineip;
}
