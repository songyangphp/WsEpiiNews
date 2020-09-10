<!--<script type="text/javascript" charset="utf-8" src="/ueditor-dev-1.5.0/ueditor.config.js"></script>-->
<!--<script type="text/javascript" charset="utf-8" src="/ueditor-dev-1.5.0/_examples/editor_api.js"> </script>-->
<!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
<!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
<!--<script type="text/javascript" charset="utf-8" src="/ueditor-dev-1.5.0/lang/zh-cn/zh-cn.js"></script>-->
<script type="text/javascript" charset="utf-8" src="/ckeditor5/ckeditor.js"></script>
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
        <label>图片：</label>
        <input type="text" class="form-control" name="image" value="{? $articles.image}"
               placeholder="请输入文章图片">
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
    <div class="form-group">
        <label>标签：</label>
        <input type="hidden" class="form-control" name="tags_id"  id="tags_id" value="{? $articles.tags_id}">
        <input type="text" class="form-control" name="tags_id_name"  id="tags_id_name"  style="display: block;width: 58%;height: 38px" value="{? $articles.tags_name }" disabled placeholder="请选择标签">
        <a class="btn btn-default btn-dialog"   data-area="50%,50%" title="标签选择" href="?app=articles@tags&id={? $articles.id}"  style="width: 84px">选择</a>

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
        <textarea class="form-control" rows="5" name="content" id="editor" placeholder="请输入文章内容">{? $articles.content}</textarea>
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
        <a href="{url articles index}"><button type="reset" class="btn btn-default">返回列表</button></a>
<!--        <button type="reset" class="btn btn-default">重置</button>-->
        <button type="submit" class="btn btn-primary">提交</button>
    </div>
</form>

<script>
    function set_tags(tags_id,tags_name,flag) {
        console.log('flag'+flag)
        var tags_val = document.getElementById('tags_id').value;
        var tags_nameval = document.getElementById('tags_id_name').value;
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


    //实例化编辑器
    //建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例
    // var ue = UE.getEditor('editor');
    //
    // function isFocus(e){
    //     alert(UE.getEditor('editor').isFocus());
    //     UE.dom.domUtils.preventDefault(e)
    // }
    // function setblur(e){
    //     UE.getEditor('editor').blur();
    //     UE.dom.domUtils.preventDefault(e)
    // }
    // function insertHtml() {
    //     var value = prompt('插入html代码', '');
    //     UE.getEditor('editor').execCommand('insertHtml', value)
    // }
    // function createEditor() {
    //     enableBtn();
    //     UE.getEditor('editor');
    // }
    // function getAllHtml() {
    //     alert(UE.getEditor('editor').getAllHtml())
    // }
    // function getContent() {
    //     var arr = [];
    //     arr.push("使用editor.getContent()方法可以获得编辑器的内容");
    //     arr.push("内容为：");
    //     arr.push(UE.getEditor('editor').getContent());
    //     alert(arr.join("\n"));
    // }
    // function getPlainTxt() {
    //     var arr = [];
    //     arr.push("使用editor.getPlainTxt()方法可以获得编辑器的带格式的纯文本内容");
    //     arr.push("内容为：");
    //     arr.push(UE.getEditor('editor').getPlainTxt());
    //     alert(arr.join('\n'))
    // }
    // function setContent(isAppendTo) {
    //     var arr = [];
    //     arr.push("使用editor.setContent('欢迎使用ueditor')方法可以设置编辑器的内容");
    //     UE.getEditor('editor').setContent('欢迎使用ueditor', isAppendTo);
    //     alert(arr.join("\n"));
    // }
    // function setDisabled() {
    //     UE.getEditor('editor').setDisabled('fullscreen');
    //     disableBtn("enable");
    // }
    //
    // function setEnabled() {
    //     UE.getEditor('editor').setEnabled();
    //     enableBtn();
    // }
    //
    // function getText() {
    //     //当你点击按钮时编辑区域已经失去了焦点，如果直接用getText将不会得到内容，所以要在选回来，然后取得内容
    //     var range = UE.getEditor('editor').selection.getRange();
    //     range.select();
    //     var txt = UE.getEditor('editor').selection.getText();
    //     alert(txt)
    // }
    //
    // function getContentTxt() {
    //     var arr = [];
    //     arr.push("使用editor.getContentTxt()方法可以获得编辑器的纯文本内容");
    //     arr.push("编辑器的纯文本内容为：");
    //     arr.push(UE.getEditor('editor').getContentTxt());
    //     alert(arr.join("\n"));
    // }
    // function hasContent() {
    //     var arr = [];
    //     arr.push("使用editor.hasContents()方法判断编辑器里是否有内容");
    //     arr.push("判断结果为：");
    //     arr.push(UE.getEditor('editor').hasContents());
    //     alert(arr.join("\n"));
    // }
    // function setFocus() {
    //     UE.getEditor('editor').focus();
    // }
    // function deleteEditor() {
    //     disableBtn();
    //     UE.getEditor('editor').destroy();
    // }
    // function disableBtn(str) {
    //     var div = document.getElementById('btns');
    //     var btns = UE.dom.domUtils.getElementsByTagName(div, "button");
    //     for (var i = 0, btn; btn = btns[i++];) {
    //         if (btn.id == str) {
    //             UE.dom.domUtils.removeAttributes(btn, ["disabled"]);
    //         } else {
    //             btn.setAttribute("disabled", "true");
    //         }
    //     }
    // }
    // function enableBtn() {
    //     var div = document.getElementById('btns');
    //     var btns = UE.dom.domUtils.getElementsByTagName(div, "button");
    //     for (var i = 0, btn; btn = btns[i++];) {
    //         UE.dom.domUtils.removeAttributes(btn, ["disabled"]);
    //     }
    // }
    //
    // function getLocalData () {
    //     alert(UE.getEditor('editor').execCommand( "getlocaldata" ));
    // }
    //
    // function clearLocalData () {
    //     UE.getEditor('editor').execCommand( "clearlocaldata" );
    //     alert("已清空草稿箱")
    // }

    var myEditor = null;
    window.onload = function(){
        ClassicEditor
            .create(document.querySelector("#editor"))
            .then(editor => {
                myEditor = editor;
            })
            .catch(error => {
                console.error(error);
            });
    }
</script>
