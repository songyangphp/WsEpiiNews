<?php

namespace wslibs\news\app;

use epii\admin\center\config\Settings;
use epii\server\Args;
use think\Db;
use epii\admin\ui\lib\epiiadmin\jscmd\Alert;
use epii\admin\ui\lib\epiiadmin\jscmd\CloseAndRefresh;
use epii\admin\ui\lib\epiiadmin\jscmd\JsCmd;
use epii\admin\ui\lib\epiiadmin\jscmd\JsEval;
use epii\admin\ui\lib\epiiadmin\jscmd\Refresh;


class tags extends base
{

    /**
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * 菜单
     */
    public function index()
    {
        $list = Db::name("articles_tags")->select();
        $this->assign("list", $list);
        $this->adminUiDisplay();
    }

    /**
     * 表格数据
     */
    public function ajaxdata()
    {

        $name = trim(Args::params("name"));
        $pid = trim(Args::params("pid"));
        $offset = trim(Args::params("offset"));
        $limit = trim(Args::params("limit"));
        $sql = "SELECT * from ".Db::getConfig("prefix")."articles_tags order by id asc,sort desc limit " . $offset . ',' . $limit;
        if (!empty($name)) {
            $sql = "SELECT * from ".Db::getConfig("prefix")."articles_tags where name like '%" . $name . "%' order by id asc,sort desc limit " . $offset . ',' . $limit;

        }
        $data = Db::query($sql);
        foreach ($data as $k => $v){
            $count = Db::name('articles_articles')->whereLike('tags_id','%,'.$v['id'].',%')->count('id');
            $data[$k]['articles_count'] = $count;
        }
        $total = Db::name('articles_tags')->count('id');
        echo json_encode(['rows' => $data, 'total' => $total]);

    }

    /**
     * @return array|false|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * 添加页面+添加
     */
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = Args::params("id/d");
            $name = trim(Args::params("name"));
            $status = trim(Args::params("status")) ?: 0;
            $sort = trim(Args::params("sort"));

            $data['name'] = $name;
            $data['status'] = $status;
            $data['sort'] = $sort;


            if (!$id) {
                $has = Db::name('articles_tags')->where('name', $name)->find();
                if ($has) {
                    $cmd = Alert::make()->msg('名字已存在')->icon('5')->onOk(null);
                    return JsCmd::make()->addCmd($cmd)->run();
                }
                $data['addtime'] = time();
                $res = Db::name('articles_tags')
                    ->insertGetId($data);
            } else {
                $res = Db::name('articles_tags')->where("id", $id)->update($data);
            }


            if ($res) {
                Settings::_saveCache();
                $alert = Alert::make()->msg("操作成功")->icon('6')->onOk(CloseAndRefresh::make()->layerNum(0)->closeNum(0))->title("重要提示")->btn("好的");
            } else {
                $alert = Alert::make()->msg("操作失败，请重试")->icon('5')->title("重要提示")->btn("好的");
            }
            return JsCmd::make()->addCmd($alert)->run();
        } else {
            if ($id = Args::params("id/d")) {
                $this->_as_tags = Db::name('articles_tags')->where('id', $id)->find();
            }else{
                $this->_as_tags = array('status'=>0);
            }
            $list = Db::name("articles_tags")->select();
            $this->assign("list", $list);
            $this->adminUiDisplay();
        }
    }

    /**
     * @return array|false|string
     * @throws \think\Exception
     * @throws \think\db\exception\PDOException
     * 删除方法
     */
    public function del()
    {
        $id = Args::params('id');
        $count = Db::name('articles_articles')->whereLike('tags_id','%,'.$id.',%')->count('id');
        if($count>0){
//            $cmd = Alert::make()->msg('此标签己被使用，确认强制删除吗')->icon('5')->onOk(null);
//            echo JsCmd::make()->addCmd($cmd)->run();
//            exit;
              $articles = Db::name('articles_articles')->whereLike('tags_id','%,'.$id.',%')->select();

              foreach ($articles as $k => $v){
                  $tags_id = trim($v['tags_id'],',');
                  $tags_id_arr = explode(',',$tags_id);
                  $arr = array_diff($tags_id_arr, [$id]);
                  if(count($arr)>0){
                    $diff_arr = implode(',',$arr);
                    $tags_list = Db::name('articles_tags')->field('name')->where('id','in',$arr)->select();
                    $tags_name = '';
                    foreach ($tags_list as $k => $vv){
                        $tags_name .= $vv['name'].',';
                    }
                    if(!empty($tags_name)){
                        $tags_name = trim($tags_name,',');
                    }
                    $data_diff = array('tags_id'=>','.$diff_arr.',');
                    $data_diff['tags_name'] = $tags_name;
                  }else{
                      $data_diff = array('tags_id'=>'');
                      $data_diff['tags_name'] = '';
                  }
                  $res = Db::name('articles_articles')->where('id',$v['id'])->update($data_diff);
              }
        }
        $res = Db::name('articles_tags')->delete($id);
        if ($res) {
//            Settings::_saveCache();
            $cmd = Alert::make()->setTimeout(3000)->msg('删除成功')->icon('6')->onOk(Refresh::make()->type("table"));
        } else {
            $cmd = Alert::make()->msg('删除失败')->icon('5')->onOk(null);
        }
        echo JsCmd::make()->addCmd($cmd)->run();
    }

}