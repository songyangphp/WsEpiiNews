
<form role="form" class="epii" method="post" data-form="1">

<div class="card-body">
    <div class="row">
        {loop $tagslists $key=>$value}
        <div class="form-check col-sm-6 col-md-3">
            {if  $value['issel']==1 }
            <input class="form-check-input tags_box" type="checkbox" value="{$value.id}" name="tags_id[]" checked data-name="{$value.name}" id="check{$value.id}">
            {else}
            <input class="form-check-input tags_box" type="checkbox" value="{$value.id}" name="tags_id[]" data-name="{$value.name}" id="check{$value.id}">
            {/if}
            <label class="form-check-label" for="check{$value.id}">{$value.name}</label>
        </div>
        {/loop}
    </div>
</div>
    <div class="form-footer">
      <a class="btn btn-primary" href="javascript:;" onclick="exit()">确定<a>

    </div>
</form>

<script>
    function  exit(){
        window.open_layer.close(window.open_layer_index);
    }
    window.onEpiiInit(function() {
        var tags_check = parent.set_tags2();
        console.log(tags_check);
        if(tags_check){
            var check_arr = tags_check.split(',');
            var test_check = 0;
            var len = check_arr.length;
            for(j = 0,len; j < len; j++) {
                $("#check"+check_arr[j]).prop('checked', true);
            }
        }
        $(".tags_box").click(function (){
            if($(this).is(':checked')){
                console.log($(this).val()+$(this).data('name'));
                parent.set_tags($(this).val(),$(this).data('name'),1);
            }else{
                console.log($(this).val()+$(this).data('name'));
                parent.set_tags($(this).val(),$(this).data('name'),2);
            }

        })

        $(".okbtn").click(function (){
            alert();
        })

    })
</script>
