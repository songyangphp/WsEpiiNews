<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=yes">
    <meta name="format-detection" content="telephone=YES">
    <meta name="apple-touch-fullscreen" content="YES" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta name="HandheldFriendly" content="True">
    <style>
        .newstitle{ margin:5% auto 3%; width:93%; color:#033333;text-align:left; font-size:1.4em;}
        .title_date{ margin:0 auto 2%; width:93%; line-height:30px; color:#999999;font-size:1.1em;}
        .title_date span{}
        .content_phone_news{ margin:0 auto; width:93%;}
        .bianji{ margin:3% auto 30px; width:95%;color: #999;font-size: 1.1em; text-align:right; }
    </style>
</head>
<body style="background:#FFF;">
<section class="newstitle">{$info['title']}</section>
<section class="title_date"><span>发布于</span>&nbsp;&nbsp;&nbsp;<span>{$info['addtime']}</span></section>
<!--<section class="bianji">【作者：{$info['author']}】</section>-->
<section class="content_phone_news" id="contentimg">{$info['content']}</section>

<script type="text/javascript">

    window.onload=function(){
        var imgs = document.getElementById("contentimg").getElementsByTagName("img");
        for(var i = 0;i<imgs.length;i++){
            //alert(i);
            imgs[i].style.width = "100%";


        }

    }
</script>
</body>
</html>
