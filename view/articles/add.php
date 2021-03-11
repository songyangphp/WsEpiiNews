<link rel="stylesheet" href="{$status_url}/select_2.css">
<style>
    .ck-editor__editable {
        min-height: 300px; !important;
        min-width: 100% !important;
    }

    .ck-editor{
        min-width: 300px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #007bff;
        border-color: #006fe6;
        padding: 1px 10px;
        color: #fff;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        margin-right: 5px;
        color: rgba(255, 255, 255, .7);
    }
</style>

<script type="text/javascript" charset="utf-8" src="{$status_url}/wangEditor.min.js"></script>

<form role="form" class="epii" method="post" data-form="1" action="{url articles add}&__addons=wslibs/news">
    <div class="form-group">
        <label>标题：</label>
        <input type="text" class="form-control" name="title" value="{? $articles.title}" required
               placeholder="请输入文章标题">
    </div>
    <div class="form-group">
        <label>简介：</label>
        <textarea class="form-control" rows="3" name="desc" placeholder="请输入文章简介">{? $articles.desc}</textarea>
    </div>
    <div class="form-group">
        <label>上传头图(最多三张)</label>
        <input type="text" class="form-control"
               name="path" id="img2"
               value="{$articles['image']}"
               data-src="{$articles['img_show_url']}"
        >
        <button data-upload=1
                data-multiple=1
                class="btn btn-danger"
                data-input-id="img2"
                data-preview-id="img_show_2"> 上传</button>
    </div>
    <div id="img_show_2">
    </div>
    <div class="form-group">
        <label>分类：</label>
        <select multiple="" class="form-control select2 select2-hidden-accessible" data-placeholder="可多选" name="classify_id[]">
            {loop $classifys $key=>$value}
            {if  $value['issel']==1 }
            <option value="{$value.id}" selected> {if $value['level'] == 2}|---{/if}{if $value['level'] == 3}|------{/if}{$value.name}</option>
            {else}
            <option value="{$value.id}" {if $value['nosel'] ==1}disabled{/if}>{if $value['level'] == 2}|---{/if}{if $value['level'] == 3}|------{/if}{$value.name}</option>
            {/if}
            {/loop}
        </select>

<!--        <input type="hidden" class="form-control" name="classify_id"  id="classify_id" value="{? $articles.classify_id}">-->
<!--        <input type="text" class="form-control" name="classify_id_name"  id="classify_id_name" required style="display: block;width: 58%;height: 38px" value="">-->
<!--        <a class="btn btn-default btn-dialog"   data-area="50%,50%" title="分类选择" href="?app=articles@classify&id={? $articles.id}"  style="width: 84px">选择分类</a>-->

<!--        <input type="text" class="form-control" name="image" value="{? $articles.image}"-->
<!--               placeholder="请输入文章图片">-->
    </div>
    <div class="form-group" style="width: 100%">
        <label>标签：</label>
        <input type="hidden" class="form-control" name="tags_id"  id="tags_id" value="{? $articles.tags_id}">

        <input type="text" class="form-control" name="tags_id_name"  id="tags_id_name"  style="display: block;width: 58%;height: 38px" value="{? $articles.tags_name }" readonly placeholder="请选择标签">
        <a class="btn btn-default btn-dialog"   data-area="50%,50%" title="标签选择" href="?app=articles@tags&id={? $articles.id}&__addons=wslibs/news"  style="width: 84px">选择</a>

<!--        {loop $tagslists $key=>$value}-->
<!--        <div class="form-check">-->
<!--            {if  $value['issel']==1 }-->
<!--            <input class="form-check-input" type="checkbox" value="{$value.id}" name="tags_id[]" checked>-->
<!--            {else}-->
<!--            <input class="form-check-input" type="checkbox" value="{$value.id}" name="tags_id[]" >-->
<!--            {/if}-->
<!--            <label class="form-check-label">{$value.name}</label>-->
<!--        </div>-->
<!--        {/loop}-->
    </div>
<!--    <div class="form-group">-->
<!--        <label>内容：</label>-->
<!--        <script id="editor" type="text/plain" style="width:1024px;height:500px;" name="content" data-html-content="{? $articles.content}">-->
<!--            {? $articles.content}-->
<!--        </script>-->
<!--        <textarea class="form-control" name="content" style="width: 100%;" id="editor" placeholder="请输入文章内容">{? $articles.content}</textarea>-->
<!--    </div>-->
    <div class="form-group">
        <label>内容：</label>
        <div class="form-control" style="width: 70%" id="div1">
            {$articles['content']}
        </div>
        <textarea id="text1" name="content" style="display: none">{$articles['content']}</textarea>
    </div>
    <div class="form-group">
        <label>状态：</label>
        <select class="selectpicker"  name="status" required>
            <option value="0" <?php if ($articles['status'] == 0){ ?>selected="selected" <?php } ?>>待发布</option>
            <option value="1" <?php if ($articles['status'] == 1){ ?>selected="selected"<?php } ?>>己发布</option>
        </select>
    </div>
    <div class="form-group">
        <label>排序：</label>
        <input type="number" class="form-control" name="sort" placeholder="释: 数字越大越靠后" value="{? $articles.sort}"">
    </div>
    <div class="form-group">
        <input type="hidden" name="id" value="{$articles.id?0}">
    </div>
    <div class="form-footer">
        <a href="{url articles index}&__addons=wslibs/news" class="btn btn-default">返回列表</a>
        <button type="submit" class="btn btn-primary">提交</button>
    </div>
</form>
<script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
<!--<script type="text/javascript" src="//unpkg.com/wangeditor/dist/wangEditor.min.js"></script>-->
<script>
    function creat_storage_task() {
        $.ajax({
            url: '?app=uploadApi@upload&_upload_yun=1',
            type: 'post',
            dataType: 'json',
            success: function (res) {
                console.log("看看结果",res);
                document.getElementById("storage_file").click();
                var formData = new FormData();
                //formData.append("token", res.data.token);
                formData.append("task_id", res.data.task_id);
                $("#storage_file").change(function () {
                    var files = $('#storage_file')[0].files;
                    for(i=0;i<files.length;i++){
                        formData.append("file["+i+"]", files[i]);
                    }
                    $.ajax({
                        url: 'http://file.wszx.cc/index.php/storage/index/token/1367bb9afa9aa177033f0ecb5e74debf',
                        //url: 'http://test.storage.com/index.php/storage/index',
                        dataType: 'json',
                        type: 'POST',
                        data: formData,
                        processData: false, // 使数据不做处理
                        contentType: false, // 不要设置Content-Type请求头
                        success: function (data) {
                            console.log(data);
                        },
                        error: function (response) {
                            console.log(response);
                        }
                    });
                })
            }
        })
    }

    window.onEpiiInit(function () {
        require(["{$status_url}/select_2.js"], function () {
            $('.select2').select2()
        });
    });

    const E = window.wangEditor
    const editor = new E('#div1')
    const $text1 = $('#text1')
    editor.config.onchange = function (html) {
        // 第二步，监控变化，同步更新到 textarea
        $text1.val(html)
    }
    editor.config.uploadImgMaxLength = 1
    editor.config.uploadImgServer = '?app=articles@upload&__addons=wslibs/news'
    editor.create()

    // 第一步，初始化 textarea 的值
    $text1.val(editor.txt.html())

    function set_tags2(){
        var tags_val = document.getElementById('tags_id').value;
        return tags_val;
    }
    function set_tags(tags_id,tags_name,flag) {
        console.log('flag'+flag)
        var tags_val = document.getElementById('tags_id').value;
        var tags_nameval = document.getElementById('tags_id_name').value;


       // var aa =  $("#tags_id1").val();
       // alert(aa);
        if(flag == 1){
            if(tags_val){
                document.getElementById('tags_id').value= tags_val +','+tags_id;
            }else{
                document.getElementById('tags_id').value=tags_id;
            }

            if(tags_nameval){
                document.getElementById('tags_id_name').value= tags_nameval +','+tags_name;
            }else{
                document.getElementById('tags_id_name').value=tags_name;
            }
        }else{
            if(tags_val){

                var tags_val_arr = tags_val.split(',');
                console.log(tags_val_arr);
                //var tags_id_diff = tags_val_arr.remove(tags_id);
                if($.inArray(tags_id,tags_val_arr) != -1){
                    var pos_id = $.inArray(tags_id,tags_val_arr);
                    tags_val_arr.splice(pos_id, 1);
                    //delete tags_val_arr[pos_id];
                    console.log(tags_val_arr);
                    console.log(tags_val_arr.toString());
                    document.getElementById('tags_id').value= tags_val_arr.join(',');
                }

            }
            if(tags_nameval){
                var tags_nameval_arr = tags_nameval.split(',');
                console.log(tags_nameval_arr);
                //var tags_id_diff = tags_val_arr.remove(tags_id);
                if($.inArray(tags_name,tags_nameval_arr) != -1){
                    var pos_id = $.inArray(tags_name,tags_nameval_arr);
                    tags_nameval_arr.splice(pos_id, 1);
                    //delete tags_val_arr[pos_id];
                    console.log(tags_nameval_arr);
                    console.log(tags_nameval_arr.toString());
                    document.getElementById('tags_id_name').value= tags_nameval_arr.join(',');
                }


                // var tags_nameval_arr = tags_nameval.split(',');
                //var tags_name_diff = tags_nameval_arr.remove(tags_name);
                //document.getElementById('tags_id_name').value= tags_name_diff.join(',');
            }
        }


    }

    // Array.prototype.indexOf = function(val) {
    //     for (var i = 0; i < this.length; i++) {
    //         if (this[i] == val) return i;
    //     }
    //     return -1;
    // };
    // Array.prototype.remove = function(val) {
    //     var index = this.indexOf(val);
    //     if (index > -1) {
    //         this.splice(index, 1);
    //     }
    // };
    var myEditor = null;
    window.onload = function(){

    }
</script>
