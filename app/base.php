<?php


namespace wslibs\news\app;


use epii\admin\center\addons_controller;
use epii\admin\center\admin_center_addons_controller;
use epii\admin\center\admin_center_controller;
use epii\server\Tools;
use epii\template\engine\EpiiViewEngine;

class base extends addons_controller
{
//    public function init()
//    {
//        $this->static_url_pre;
//        parent::init();
//        $engine = new EpiiViewEngine();
//        $engine->init(["tpl_dir" => __DIR__ . "/../view", "cache_dir" => Tools::getRuntimeDirectory() . "/article_view"]);
//        $this->setViewEngine($engine);
//    }

    static public $treeList = array(); //存放无限分类结果如果一页面有多个无限分类可以使用 Tool::$treeList = array(); 清空

    /**
     * 无限级分类
     * @access public
     * @param Array $data //数据库里获取的结果集
     * @param Int $pid
     * @param Int $count //第几级分类
     * @return Array $treeList
     */
    static public function tree(&$data, $pid = 0, $count = 1)
    {
        foreach ($data as $key => $value) {
            if ($value['pid'] == $pid) {
                $value['count'] = $count;
                self::$treeList [] = $value;
                unset($data[$key]);
                self::tree($data, $value['id'], $count + 1);
            }
        }
        return self::$treeList;
    }
}