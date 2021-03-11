<form role="form" class="epii" method="post" data-form="1" action="{url classify edit}&__addons=wslibs/news">
    <input type="hidden" value="{$id}" name="id">
    <?php if (!isset($_GET["inhome"])) $_GET["inhome"] = 0; ?>
    <input type="hidden" value="{$_GET.inhome}" name="inhome">
    <div class="form-group">
        <label>节点名称：</label>
        <input type="text" class="form-control" name="name" value="{$classifyinfo.name}" placeholder="请输入父节点名称" required>
    </div>
    <div class="form-group">
        <label for="class">父节点：</label>
        <select class="form-control" id="class" name="pid">
            <option value="0" <?php if ($classifyinfo['pid'] == 0){ ?>selected="selected"<?php } ?>>无</option>
            {:options,$list,$classifyinfo['pid']}

        </select>
    </div>

    <div class="form-group">
        <label>备注：</label>
        <input type="text" class="form-control" name="remark" value="{$classifyinfo.remark}" placeholder="请输入备注信息">
    </div>
    <div class="form-group">

        <label>状态：</label>
        <select class="selectpicker" name="status" required>
            <option value="0" <?php if ($classifyinfo['status'] == 0){ ?>selected="selected" <?php } ?>>未启用</option>
            <option value="1" <?php if ($classifyinfo['status'] == 1){ ?>selected="selected"<?php } ?>>启用</option>
        </select>
    </div>
    <div class="form-group">
        <label>排序：</label>
        <input type="number" class="form-control" name="sort" value="{$classifyinfo.sort}" placeholder="释: 数字越大越靠后">
    </div>

    <div class="form-footer">
        <button type="submit" class="btn btn-primary">提交</button>
    </div>
</form>
<script>
    function set_icon(icon) {
        document.getElementById('icon').value = icon;
    }
</script>