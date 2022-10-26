<div class="clearfix"></div>
<?php Sidebar::render('footer-top'); ?>
<div class="clearfix"></div>
<footer>
    <div class="container"> <?php Sidebar::render('footer-main'); ?> </div>
</footer>
<div class="footer-bottom">
    <div class="container">
        <p><a href="https://sikido.vn">© <?php echo date("Y"); ?> <?php echo option::get('general_label'); ?> - Thiết kế bởi sikido.vn</a></p>
    </div>
</div>
<?php if (!Device::isGoogleSpeed()) { ?>
    <div id="fb-root"></div>
    <script type='text/javascript' defer>
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.4&appId=879572492127382";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
<?php } ?>
<?php echo do_action('cle_footer'); ?>

<a href="#top" id="back-to-top" class="backtop" title="lên đầu trang">
    <div class="border_btt">
        <i class="fas fa-arrow-up"></i>
    </div>
</a>
<style>
    .backtop {
        position: fixed;
        top: auto !important;
        color: #fff;
        border-radius: 50%;
        background: #c6cddb;
        text-decoration: none;
        transition: opacity 0.2s ease-out;
        opacity: 0;
        -webkit-backface-visibility: hidden;
        -moz-backface-visibility: hidden;
        -ms-backface-visibility: hidden;
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
        -moz-backface-visibility: hidden;
        -ms-backface-visibility: hidden;
        backface-visibility: hidden;
        width: 58px;
        height: 58px;
        z-index: 199;
        background-color: #cddca1;
        border-radius: 50%;
        text-align: center;
        line-height: 58px;
        font-size: 18px;
        color: #fff;
        cursor: pointer;
        display: inline-block;
        float: left;
    }
    .backtop:hover{
        color: #000;
    }

    .backtop .border_btt {
        display: inline-block;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        margin: 4px;
        line-height: 50px;
        background: #91ad41;
    }
    

    @media (min-width: 1441px) {
        .backtop {
            right: 10%;
            bottom: 80px;
        }
    }

    @media (max-width: 767px) {
        .backtop {
            right: 5%;
            bottom: 40px;
        }
    }
</style>
<script defer>
    $("a[href='#top']").click(function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
    $(window).scroll(function() {

        if ($(this).scrollTop() > 0) {


            $(".backtop").css('opacity', 1);

        } else {
            $(".backtop").css('opacity', 0);

        }

    });
</script>