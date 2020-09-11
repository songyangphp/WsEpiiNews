<?php


namespace wslibs\news;

use wslibs\news\i\INewUpload;

class uploadHandler implements INewUpload
{
    public function onFile($info): string
    {
        $info['path'] = str_replace("\\","/",strtolower($info['path']));
        $path = "http://".$_SERVER['HTTP_HOST'] ."/upload/". $info['path'];

        return json_encode(["uploaded" => true, "url" => $path]);
    }
}