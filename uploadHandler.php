<?php


namespace wslibs\news;

use wslibs\news\i\INewUpload;

class uploadHandler implements INewUpload
{
    public function onFile($info): string
    {
        $info['path'] = str_replace("\\","/",strtolower($info['path']));
        if(strpos($info['path'],'http') !== false || strpos($info['path'],'https') !== false){
            $path = $info['path'];
        }else{
            $path = "http://".$_SERVER['HTTP_HOST'] ."/upload/". $info['path'];
        }

        return json_encode(["errno" => 0, "data" => array_values([$path])]);
    }
}