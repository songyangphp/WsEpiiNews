"# WsCunchuIO" 
<br>
微软云存储SDK
<hr>
初始化方法(全局有效):
<br>
CunChuIO::getConfig(array(
    "storage_list" => ,
    "accountname" => ,
    "accountkey" => ,
    "storename" => ,
    "rongqi" => ,
));
<br>
使用方法:
<br>
CunChuIO::uploadContent("云端存储路径","要上传的文件(file_get_content('文件'))");