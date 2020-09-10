<form role="form" class="epii" method="post" data-form="1" action="{url tags add}&__addons=wslibs/news">
    <div class="form-group">
        <label>标签名称：</label>
        <input type="text" class="form-control" name="name" value="{? $tags.name}" required
               placeholder="请输入标签名称">
    </div>
    <div class="form-group">
        <label>状态：</label>
        <select class="selectpicker"  name="status" required>
            <option value="0" <?php if ($tags['status'] == 0){ ?>selected="selected" <?php } ?>>未启用</option>
            <option value="1" <?php if ($tags['status'] == 1){ ?>selected="selected"<?php } ?>>启用</option>
        </select>
    </div>
    <div class="form-group">
        <label>排序：</label>
        <input type="number" class="form-control" name="sort" placeholder="释: 数字越大越靠后" value="{? $tags.sort}"">
    </div>
    <div class="form-group">
        <input type="hidden" name="id" value="{$tags.id?0}">
    </div>
    <div class="form-footer">
        <button type="reset" class="btn btn-default">重置</button>
        <button type="submit" class="btn btn-primary">提交</button>
    </div>
</form>