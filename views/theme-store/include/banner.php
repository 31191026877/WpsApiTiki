<?php
	$bannerSetting  = get_theme_layout_setting('banner');

	$breadcrumb = Template::breadcrumb();

	$image_banner = Template::imgLink(option::get('banner_img'));

	$name = '';

	if(Template::isMethod('index')) {
		if(isset($category) && have_posts($category)) {
			$name = $category->name;
		}
        else if(isset($brand) && have_posts($brand)) {
            $name = $brand->name;
        }
        else if(is_page('products_index')) {
			$name = __('Sản phẩm');
		}
	}
	else {
		if(isset($object) && have_posts($object)) {
			$name = $object->title;
		}
	}
?>

<div class="box-bg-top box-bg-<?php echo $bannerSetting['type'];?> <?php echo Template::getPage();?>">
	<div class="parallax box-bg-title" style="background:url(<?php echo $image_banner;?>);background-size: cover;">
		<div class="container title">
			<h1 class="header animated fadeInDown"><?= $name;?></h1>
			<div class="animated fadeInUp"><?php do_action('breadcrumb_render');?></div>
		</div>
	</div>
</div>

<style type="text/css">
	.box-bg-top {
		min-height: <?php echo $bannerSetting['height'];?>px; position: relative; text-align: center;
		margin-bottom:10px;
        overflow: hidden;
	}
    .container>.box-bg-top>.container { width:100%;}
    .box-bg-top .container .container { width:100%; padding:0;}
	.box-bg-top .box-bg-title {
	    background-color: rgba(0,0,0,0.5); text-align: center;
	    height: 130%; width: 100%;
	    position: absolute; left: 0; top: 0; color: #fff;
	}
    .box-bg-top .box-bg-title:after {
        content:'';
        background-color: rgba(0,0,0,0.5); text-align: center;
        height: 130%; width: 100%;
        position: absolute; left: 0; top: 0; color: #fff;

    }
	.box-bg-top .box-bg-title .title {
	    position: relative;  top: 30%; transform: translateY(-30%); z-index: 1;
	}
	.box-bg-top .box-bg-title .title h1.header {
        text-align: left; font-size: 40px; color:#fff;
        padding-bottom: 15px;
    }
    .box-bg-top .breadcrumb-box {
        background-color: transparent;
        text-align: left;
    }
    .box-bg-top .breadcrumb-box .btn-breadcrumb a.btn.btn-default {
        color:#fff;
    }
    .box-bg-top .breadcrumb-box .btn-breadcrumb span { font-size: 12px;}

    .box-bg-top.post_detail .title h1.header {
        text-align: left; font-size: 20px; color:#fff;
        padding-bottom: 15px;
    }
	.wrapper { padding-top: 0;}

    .box-bg-top.box-bg-center .title h1.header {
        text-align: center;
    }
    .box-bg-top.box-bg-center .breadcrumb-box {
        text-align: center;
    }
</style>