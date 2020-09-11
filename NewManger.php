<?php


namespace wslibs\news;

use wslibs\news\i\INewUpload;

class NewManger
{
    private static $upload_handier = null;

    public static function setUploadHandier(INewUpload $handler) {
        self::$upload_handier = $handler;
    }

    public static function __getUploadHandier(): INewUpload {
        if(is_null(self::$upload_handier)){
            self::$upload_handier = new uploadHandier();
        }
        return self::$upload_handier;
    }
}