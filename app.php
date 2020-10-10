<?php

namespace wslibs\news;

use epii\admin\center\libs\AddonsApp;

class app extends AddonsApp
{

    public function install(): bool
    {
        // TODO: Implement install() method.
        $pid = $this->addMenu(0,"文章管理","");
        $this->addMenu($pid,"标签管理","?app=tags@index&__addons=wslibs/news");
        $this->addMenu($pid,"分类管理","?app=classify@index&__addons=wslibs/news");
        $this->addMenu($pid,"文章列表","?app=articles@index&__addons=wslibs/news");
        $this->execSqlFile(__DIR__."/news.sql","epii_");
        $this->copyDirToStatic(__DIR__."/wangEditor");
        return true;
    }

    public function update($new_version, $old_version): bool
    {
        // TODO: Implement update() method.
        return true;
    }

    public function onOpen(): bool
    {
        // TODO: Implement onOpen() method.
        return true;
    }

    public function onClose(): bool
    {
        // TODO: Implement onClose() method.
        return true;
    }
}