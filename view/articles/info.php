

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
    <title>{$info['title']}</title>
    <style>
        .newstitle{ margin:5% auto 3%; width:93%; color:#033333;text-align:left; font-size:1.4em;}
        .title_date{ margin:0 auto 2%; width:93%; line-height:30px; color:#999999;font-size:1.1em;}
        .title_date span{}
        .content_phone_news{ margin:0 auto; width:93%;}
        .bianji{ margin:3% auto 30px; width:95%;color: #999;font-size: 1.1em; text-align:right; }

        #origin-img {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #000;
        }

        #origin-img .swiper-slide img {
            width: 100%;
            vertical-align: middle;
        }

        .swiper-pagination {
            top: 1em;
            bottom: auto;
            color: #fff;
        }
    </style>
</head>

<body style="background:#FFF;">
<section class="newstitle">{$info['title']}</section>
<section class="title_date"><span>发布于</span>&nbsp;&nbsp;&nbsp;<span>{$info['addtime']}</span></section>
<!--<section class="bianji">【作者：{$info['author']}】</section>-->
<section class="content_phone_news" id="contentimg">{$info['content']}</section>

<div class="swiper-container" id="origin-img">
    <div class="swiper-wrapper"></div>
    <div class="swiper-pagination"></div>
    <!-- <div class="upload">图片描述</div> -->
</div>

<link rel="stylesheet" href="{$status_url}/swiper.min.css">
<script src="{$status_url}/swiper.min.js"></script>

<script src="http://libs.baidu.com/jquery/2.1.4/jquery.min.js"></script>
<script type="text/javascript">
    var imgsdata = []
    window.onload = function () {
        var imgs = document.getElementById("contentimg").getElementsByTagName("img");
        for (var i = 0; i < imgs.length; i++) {
            //alert(i);
            imgs[i].style.maxWidth = "100%";
            imgsdata.push(imgs[i].src);
        }
    }

    $(function () {
        // $("#contentimg img").click(function () {
        //   console.log(1111)
        //   var index = $("#contentimg img").index($(this))
        //   console.log(index)
        //   imgList.start = index;
        //   layer.photos({
        //     photos: imgList
        //     , anim: 5
        //   });
        // })


        var swiper = new Swiper('.swiper-container', {
            zoom: true,
            width: window.innerWidth,
            virtual: true,
            spaceBetween: 20,
            pagination: {
                el: '.swiper-pagination',
                type: 'fraction',
            },
            on: {
                click: function () {
                    $('#origin-img').fadeOut('fast');
                    this.virtual.slides.length = 0;
                    this.virtual.cache = [];
                    swiperStatus = false;

                },
            },

        });

        $('#contentimg img').click(function () {
            clickIndex = $("#contentimg img").index($(this))

            imgs = imgsdata;
            for (i = 0; i < imgs.length; i++) {
                swiper.virtual.appendSlide('<div class="swiper-zoom-container"><img src="' + imgs[i] + '" /></div>');
            }
            swiper.slideTo(clickIndex);
            $('#origin-img').fadeIn('fast');
            swiperStatus = true;

        })

        //切换图状态禁止页面缩放
        document.addEventListener('touchstart', function (event) {
            if (event.touches.length > 1 && swiperStatus) {
                event.preventDefault();
            }
        })
        var lastTouchEnd = 0;
        document.addEventListener('touchend', function (event) {
            var now = (new Date()).getTime();
            if (now - lastTouchEnd <= 300) {
                event.preventDefault();
            }
            lastTouchEnd = now;
        }, false)

        document.addEventListener('touchmove', function (e) {
            if (swiperStatus) {
                e.preventDefault();
            }
        })
    })

</script>
</body>
</html>
