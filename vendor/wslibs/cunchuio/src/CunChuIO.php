<?php

/**
 * Created by PhpStorm.
 * User: mrren
 * Date: 2017/4/15
 * Time: 下午5:32
 */

namespace wslibs\storage;
use WindowsAzure\Common\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\CreateBlobOptions;
use WindowsAzure\Common\ServicesBuilder;

class CunChuIO
{
    public static $storename;
    public static $rongqi;
    public static $storage_list;
    public static $accountname ;
    public static $accountkey;

    public static function setConfig($config)
    {
        foreach ($config as $k => $v){
            if(empty($v)){
                exit("storage ". $k. " is null");
            }
        }
        self::$storename = $config['storename'];
        self::$rongqi = $config['rongqi'];
        self::$storage_list = $config['storage_list'];
        self::$accountname = $config['accountname'];
        self::$accountkey = $config['accountkey'];
    }

    public static function uploadImageFile($d_pathtofilename, $o_file, $contenttype = null, $recalImage = array(1024, 1024))
    {
        if (!$recalImage) {
            $recalImage = array(1024, 1024);
        }

        if (!self::isImage($d_pathtofilename)) {
            $content = file_get_contents($o_file);
            $out =  self::uploadContent($d_pathtofilename, $content);
            unset($content);
            if (is_uploaded_file($o_file)) {
                unlink($o_file);
            }
            return $out;
        }

        $content = "";
        if (is_uploaded_file($o_file)) {//压缩图片大小
            $content = self::scaleImageFileToBlob($o_file, $recalImage);
        } else {
            $content = file_get_contents($o_file);
        }

        $tmp_arr = explode(".", $o_file);
        $file_ext = strtolower($tmp_arr[count($tmp_arr) - 1]);

        return self::uploadImageContent($d_pathtofilename, $content, $contenttype ? $contenttype : $file_ext);
    }

    public static function uploadImageContent($d_pathtofilename, $content, $contenttype)
    {
        $d_pathtofilename = strtolower($d_pathtofilename);
        $index = strpos($d_pathtofilename, "uploads/");

        if ($index !== false && $index >= 0 && $index < 4) {
            $d_pathtofilename = substr($d_pathtofilename, $index + 8);
        }

        $blobRestProxy = self::getBlobRestProxy(self::$storename);
        $blob_option = new CreateBlobOptions();
        $blob_option->setContentType(self::getContentTypeFromFileExt($contenttype));
        try {
            return $blobRestProxy->createBlockBlob(self::$rongqi, "uploads/".$d_pathtofilename, $content, $blob_option);
        } catch (ServiceException $e) {
            return false;
        }
    }

    public static function uploadContent($d_pathtofilename, $content, $contianer = "uploads")
    {
        $d_pathtofilename = strtolower($d_pathtofilename);
        $blobRestProxy = self::getBlobRestProxy(self::$storename);
        $blob_option = new  CreateBlobOptions();
        $blob_option->setContentType(self::getContentTypeFromFileExt($d_pathtofilename));
        try {
            if($_GET['debug']){
                var_dump(self::$rongqi);
                var_dump($contianer."/".$d_pathtofilename);
                var_dump($content);
                var_dump($blob_option);
                die;
            }
            return $blobRestProxy->createBlockBlob(self::$rongqi, $contianer."/".$d_pathtofilename, $content, $blob_option);
        } catch (ServiceException $e) {
            return false;
        }
    }

    public static function isImage($d_name)
    {
        $file_ext_tmp = explode(".", strtolower($d_name));
        $ext = $file_ext_tmp[count($file_ext_tmp) - 1];
        $arr = array(
            "gif",
            "png",
            "jpg",
            "jpeg",
        );
        if (in_array($ext, $arr)) {
            return true;
        }
        return false;
    }

    public static function getContentTypeFromFileExt($file_ext)
    {
        $file_ext_tmp = explode(".", strtolower($file_ext));
        $ext = $file_ext_tmp[count($file_ext_tmp) - 1];
        $arr = array(
            "gif" => "image/gif",
            "png" => "image/png",
            "jpg" => "image/jpeg",
            "jpeg" => "image/jpeg",
            "html" => "text/html",
            "mp4"=>"video/mpeg4",
            "pdf" => "application/pdf",
        );
        if (in_array($ext, array_keys($arr))) {
            return $arr[$ext];
        }
        return "application/octet-stream";
    }

    public static function getString($pathtofilename)
    {
        $blobRestProxy = self::getBlobRestProxy(self::$storename);
        try {
            // Get blob.
            $blob = $blobRestProxy->getBlob(self::$rongqi, $pathtofilename);
            return $blob->getContentStream();
        } catch (ServiceException $e) {
            return "";
        }
    }

    public static function getContent($pathtofilename)
    {
        $blobRestProxy = self::getBlobRestProxy(self::$storename);

        try {
            // Get blob.
            $blob = $blobRestProxy->getBlob(self::$rongqi, $pathtofilename);
            return $blob->getContentStream();
        } catch (ServiceException $e) {

            return "";
        }
    }

    public static function getBolbTmpFile($pathtofilename,$name='')
    {
        $blobRestProxy = self::getBlobRestProxy(self::$storename);
        try {
            $blob = $blobRestProxy->getBlob(self::$rongqi, $pathtofilename);
            $pathtofilename = str_replace("/","_",$pathtofilename);
            $pathtofilename = substr($pathtofilename,strlen("uploads/sign/gongzheng/")-strlen($pathtofilename));
            $tmpfile = NAS_WSZX_DIR."tmp/".$pathtofilename;

            file_put_contents($tmpfile, $blob->getContentStream());
            return $tmpfile;
        } catch (ServiceException $e) {

            return null;
        }
    }
    public static function delTmpFile($tmpfile)
    {
        if(strpos($tmpfile,NAS_WSZX_DIR."tmp/")===0)
        {
            unlink($tmpfile);
        }
    }



    public static function getBlobConnectionString($storagename)
    {
        if(!self::isRealStoreage($storagename))
        {
            return false;
        }

        return "BlobEndpoint=http://{$storagename}.blob.core.chinacloudapi.cn/;QueueEndpoint=http://{$storagename}.queue.core.chinacloudapi.cn/;TableEndpoint=http://{$storagename}.table.core.chinacloudapi.cn/;AccountName=" . self::$accountname . ";AccountKey=" . self::$accountkey;

    }
    public static function getBlobRestProxy($storagename)
    {
        if(! ($connectionString = self::getBlobConnectionString($storagename)))
        {
            return null;
        }
        return  ServicesBuilder::getInstance()->createBlobService($connectionString);
    }


    private static function isRealStoreage($storagename)
    {
        return in_array($storagename, self::$storage_list);
    }


    public static function scaleImageFileToBlob($file, $recalImage)
    {//修改大小
        $max_width = $recalImage[0];
        $max_height = $recalImage[1];
        list($width, $height, $image_type) = getimagesize($file);
        $x_ratio = $max_width / $width;
        $y_ratio = $max_height / $height;
        if (($width <= $max_width) && ($height <= $max_height)) {
//            $tn_width = $width;
//            $tn_height = $height;
            return file_get_contents($file);
        } elseif (($x_ratio * $height) < $max_height) {
            $tn_height = ceil($x_ratio * $height);
            $tn_width = $max_width;
        } else {
            $tn_width = ceil($y_ratio * $width);
            $tn_height = $max_height;
        }
        switch ($image_type) {
            case 1:
                $src = imagecreatefromgif($file);
                break;
            case 2:
                $src = imagecreatefromjpeg($file);
                break;
            case 3:
                $src = imagecreatefrompng($file);
                break;
            default:
                return '';
                break;
        }

        $tmp = imagecreatetruecolor($tn_width, $tn_height);

        /* Check if this image is PNG or GIF, then set if Transparent*/
        if (($image_type == 1) OR ($image_type == 3)) {
            imagealphablending($tmp, false);
            imagesavealpha($tmp, true);
            $transparent = imagecolorallocatealpha($tmp, 255, 255, 255, 127);
            imagefilledrectangle($tmp, 0, 0, $tn_width, $tn_height, $transparent);
        }
        imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tn_width, $tn_height, $width, $height);

        /*
         * imageXXX() only has two options, save as a file, or send to the browser.
         * It does not provide you the oppurtunity to manipulate the final GIF/JPG/PNG file stream
         * So I start the output buffering, use imageXXX() to output the data stream to the browser,
         * get the contents of the stream, and use clean to silently discard the buffered contents.
         */
        ob_start();

        switch ($image_type) {
            case 1:
                imagegif($tmp);
                break;
            case 2:
                imagejpeg($tmp, NULL, 75);
                break; // best quality
            case 3:
                imagejpeg($tmp, NULL, 75);
                break; // no compression
            default:
                echo '';
                break;
        }

        $final_image = ob_get_contents();

        ob_end_clean();

        return $final_image;
    }
}