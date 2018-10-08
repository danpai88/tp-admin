<?php
namespace app\common\service;

use app\common\model\CountryTowns;
use app\common\utils\ToolUtil;
use think\facade\Request;
use GuzzleHttp\Client;
use think\facade\Env;
use app\common\model\Towns;
use think\model\Collection;

class YzbmService
{
    /**
     * 保存没有结果的关键词，后面人工去查
     * @param string $key
     */
    public static function saveNoResultKeyword($key = '')
    {
        $count = model('TownNoResult')->where('title', $key)->count();
        if($key && !$count){
            $save = model('TownNoResult');
            $save->title = $key;
            $save->local_url = Request::url(true);
            $save->baidu_url = ToolUtil::build_baidu_url($key);
            $save->created_at = ToolUtil::current_date();
            $ret = $save->save();
        }
    }

    /**
     * 从百度中搜索一次结果，只取百度的第一页
     * @param string $key
     * @return array
     */
    public static function searchFromBaidu(string $key)
    {
        $key = ToolUtil::parseUnIrregularStr($key);

        $url = ToolUtil::build_baidu_url($key);

        $content = static::http_get($url);
        $content = iconv("gbk", "utf-8//IGNORE", $content);

        $response = [];

        require_once ROOT_PATH.'/extend/simple_html_dom.php';
        $jquery = str_get_html($content);

        if(!$jquery->find('table', 0)){
            return $response;
        }

        $trs = $jquery->find('table', 0)->find('tr');
        foreach ($trs as $key => $tr){
            if($key === 0){
                continue;
            }

            $fullname = $tr->find('td', 1)->innerText();

            if($fullname){
                $response[] = [
                    'code'      => str_replace(['<em>', '</em>'], [''], $tr->find('td', 0)->innerText()),
                    'full_name' => str_replace(['<em>', '</em>', ' '], '', $fullname),
                ];
            }
        }
        return $response;
    }

    /**
     * 增加新的邮政编码记录
     * @param $code
     * @param $full_name
     * @param string $province
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @return integer
     */
    public static function addNewYzbm(string $code, string $full_name, string $province = '') : int
    {
        $tmp = mb_substr($full_name, 0, 2);

        $pid = static::getProvinceId($tmp, true);

        $count = Towns::where(['full_name' => $full_name])->find();
        $newId = 0;
        if(!$count){
            $data['pid'] = $pid[0];
            $data['code'] = $code;
            $data['full_name'] = $full_name;
            $newId = Towns::insert($data);
            dump('>>>> '.$full_name);
        }else{
            dump('@@@@ '.$full_name);
        }
        return $newId;
    }

    /**
     * 替换一些关键词，经常有用户输入一些额外关键词，
     * 如：
     *  XXXX邮编是多少？
     * @param string $keywords
     */
    public static function replaceSomeWords(string $keywords)
    {
        $fromKeys = Env::get('data.yzbm_replace', '');
        $fromKeys = explode(',', $fromKeys);

        foreach ($fromKeys as $fromKey) {
            $keywords = str_replace($fromKey, '', $keywords);
        }
        return $keywords;
    }

    /**
     * 执行http请求
     * @param string $url
     * @return string
     */
    public static function http_get(string $url)
    {
        $httpHandle = new Client(['timeout' => 3]);
        $response = $httpHandle->get($url);
        return $response->getBody()->getContents();
    }

    /**
     * 根据关键词 搜索邮编
     * @param string $key
     * @throws
     * @return Collection
     */
    public static function search($key = '', $pagesize = 40)
    {
        $key = ToolUtil::parseUnIrregularStr($key);

        $query = model('Towns')->field('code,full_name');

        if(is_numeric($key) && (mb_strlen($key) > 3 && mb_strlen($key) < 8)){
            //按邮政编号来查
            $query->whereLike('code', '%'.$key.'%');
        }else{
            //按地址关键词来查
            $key_arr = ToolUtil::mb_str_split($key);
            $whereLike = '%'.implode('%', $key_arr).'%';

            $pid = static::getProvinceId($key);
            if($pid){
                $query->whereIn('pid', $pid);
            }

            $query->whereLike('full_name', $whereLike);
        }

        $lists = $query->limit($pagesize)->select();
        return $lists;
    }

    /**
     * 获取省id
     * @param string $key
     * @throws
     */
    protected static function getProvinceId($key = '', $full_partten = false)
    {
        $pid = [];
        $tmp = mb_substr($key, 0, 2);

        $like = '%'.$tmp.'%';
        if($full_partten){
            $like = $tmp.'%';
        }

        $results = CountryTowns::whereLike('full_name', $like)->column('pid');

        if(count($results)){
            $pid = array_unique($results);
        }
        return $pid;
    }

    /**
     * 记录一下用户的lbs查询记录
     * @param $ip
     * @param $latitude
     * @param $longitude
     * @param $url
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function addLbsRecord($ip, $latitude, $longitude, $url)
    {
        $data = [
            'ip' => $ip,
            'latitude' => $latitude,
            'longitude' => $longitude,
        ];

        $count = model('Lbs')->where($data)->find();
        if(!$count){
            $data['created_at'] = ToolUtil::current_date();
            $data['url'] = $url;
            model('Lbs')->insert($data);
        }
    }

    /**
     * 可以使用前置索引的模糊查询
     * @param string $key
     * @return array|null|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function searchLikeWithIndex($province_city, $other)
    {
        $keyLike = $province_city;

        $map = env('data.map');
        if($map){
            $arr = explode(',', $map);
            foreach ($arr as $item) {
                list($from, $to) = explode(':', $item);
                $other = str_replace($from, $to, $other);
            }
        }

        $key_arr = ToolUtil::mb_str_split($other);
        $keyLike .= '%'.implode('%', $key_arr).'%';

        $query = model('Towns')->field('code,full_name');
        $query->whereLike('full_name', $keyLike);

        $pid = static::getProvinceId($province_city);
        if($pid){
            $query->whereIn('pid', $pid);
        }

        $result = $query->find();
        return $result;
    }

    /**
     * 从第三方api接口获取 位置信息
     *
     * @param string $latitude
     * @param string $longitude
     * @return array|mixed
     * @throws
     */
    public static function getLbs(string $latitude, string $longitude)
    {
        $response = static::lbsFromBaidu($latitude, $longitude);
        return $response;
    }

    /**
     * 从高德地图中经纬度反查地址
     * @param string $latitude
     * @param string $longitude
     * @return array|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    protected static function lbsFromAmap(string $latitude, string $longitude)
    {
        $url = 'https://restapi.amap.com/v3/geocode/regeo?key=60b9cd3b38debea9c72083fbc54b00f8&location='.$longitude.','.$latitude;
        $url.= '&output=json&roadlevel=1';

        //记录一下用户的lbs查询
        static::addLbsRecord(Request::ip(), $latitude, $longitude, $url);

        $body = static::http_get($url);
        $json = json_decode($body, true);

        $address = $json['regeocode']['addressComponent'];

        $province_city = $address['province'];
        if(!empty($address['city'])){
            $province_city .= $address['city'];
        }

        return [
            $province_city,
            !empty($address['district']) ? $address['district'] : '',
            !empty($address['township']) ? $address['township'] : '',
            !empty($address['streetNumber']['street']) ? $address['streetNumber']['street'] : '',
        ];
    }

    /**
     * 从百度地图中经纬度反查地址
     * @param string $latitude
     * @param string $longitude
     * @throws
     */
    protected static function lbsFromBaidu(string $latitude, string $longitude)
    {
        $url = 'http://api.map.baidu.com';
        $uri = '/geocoder/v2/';
        $param = [
            'location' => $latitude.','.$longitude,
            'output' => 'json',
            'pois' => 0,
            'ak' => 'U4YdGcq5c8VRdu3n5txzlpzSGBehrTzO',
        ];
        $sk = 'LFl5yICXpo2Qt8Qzudubq00tjzl6cbo7';

        $param['sn'] = static::baiduCaculateAKSN($sk, $uri, $param);

        $newurl = $url.$uri.'?'.http_build_query($param);

        //记录一下用户的lbs查询
        static::addLbsRecord(Request::ip(), $latitude, $longitude, $newurl);

        $body = static::http_get($newurl);
        $json = json_decode($body, true);
        $address = $json['result']['addressComponent'];

        $province_city = $address['province'];
        if($address['province'] !== $address['city']){
            $province_city .= $address['city'];
        }

        return [
            $province_city,
            $address['district'],
            $address['town'],
            $address['street']
        ];
    }

    /**
     * 百度api的加密算法
     * @param $sk
     * @param $url
     * @param $querystring_arrays
     * @param string $method
     * @return string
     */
    protected static function baiduCaculateAKSN($sk, $url, $querystring_arrays, $method = 'GET')
    {
        if ($method === 'POST'){
            ksort($querystring_arrays);
        }
        $querystring = http_build_query($querystring_arrays);
        return md5(urlencode($url.'?'.$querystring.$sk));
    }
}