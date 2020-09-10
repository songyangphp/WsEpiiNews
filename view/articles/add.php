<style>
    .ck-editor__editable {
        min-height: 300px; !important;
        min-width: 100% !important;
    }

    .ck-editor{
        min-width: 300px;
    }
</style>

<script type="text/javascript" charset="utf-8" src="{$status_url}/ckeditor.js"></script>
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
        <label>新闻头图片：</label>
        <div class="form-group col-sm-6">
            <img id="show1" src="" alt="" >
            <button class="btn btn-default"
                    data-upload="1"
                    id="btn1"
                    data-input-id="id1"
                    data-img-id="show1"
                    data-img-style="width:200px;height:200px">选择</button>
        </div>
        <input type="hidden" name="path[]" value="" id="id1">
<!--        <div class="input-group">-->
<!--            <div class="custom-file">-->
<!--                <input type="file" class="custom-file-input" id="exampleInputFile">-->
<!--                <label class="custom-file-label" for="exampleInputFile">Choose file</label>-->
<!--            </div>-->
<!--            <div class="input-group-append">-->
<!--                <span class="input-group-text" id="">Upload</span>-->
<!--            </div>-->
<!--        </div>-->
    </div>
    <div class="form-group">
        <label>分类：</label>
        <select multiple="" class="form-control" name="classify_id[]">
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
    <div class="form-group">
        <label>内容：</label>
<!--        <script id="editor" type="text/plain" style="width:1024px;height:500px;" name="content" data-html-content="{? $articles.content}">-->
<!--            {? $articles.content}-->
<!--        </script>-->
        <textarea class="form-control" name="content" style="width: 100%;" id="editor" placeholder="请输入文章内容">{? $articles.content}</textarea>
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

<script>
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
        ClassicEditor
            .create( document.querySelector( '#editor' ), {
                ckfinder: {
                    uploadUrl: '?app=articles@upload&__addons=wslibs/news'
                }
            } )
            .then( editor => {
                window.editor = editor;
            } )
            .catch( err => {
                console.error( err.stack );
            } );
    }
</script>