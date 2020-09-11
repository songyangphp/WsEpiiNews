<?php


namespace wslibs\news;

use epii\ui\upload\driver\LocalFileUploader;
use wslibs\storage\CunChuIO;
use wslibs\news\i\INewUpload;

class uploadHandier implements INewUpload
{
    private $_chunchu_pre = "https://wszxstore.blob.core.chinacloudapi.cn/jiazheng/uploads/";

    public function onFile($info): string
    {
        CunChuIO::setConfig(array(
            "storage_list" => ['wszxstore'],
            "accountname" => 'wszxstore',
            "accountkey" => 'LgYWaS8nxag0JVYzCocB+cgUvC2Dg+6g9xfwTSbSmSb13c7EjRTjw+7uz4krW1cWjunWxdhQCeGGplay95/Oyg==',
            "storename" => 'wszxstore',
            "rongqi" => 'jiazheng',
        ));

        $info['path'] = str_replace("\\","/",strtolower($info['path']));
        $path = $path = LocalFileUploader::getInitUploadDir() ."/". $info['path'];

        CunChuIO::uploadContent($info['path'],file_get_contents($path));

        $url = $this->_chunchu_pre.$info['path'];

        return json_encode(["uploaded" => true, "url" => $url]);
    }
}