<?php


namespace wslibs\news\app;


use epii\admin\center\addons_controller;
use epii\admin\center\config\Settings;
use epii\admin\ui\lib\epiiadmin\jscmd\Alert;
use epii\admin\ui\lib\epiiadmin\jscmd\CloseAndRefresh;
use epii\admin\ui\lib\epiiadmin\jscmd\JsCmd;
use epii\admin\ui\lib\epiiadmin\jscmd\Refresh;
use epii\server\Args;
use epii\ui\upload\AdminUiUpload;
use epii\ui\upload\driver\LocalFileUploader;
use think\Db;
use wslibs\cloud_upload\CloudFileUploaderManager;
use wslibs\news\NewManger;

class articles extends base
{

    private $_chunchu_pre = "https://wszxstore.blob.core.chinacloudapi.cn/jiazheng/uploads/";

    public function index()
    {
        $tags_id = trim(Args::params("tagid/d"));
        $pid = trim(Args::params("pid/d"));
        $list = Db::name("articles_tags")->select();
        $list_classify = Db::name("articles_classify")->where('status', 1)->select();
        foreach ($list_classify as $k => $v) {
            if ($list_classify[$k]['pid'] != 0) {
                if ($v['level'] == 3) {
                    $list_classify[$k]['name'] = '|------------' . $v['name'];
                } else {
                    $list_classify[$k]['name'] = '|------' . $v['name'];
                }
            }
        }
        $list_classify = $this->tree($list_classify);

        $this->assign("list_classify", $list_classify);
        $this->assign("list", $list);
        $this->assign("pid", $pid);
        $this->assign("tags_id", $tags_id);
        $this->adminUiDisplay();
    }

    /**
     * 表格数据
     */
    public function ajaxdata()
    {

        $title = trim(Args::params("title"));
        $pid = trim(Args::params("pid"));
        $tags_id = trim(Args::params("tagid"));
        $offset = trim(Args::params("offset"));
        $limit = trim(Args::params("limit"));
        $sql = "SELECT * from ".Db::getConfig("prefix")."articles_articles order by sort desc,id desc limit " . $offset . ',' . $limit;
        $where = '1';

        if (!empty($title)) {
            $where .= " and title like '%" . $title . "%'";
        }
        if(!empty($tags_id)){
            $where .= " and tags_id like '%," . $tags_id . ",%'";
        }
        if(!empty($pid)){
            $sons_list = Db::name('articles_classify')->whereLike('trees','%,'.$pid.',%')->select();
            if($sons_list){
                $where_pid =" and (";
                foreach ($sons_list as $ks => $vs){
                    if($ks == 0){
                        $where_pid .=  "classify_id like '%," . $vs['id'] . ",%'";
                    }else{
                        $where_pid .= " or classify_id like '%," . $vs['id'] . ",%'";
                    }
                }
                $where_pid .= " )";
                $where .= $where_pid;
            }
        }
        if($where){
            $sql = "SELECT * from ".Db::getConfig("prefix")."articles_articles where ".$where." order by sort desc,id desc limit " . $offset . ',' . $limit;
        }
        $data = Db::query($sql);
        if($where != '1'){
            $total = Db::name('articles_articles')->where($where)->count('id');
        }else{
            $total = Db::name('articles_articles')->count('id');
        }
        echo json_encode(['rows' => $data, 'total' => $total]);

//        $title = trim(Args::params("title"));
//        $tags_id = trim(Args::params("tagid"));
//        $map = [];
//        if (!empty($title)) {
//            $map[] = ["title", "LIKE", "%{$title}%"];
//        }
//        if (!empty($tags_id)) {
//            $map[] = ["tags_id", "LIKE", "%,{$tags_id},%"];
//        }
//        echo $this->tableJsonData('articles', $map, function($data) {
//            return $data;
//        });
    }


    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = Args::params("id/d");
            $title = trim(Args::params("title"));
            $desc = trim(Args::params("desc"));
            $status = trim(Args::params("status")) ?: 0;
            $sort = trim(Args::params("sort"));
            $content = Args::params("content");
            $image = Args::params("path")[0];
            $classify_id = Args::params("classify_id");
            $tags_id= Args::params("tags_id");
            $data = array();


            if($tags_id){
//                $tags_name = Db::name('tags')->field('name')->where('id','in',$tags_id)->select();
//                $tags_name_str = '';
//                if($tags_name){
//                    foreach ($tags_name as $k => $v){
//                        $tags_name_str  .= $v['name'].',';
//                    }
//                }
//                $data['tags_name'] = trim($tags_name_str,',');
//                $tags_id = implode(',',$tags_id);
                $data['tags_name'] = Args::params("tags_id_name");
            }

            if ($image) {
                $image = "http://" . $_SERVER['HTTP_HOST'] . "/upload/" . $image;
            } else {
                $image = "";
            }
            if ($classify_id) {
                $classify_name = Db::name('articles_classify')->field('name')->where('id', 'in', $classify_id)->select();
                $classify_name_str = '';
                if ($classify_name) {
                    foreach ($classify_name as $k => $v) {
                        $classify_name_str .= $v['name'] . ',';
                    }
                }
                $data['classify_name'] = trim($classify_name_str, ',');
                $classify_id = implode(',', $classify_id);
            }
            $data['title'] = $title;
            $data['desc'] = $desc;
            $data['content'] = $content;
            $data['image'] = $image;
            $data['classify_id'] = ',' . $classify_id . ',';
            $data['tags_id'] = ',' . $tags_id . ',';
            $data['status'] = $status;
            $data['sort'] = $sort;

            if (!$id) {
                $has = Db::name('articles_articles')->where('title', $title)->find();
                if ($has) {
                    $cmd = Alert::make()->msg('标题已存在')->icon('5')->onOk(null);
                    return JsCmd::make()->addCmd($cmd)->run();
                }
                $data['addtime'] = time();
                $res = Db::name('articles_articles')
                    ->insertGetId($data);
            } else {
                $res = Db::name('articles_articles')->where("id", $id)->update($data);
            }

            if ($res) {
                Settings::_saveCache();
                $alert = Alert::make()->msg("操作成功")->icon('6')->onOk(CloseAndRefresh::make()->layerNum(0)->closeNum(0))->title("重要提示")->btn("好的");
            } else {
                $alert = Alert::make()->msg("操作失败，请重试")->icon('5')->title("重要提示")->btn("好的");
            }
            return JsCmd::make()->addCmd($alert)->run();//->url('/?app=articles@index')
        } else {
            $tagslists = Db::name('articles_tags')->where('status', 1)->select();
            $articles_tags = array();
            if ($id = Args::params("id/d")) {
                $articles_info = Db::name('articles_articles')->where('id', $id)->find();
                $articles_info['tags_id'] = trim($articles_info['tags_id'], ',');
                $this->_as_articles = $articles_info;
                $articles_tags = explode(',', $articles_info['tags_id']);
            } else {
                $this->_as_articles = array('status' => 0);
            }
            foreach ($tagslists as $k => $v) {
                if (in_array($v['id'], $articles_tags)) {
                    $tagslists[$k]['issel'] = 1;
                } else {
                    $tagslists[$k]['issel'] = 0;
                }
            }


            $articles_classify = array();
            if ($id) {
                $articles_info = Db::name('articles_articles')->where('id', $id)->find();
                $articles_classify = explode(',', trim($articles_info['classify_id'], ','));
            }
            $classifys = Db::name('articles_classify')->where('status', 1)->order('pid asc')->select();
            foreach ($classifys as $k => $v) {
                $level_sel = Db::name('articles_classify')->where('pid', $v['id'])->find();
                if ($level_sel) {
                    $classifys[$k]['nosel'] = 1;
                } else {
                    $classifys[$k]['nosel'] = 0;
                }
                if (in_array($v['id'], $articles_classify)) {
                    $classifys[$k]['issel'] = 1;
                } else {
                    $classifys[$k]['issel'] = 0;
                }
            }
            $classifys = $this->tree($classifys);
            $this->_as_classifys = $classifys;


            $this->_as_tagslists = $tagslists;
            $list = Db::name("articles_articles")->select();
            $this->assign("list", $list);
            $this->assign("status_url", $this->static_url_pre);
            $this->adminUiDisplay();
        }
    }

    public function tags()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//            $tags_id= Args::params("tags_id/d");
//            if($tags_id){
//                $tags_name = Db::name('articles_tags')->field('name')->where('id','in',$tags_id)->select();
//                $tags_name_str = '';
//                if($tags_name){
//                    foreach ($tags_name as $k => $v){
//                        $tags_name_str  .= $v['name'].',';
//                    }
//                }
//                $tags_name = trim($tags_name_str,',');
//                $tags_id = implode(',',$tags_id);
//
//            }
//
//            var_dump($tags_id);
//            var_dump($tags_name);exit;
        } else {
            $id = Args::params("id/d");
            $articles_tags = array();
            if ($id) {
                $articles_info = Db::name('articles_articles')->where('id', $id)->find();
                $articles_tags = explode(',', trim($articles_info['tags_id'], ','));
            }
            $tagslists = Db::name('articles_tags')->where('status', 1)->select();
            foreach ($tagslists as $k => $v) {
                if (in_array($v['id'], $articles_tags)) {
                    $tagslists[$k]['issel'] = 1;
                } else {
                    $tagslists[$k]['issel'] = 0;
                }
            }
            $this->_as_tagslists = $tagslists;
            $this->adminUiDisplay();
        }
    }


    public function del()
    {

        $id = Args::params('id');
        $res = Db::name('articles_articles')->delete($id);
        if ($res) {
//            Settings::_saveCache();
            $cmd = Alert::make()->msg('删除成功')->icon('6')->onOk(Refresh::make()->type("table"));
        } else {
            $cmd = Alert::make()->msg('删除失败')->icon('5')->onOk(null);
        }
        echo JsCmd::make()->addCmd($cmd)->run();
    }

    public function classify()
    {

        $id = Args::params("id/d");
        $articles_classify = array();
        if ($id) {
            $articles_info = Db::name('articles_articles')->where('id', $id)->find();
            $articles_classify = explode(',', trim($articles_info['classify_id'], ','));
        }
        $classifys = Db::name('articles_classify')->where('status', 1)->order('pid asc')->select();
        foreach ($classifys as $k => $v) {
            $level_sel = Db::name('articles_classify')->where('pid', $v['id'])->find();
            if ($level_sel) {
                $classifys[$k]['nosel'] = 1;
            } else {
                $classifys[$k]['nosel'] = 0;
            }
            if (in_array($v['id'], $articles_classify)) {
                $classifys[$k]['issel'] = 1;
            } else {
                $classifys[$k]['issel'] = 0;
            }
        }
        $this->_as_classifys = $classifys;
        $this->adminUiDisplay();

    }

    public function is_publish()
    {
        $id = Args::params('id');
        $status = Args::params('status/d');
        $res = Db::name('articles_articles')->where('id', $id)->update(['status' => $status]);
        $font_data = '';
        if ($status == 1) {
            $font_data = '发布';
        } else {
            $font_data = '己修改待发布';
        }
        if ($res) {
//            Settings::_saveCache();
            $cmd = Alert::make()->msg($font_data . '成功')->icon('6')->onOk(Refresh::make()->type("table"));
        } else {
            $cmd = Alert::make()->msg($font_data . '失败')->icon('5')->onOk(null);
        }
        echo JsCmd::make()->addCmd($cmd)->run();
    }

//    public function set_tags()
//    {
//        $tags_id=Args::params('tags_id');
//        $this->adminUijsArgs('tags_id',$tags_id);
//        echo json_encode(['tags_id'=>$tags_id]);
//    }


    public function upload()
    {
        $res = AdminUiUpload::doUpload(["mp4", "gif", "jpeg", "jpg", "png"], 20480000);

        if ($res && ($arr = json_decode($res, true)) && $arr['code'] == 1) {
            $return = NewManger::__getUploadHandier()->onFile($arr);
            exit($return);
        } else {
            exit(json_encode(["uploaded" => false, "url" => ""]));
        }
    }

    public function show()
    {
        $id = intval(Args::params("id/1"));

        $new_info = Db::name("articles_articles")->where("id", $id)->find();
        $new_info['addtime'] = date("Y年n月d日", $new_info['addtime']);

        $this->assign("info", $new_info);
        $this->display("articles/info");
    }


}