<form role="form" class="epii" method="post" data-form="1" action="{url classify add}&__addons=wslibs/news">

        <div class="form-group">
            <label>分类名称：</label>
            <input type="text" class="form-control" name="name" required placeholder="请输入父分类名称">
        </div>
        <div class="form-group">
        <label for="class">父分类：</label>
        <select class="form-control-" id="class" name="pid">
            <option value="0">无</option>
            <?php foreach($list as $k=>$v){?>
            <option value="{$v['id']}">{$v['name']}</option>
            <?php }?>
        </select>
        </div>
        <div class="form-group">
            <label>备注：</label>
            <input type="text" class="form-control" name="remark" placeholder="请输入备注信息">
        </div>
        <div class="form-group">
            <label>状态：</label>
            <select class="selectpicker"  name="status" required>
                <option value="0">未启用</option>
                <option value="1">启用</option>
            </select>
        </div>
        <div class="form-group">
            <label>排序：</label>
            <input type="number" class="form-control" name="sort" placeholder="释: 数字越大越靠后">
        </div>
        <div class="form-footer">
            <button type="reset" class="btn btn-default">重置</button>
            <button type="submit" class="btn btn-primary">提交</button>
        </div>
    </form>


<script>
   function set_icon(icon) {
       document.getElementById('icon').value=icon;
   }
</script>