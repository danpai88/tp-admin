<?php
namespace app\common\utils;

use think\facade\Request;
use think\facade\Route;

class ToolUtil
{
    /**
     * 解析jsonp数据为 array
     *
     * @param string $jsonp
     * @param bool   $assoc
     * @return mixed
     */
    public static function jsonp_decode($jsonp = '', $assoc = true)
    {
        $jsonp = trim($jsonp);
        if(isset($jsonp[0]) && $jsonp[0] !== '[' && $jsonp[0] !== '{') {
            $begin = strpos($jsonp, '(');
            if(false !== $begin)
            {
                $end = strrpos($jsonp, ')');
                if(false !== $end)
                {
                    $jsonp = substr($jsonp, $begin + 1, $end - $begin - 1);
                }
            }
        }
        return json_decode($jsonp, $assoc);
    }

    public static function getLangs()
    {
        global $wpcc_langs;
        return array_keys($wpcc_langs);
    }

    public static function buildLangUrl(string $lang)
    {
        $host = Request::host();
        $full_url = Request::url();

        if(CURRENT_LANG){
            $full_url = str_replace('/'.CURRENT_LANG, '', $full_url);
        }
        return Request::scheme().'://'.$host.'/'.$lang.$full_url;
    }

    /**
     * 简繁体转换
     * @param string $content
     * @param string $lang
     * @return mixed
     */
    public static function zhconversion($content = '', $lang = '')
    {
        global $wpcc_langs;

        if(!$lang){
            $language = strtolower(Request::header('Accept-Language'));
            if($language){
                list($lang,) = explode(',', $language);
            }
            $lang = CURRENT_LANG ? CURRENT_LANG : $lang;
        }

        $func = 'zhconversion_hant';
        if(isset($wpcc_langs[$lang])){
            $func = $wpcc_langs[$lang][0];
        }
        return call_user_func($func, $content);
    }

    /**
     * 切割字符串为数组
     * @param string $str 待切割字符串
     * @param integer $num 每个数组包含几个元素
     * @return array
     */
    public static function mb_str_split($str, int $num = 1)
    {
        $str = preg_replace('# #', '', $str);

        $len = mb_strlen($str);
        if($len < 2){
            return [$str];
        }

        $arr = [];
        $page = ceil($len/$num);
        for($i = 1; $i <= $page; $i++){
            $arr[] = mb_substr($str, ($i-1)*$num, $num);
        }
        return $arr;
    }

    /**
     * 解码不规则的字符串
     *
     * @param string $str
     * @return string
     */
    public static function parseUnIrregularStr($str = '')
    {
        $str = preg_replace('# #', '', $str);
        if(stristr($str, '\x')){
            $str = urldecode(str_replace('\x', '%', $str));
        }
        return $str;
    }

    /**
     * 生成百度查询链接
     * @param string $key
     * @return string
     */
    public static function build_baidu_url($key = '')
    {
        $key = iconv('utf-8', 'gbk//IGNORE', $key);
        $url = sprintf('http://opendata.baidu.com/post/s?wd=%s&p=mini&rn=30', urlencode($key));
        return $url;
    }

    /**
     * 获取当前日期
     * @return false|string
     */
    public static function current_date()
    {
        return date('Y-m-d H:i:s');
    }

    /**
     * 格式化日期
     * @param string $str
     * @return false|string
     */
    public static function formatShortDate($str = '')
    {
        if(is_numeric($str)){
            return date('Y-m-d', $str);
        }
        return date('Y-m-d', strtotime($str));
    }

    /**
     * 将csv文件读取成数组
     * @param string $filename
     * @return array|bool
     */
    public static function csv2array(string $filename)
    {
        $csv = [];
        $all_lines = @file( $filename );
        if( !$all_lines ) {
            return $csv;
        }

        foreach ($all_lines as $line) {
            $line = iconv('gb2312','utf-8', $line);
            $csv[] = explode(',', $line);
        }
        return $csv;
    }

    public static function isMobile()
    {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return true;
        }

        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset($_SERVER['HTTP_VIA'])) {
            // 找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }

        // 脑残法，判断手机发送的客户端标志,兼容性有待提高。其中'MicroMessenger'是电脑微信
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = array('nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile','MicroMessenger');
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return true;
            }
        }

        // 协议法，因为有可能不准确，放到最后判断
        if (isset ($_SERVER['HTTP_ACCEPT'])) {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return true;
            }
        }
        return false;
    }
}