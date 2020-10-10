
<section class="content" style="padding: 10px">
    <div class="row">
        <div class="col-12">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">搜索</h3>
                </div>
                <div class="card-body">
                    <form role="form" data-form="1" data-search-table-id="1" data-title="自定义标题" >
                        <div class="form-inline">
                            <div class="form-group">
                                <label>标题：</label>
                                <input type="text" class="form-control" name="title" placeholder="支持模糊搜索">
                            </div>
                            <div class="form-group">
                                <label for="class">分类：</label>
                                <select class="selectpicker" id="class" name="pid">
                                    <option value="">请选择</option>
                                    {:options,$list_classify}
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="class">标签：</label>
                                <select class="selectpicker" id="class" name="tagid">
                                    <option value="">请选择</option>
                                    {:options,$list}
                                </select>
                            </div>

                            <div class="form-group" style="margin-left: 10px">
                                <button type="submit" class="btn btn-primary">提交</button>
                                <button type="reset" class="btn btn-default">重置</button>
                            </div>
                        </div>
                    </form>
                </div>



            </div>
        </div>
    </div>
</section>

<div class="content">


    <div class="card-body table-responsive" style="padding-top: 0px">

        <a class="btn btn-dialog"
           data-area='80%,80%'
           data-title='编辑'
           title="新增文章"
           href="{url articles add}&__addons=wslibs/news">新增</a>
    </div>
    <div class="card-body table-responsive" style="padding-top: 0px">
        <table data-table="1" data-url="{url articles ajaxdata}&tagid={? $tags_id}&pid={? $pid}&__addons=wslibs/news" id="table1" class="table table-hover" data-page-size="50">
            <thead>
            <tr>
                <th data-field="id" data-formatter="epiiFormatter">ID</th>
                <th data-field="title" data-formatter="epiiFormatter">标题</th>
                <th data-field="classify_name" data-formatter="epiiFormatter">分类</th>
                <th data-field="tags_name" data-formatter="epiiFormatter" data-align="center">标签</th>
<!--                <th data-field="image" data-formatter="epiiFormatter">图片</th>-->
                <th data-field="status" data-formatter="epiiFormatter.switch" data-align="center">状态</th>
                <!--                <th data-field="remark" data-formatter="epiiFormatter">备注</th>-->
                <th data-field="sort" data-formatter="epiiFormatter">排序</th>
<!--                <th data-formatter="epiiFormatter.btns"-->
<!--                    data-btns="edit,del"-->
<!--                    data-edit-url="{url articles add}&id={id}"-->
<!--                    data-edit-title="编辑：{name}"-->
<!--                    data-del-url="{url articles del}&id={id}"-->
<!--                    data-del-title="删除：{name}"-->
<!--                    data-area="70%,60%"-->
<!--                >操作-->
<!--                </th>-->
                <th data-formatter="epiiFormatter.btns"
                    data-btns="edit1,del1,publish1,show"
                >操作
                </th>
            </tr>
            </thead>
        </table>
    </div>
</div>

<script>
    function edit1(field_value, row, index,field_name) {
        return "<a class='btn btn-outline-info btn-sm btn-dialog' data-area='80%,80%' data-title='编辑'   href='?app=articles@add&id=" + row.id + "&__addons=wslibs/news'><i class='fa fa-pencil-square-o' ></i>编辑</a>";
    }
    function del1(field_value, row, index,field_name) {
        return "<a class='btn btn-outline-danger btn-sm btn-confirm' data-msg=\"确定删除吗?\"  data-ajax='1'  href='?app=articles@del&id=" + row.id + "&__addons=wslibs/news'><i class='fa fa-trash' ></i>删除</a>";
    }
    function publish1(field_value, row, index,field_name) {
        if(row.status == 1){
            return "<a class='btn btn-outline-info btn-sm btn-confirm' data-msg=\"确定要修改成待发布吗?\"  data-ajax='1'  href='?app=articles@is_publish&status=0&id=" + row.id + "&__addons=wslibs/news'><i class='fa fa-paper-plane' ></i>己发布</a>";
        }else{
            return "<a class='btn btn-outline-danger btn-sm btn-confirm' data-msg=\"确定发布吗?\"  data-ajax='1'  href='?app=articles@is_publish&status=1&id=" + row.id + "&__addons=wslibs/news'><i class='fa fa-paper-plane' ></i>待发布</a>";
        }
    }
    function show(field_value, row, index,field_name) {
        return "<a class='btn btn-sm btn-outline-success btn-dialog' title='查看文章' data-area='80%,80%' href='?app=articles@show&id=" + row.id + "&__addons=wslibs/news'>预览文章</a>";
    }
</script>