
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
                                <label>标签名称：</label>
                                <input type="text" class="form-control" name="name" placeholder="支持模糊搜索">
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
        <a class="btn    btn-outline-primary btn-table-tool btn-dialog" href="{url tags add}&__addons=wslibs/news" data-area="40%,60%" title="新增节点">新增</a>
    </div>
    <div class="card-body table-responsive" style="padding-top: 0px">
        <table data-table="1" data-url="{url tags ajaxdata}&__addons=wslibs/news" id="table1" class="table table-hover">
            <thead>
            <tr>

                <th data-field="id" data-formatter="epiiFormatter">ID</th>
                <th data-field="name" data-formatter="epiiFormatter">标签名称</th>
                <!--                <th data-field="pid" data-formatter="epiiFormatter">父节点</th>-->
                <!--                <th data-field="icon" data-formatter="epiiFormatter" data-align="center">图标</th>-->
<!--                <th data-field="url" data-formatter="epiiFormatter">链接地址</th>-->
                <th data-field="status" data-formatter="epiiFormatter.switch" data-align="center">状态111</th>
                <th data-field="articles_count" data-btns="search" data-formatter="epiiFormatter.btns" data-search-url="{url articles index}&tagid={id}" data-search-title="查看">文章</th>
                <th data-field="sort" data-formatter="epiiFormatter">排序</th>
                <th data-formatter="epiiFormatter.btns"
                    data-btns="edit,del1"
                    data-edit-url="{url tags add}id={id}&__addons=wslibs/news"
                    data-edit-title="编辑：{name}"
                    data-area="70%,60%"
                >操作
                </th>
            </tr>
            </thead>
        </table>
    </div>
</div>

<script>
    function search(field_value, row, index,field_name) {
        return "<a class=' btn-sm btn-addtab'  data-title='查询'   href='?app=articles@index&tagid=" + row.id + "&__addons=wslibs/news'>"+row.articles_count+"</a>";
    }
    function del1(field_value, row, index,field_name) {
        console.log(row.articles_count);
        if(row.articles_count > 0){
            return "<a class='btn btn-outline-danger btn-sm btn-confirm' data-msg=\"此标签己被使用，确认强制删除吗?\"  data-ajax='1'  href='?app=tags@del&id=" + row.id + "&__addons=wslibs/news'><i class='fa fa-trash' ></i>删除</a>";
        }else{
            return "<a class='btn btn-outline-danger btn-sm btn-confirm' data-msg=\"确定要删除吗?\"  data-ajax='1'  href='?app=tags@del&id=" + row.id + "&__addons=wslibs/news'><i class='fa fa-trash' ></i>删除</a>";
        }
    }
</script>