
<form role="form" class="epii" method="post" data-form="1">

    <div class="form-group">
        <label>分类树：</label>

        <select multiple="" class="form-control" height>
            {loop $classifys $key=>$value}
                {if  $value['issel']==1 }
                <option value="{$value.id}"> {if $value['level'] == 2}---{/if}{if $value['level'] == 3}------{/if}{$value.name}</option>
                {else}
                <option value="{$value.id}" {if $value['nosel'] ==1}disabled{/if}>{if $value['level'] == 2}---{/if}{if $value['level'] == 3}------{/if}{$value.name}</option>
                {/if}
            {/loop}
        </select>


<!--        {loop $classifys $key=>$value}-->
<!--        <div class="form-check">-->
<!--            {if  $value['issel']==1 }-->
<!--            <input class="form-check-input tags_box" type="checkbox" value="{$value.id}" name="tags_id[]" checked data-name="{$value.name}">-->
<!--            {else}-->
<!--            <input class="form-check-input tags_box" type="checkbox" value="{$value.id}" name="tags_id[]" data-name="{$value.name}">-->
<!--            {/if}-->
<!--            <label class="form-check-label">{$value.name}</label>-->
<!--        </div>-->
<!--        {/loop}-->
    </div>
<!--    <div class="form-footer">-->
<!--        <a class="layui-layer-ico layui-layer-close layui-layer-close1" href="javascript:;">确定<a>-->
<!--<button class="btn btn-primary okbtn ">确定</button>-->
<!--    </div>-->
</form>

<script>
    window.onEpiiInit(function() {
        console.log(123);
        $(".tags_box").click(function (){
            if($(this).is(':checked')){
                console.log($(this).val()+$(this).data('name'));
                parent.set_tags($(this).val(),$(this).data('name'),1);
            }else{
                console.log($(this).val()+$(this).data('name'));
                parent.set_tags($(this).val(),$(this).data('name'),2);
            }

        })

        // $(".okbtn").click(function (){
        //     var index=parent.layer.getFrameIndex(window.name);
        //     parent.layer.close(index);
        // }

        // var i = document.getElementsByTagName('i');
        //
        // for (var j = 0; j < i.length; j++) {
        //     i[j].onmouseover = function () {
        //         this.style.cursor = 'pointer';
        //
        //     }
        //     i[j].onclick = function () {
        //         parent.set_icon(this.className);
        //         var index=parent.layer.getFrameIndex(window.name);
        //         parent.layer.close(index);
        //     }
        // }
    })
</script>
