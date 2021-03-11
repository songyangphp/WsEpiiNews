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


class classify extends base
{

    /**
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * 菜单
     */
    public function index()
    {
        $list = Db::name("articles_classify")->where("pid =0")->select();
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
        $sql = "SELECT *,if(pid=0,id,pid) as pidd from ".Db::getConfig("prefix")."articles_classify order by pidd asc,pid asc,sort desc limit " . $offset . ',' . $limit;
        if (!empty($name)) {
            $sql = "SELECT *,if(pid=0,id,pid) as pidd from ".Db::getConfig("prefix")."articles_classify where name like '%" . $name . "%' order by pidd asc,pid asc,sort desc limit " . $offset . ',' . $limit;

        }
        if (!empty($pid)) {
            $sql = "SELECT *,if(pid=0,id,pid) as pidd from ".Db::getConfig("prefix")."articles_classify where  pid=" . $pid . " order by pidd asc,pid asc,sort desc limit " . $offset . ',' . $limit;

        }
        if (!empty($name) && !empty($pid)) {
            $sql = "SELECT *,if(pid=0,id,pid) as pidd from ".Db::getConfig("prefix")."articles_classify where name like '%" . $name . "%' and pid=" . $pid . "order by pidd asc,pid asc,sort desc limit " . $offset . ',' . $limit;
        }

        $data = Db::query($sql);

        foreach ($data as $k => $v) {
            if ($data[$k]['pid'] != 0) {
                if($v['level'] == 3){
                    $data[$k]['name'] = '|------------' . $v['name'];
                }else{
                    $data[$k]['name'] = '|------' . $v['name'];
                }
            }
            $data[$k]['icon'] = '<i class="' . $v['icon'] . '"></i>';
                $arr_child = $this->findchild($v['id']);
                $count_c = 0;
                foreach ($arr_child as $kk => $vv){
                    $count = Db::name('articles_articles')->whereLike('classify_id','%,'.$vv.',%')->count('id');
                    $count_c = $count_c+$count;
                }
            $data[$k]['articles_count'] = $count_c;
        }
        if(empty($name)){
            $data_list = $this->tree($data);
        }else{
            $data_list = $data;
        }
        $total = Db::name('articles_classify')->count('id');
        echo json_encode(['rows' => $data_list, 'total' => $total]);

    }

    public function findchild($id){
        $list = Db::name('articles_classify')->whereLike('trees','%,'.$id.',%')->select();
        $arr = array();
        foreach ($list as $k => $v){
            $arr[] = $v['id'];
        }
        return $arr;
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
            $name = trim(Args::params("name"));
            $pid = trim(Args::params("pid"));
            $icon = trim(Args::params("icon"));
            $url = trim(Args::params("url"));
            $remark = trim(Args::params("remark"));
            $status = trim(Args::params("status")) ?: 0;
            $sort = trim(Args::params("sort"));

            if (Db::name('articles_classify')->where("name = '$name'")->find()) {
                $alert = Alert::make()->msg($name . "此分类已存在")->icon('5')->title("重要提示")->btn("好的");
                return JsCmd::make()->addCmd($alert)->run();
            }

            $data['name'] = $name;
            $data['pid'] = $pid;
            $data['remark'] = $remark;
            $data['status'] = $status;
            $data['sort'] = $sort;
            $data['icon'] = $icon;
            $data['url'] =  $url;
            $data['open_type'] = (int) Args::params("open_type");

            if($pid == 0){
                $data['level'] =1;
            }else{
                $data['trees'] = ','.$pid.',';
                $data['level'] =2;
            }
            $re = Db::name('articles_classify')
                ->insertGetId($data);
            if ($re) {
                if($pid == 0){
                    $data_update['trees'] = ','.$re.',';
                    $data_update['level'] =1;
                }else{
                    $c_info = Db::name('articles_classify')->where('id',$pid)->find();
                    if($c_info['pid'] != 0){
                        if($c_info['trees']){
                            $data_update['trees'] = $c_info['trees'].$re.',';
                        }else{
                            $data_update['trees'] = $c_info['trees'].','.$re.',';
                        }
                        $data_update['level'] =$c_info['level']+1;
                    }else{
                        $data_update['trees'] = ','.$pid.','.$re.',';
                        $data_update['level'] =2;
                    }

                }
                Db::name('articles_classify')->where('id',$re)->update($data_update);
                Settings::_saveCache();
                $alert = Alert::make()->msg("操作成功")->icon('6')->onOk(CloseAndRefresh::make()->layerNum(0)->closeNum(0))->title("重要提示")->btn("好的");
            } else {
                $alert = Alert::make()->msg("操作失败，请重试")->icon('5')->title("重要提示")->btn("好的");
            }

            return JsCmd::make()->addCmd($alert)->run();

        } else {
            $list = Db::name("articles_classify")->where('status', 1)->where('level','<=',2)->select();
            foreach ($list as $k => $v){
                if ($list[$k]['pid'] != 0) {
                    $list[$k]['name'] = '------' . $v['name'];
                }
                $list[$k]['icon'] = '<i class="' . $v['icon'] . '"></i>';
            }

            $list = $this->tree($list);

            $this->assign("list", $list);
            $this->adminUiDisplay();
        }
    }

    /**
     * @return array|false|string
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\db\exception\PDOException
     * 编辑页面+编辑
     */

    public function edit()
    {
        $id = Args::params("id");
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (!$id) {
                return JsCmd::make()->addCmd(Alert::make()->msg("缺少参数")->title("重要提示")->btn("好的"))->run();
            }

            $name = trim(Args::params("name"));
            $pid = trim(Args::params("pid"));
            $icon = trim(Args::params("icon"));
            $url = trim(Args::params("url"));
            $remark = trim(Args::params("remark"));
            $status = trim(Args::params("status")) ?: 0;
            $sort = trim(Args::params("sort"));

            $data['name'] = $name;
            $data['pid'] = $pid;
            $data['remark'] = $remark;
            $data['status'] = $status;
            $data['sort'] = $sort;
            $data['icon'] = $icon;
            $data['url'] = $url;
            $data['badge_class'] = Args::params("badge_class","");
            $data['open_type'] = (int) Args::params("open_type");
            if($pid == 0){
                $data['trees'] = ','.$id.',';
                $data['level'] =1;
            }else{
                $c_info = Db::name('articles_classify')->where('id',$pid)->find();
                if($c_info['pid'] != 0){
                    if($c_info['trees']){
                        $data['trees'] = $c_info['trees'].$id.',';
                    }else{
                        $data['trees'] = $c_info['trees'].','.$id.',';
                    }
                    $data['level'] =$c_info['level']+1;
                }else{
                    $data['trees'] = ','.$pid.','.$id.',';
                    $data['level'] =2;
                }
            }
            $re = Db::name("articles_classify")
                ->where("id = '$id'")
                ->update($data);


            if ($re) {
                Settings::_saveCache();
                if (Args::postVal("inhome")) {
                    $alert = Alert::make()->msg("操作成功")->icon('6')->onOk(JsEval::make()->add_string("top.window.location.reload();"))->title("重要提示")->btn("好的");
                } else {
                    $alert = Alert::make()->msg("操作成功")->icon('6')->onOk(CloseAndRefresh::make()->layerNum(0)->closeNum(0))->title("重要提示")->btn("好的");
                }


            } else {
                $alert = Alert::make()->msg("失败或未修改，请重试")->icon('5')->title("重要提示")->btn("好的");
            }
            return JsCmd::make()->addCmd($alert)->run();

        } else {
//            $list = Db::name("classify")->where('pid', 0)->select();
            $list = Db::name("articles_classify")->where('status', 1)->where('level','<=',2)->select();
            foreach ($list as $k => $v){
                if ($list[$k]['pid'] != 0) {
                    $list[$k]['name'] = '------' . $v['name'];
                }
                $list[$k]['icon'] = '<i class="' . $v['icon'] . '"></i>';
            }
            $this->assign("list", $list);
            $this->assign("id", $id);
            $classifyinfo = Db::name("articles_classify")->where("id", $id)->find();
            $this->assign('classifyinfo', $classifyinfo);
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
        $son_list = Db::name('articles_classify')->where('pid',$id)->find();
        if($son_list){
            $cmd = Alert::make()->msg('此分类有子类无法删除')->icon('5')->onOk(null);
            echo JsCmd::make()->addCmd($cmd)->run();
            exit;
        }
        $articles_count = Db::name('articles_articles')->whereLike('classify_id','%,'.$id.',%')->count();
        if($articles_count >0){
            $cmd = Alert::make()->msg('此分类下有文章无法删除')->icon('5')->onOk(null);
            echo JsCmd::make()->addCmd($cmd)->run();
            exit;
        }
        $res = Db::name('articles_classify')->delete($id);
        if ($res) {
//            Settings::_saveCache();
            $cmd = Alert::make()->msg('删除成功')->icon('6')->onOk(Refresh::make()->type("table"));
        } else {
            $cmd = Alert::make()->msg('删除失败')->icon('5')->onOk(null);
        }
        echo JsCmd::make()->addCmd($cmd)->run();
    }

}