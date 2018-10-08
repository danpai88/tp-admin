<?php
namespace app\common\utils;

use think\facade\Env;

class CyFtp
{
    public static $handle = null;

    /**
     * @return resource
     * @throws
     */
    public function connect()
    {
        $ftpHost = Env::get('ftp_host');
        $ftpPort = Env::get('ftp_port');
        $ftpUser = Env::get('ftp_user');
        $ftpPass = Env::get('ftp_pass');

        $ftpHandle = ftp_connect($ftpHost, $ftpPort);
        ftp_login($ftpHandle, $ftpUser, $ftpPass);
        ftp_pasv($ftpHandle, true);
        return $ftpHandle;
    }

    public static function getInstance()
    {
        if(is_null(self::$handle)){
            self::$handle = (new self())->connect();
        }
        return self::$handle;
    }

    public static function remoteSaveFtp($module, $url)
    {
        $param = [
            'url' => $url,
            'save_path' => date('Y/m/d').'/'.md5($url).'.jpg',
            'root_path' => $module,
        ];
        $newurl = 'http://cdn-167.lujuba.com/script/down.php?'.http_build_query($param);
        return file_get_contents($newurl);
    }

    /**
     * 上传到ftp
     * @param string $localPath 本地路径
     * @param string $FtpPath 远程ftp路径
     * @return bool
     */
    public static function saveToFtp($localPath, $FtpPath)
    {
        $ftpHandle = static::getInstance();

        $ftpDir = ltrim($FtpPath, '/');
        $tmp = 'toutiao';
        $ftpDir_arr = explode('/', $ftpDir);
        $ftpFile = array_pop($ftpDir_arr);

        foreach ($ftpDir_arr as $dir){
            $tmp .= '/'.$dir;
            if(!static::ftp_is_dir($ftpHandle, $tmp)){
                ftp_mkdir($ftpHandle, $tmp);
                ftp_chmod($ftpHandle, 0755, $tmp);
            }
        }

        ftp_chdir($ftpHandle, $tmp);

        $fpHandle = fopen($localPath, 'r');

        $uploadRet = ftp_fput($ftpHandle, $ftpFile, $fpHandle, FTP_BINARY);
        ftp_close($ftpHandle);
        fclose($fpHandle);

        return $uploadRet;
    }

    /**
     * 判断FTP服务器上 某个目录是否存在
     * @param resource $ftp ftp句柄
     * @param string $dir 要检查的目录（相对路径）
     * @return bool
     */
    public static function ftp_is_dir($ftp, $dir)
    {
        $pushd = ftp_pwd($ftp);
        if ($pushd !== false && @ftp_chdir($ftp, $dir))
        {
            ftp_chdir($ftp, $pushd);
            return true;
        }
        return false;
    }
}