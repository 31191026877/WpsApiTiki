<?php
$icon_social = [
    'social_facebook' => '<i class="fab fa-facebook-f"></i>',
    'social_twitter' => '<i class="fab fa-twitter"></i>',
    'social_youtube' => '<i class="fab fa-youtube"></i>',
    'social_instagram' => '<i class="fab fa-instagram"></i>',
    'social_pinterest' => '<i class="fab fa-pinterest-p"></i>',
];
$object->content = str_replace('<div id="eJOY__extension_root" style="all: unset;">&nbsp;</div>', '', $object->content);
$object->content = str_replace('<p>&nbsp;</p>', '', $object->content);
$object->content = trim($object->content);
?>
<div class="layout">
    <div class="container">
        <div class="contact-form-wrapper">
            <div class="col-sm-4 col-md-4 contact-map">
                <?php echo option::get('maps_embed');?>
            </div>
            <div class="col-sm-8 col-md-8" style="padding:15px;">
                <div class="header-contact"><h3><?php echo __('THÔNG TIN LIÊN HỆ', 'page_contact_title_1');?></h3></div>
                <?php if(!empty($object->content)) {?>
                <div class="contact-text"><?php the_content();?></div>
                <?php } ?>
                <div class="contact-info">
                    <p><span><?php echo __('Địa chỉ');?>:</span> <?php echo Option::get('contact_address');?></p>
                    <p><span><?php echo __('Điện thoại');?>:</span> <?php echo Option::get('contact_phone');?></p>
                    <p><span><?php echo __('E-mail');?>:</span> <?php echo Option::get('contact_mail');?></p>
                    <div class="social">
                        <?php $socials = get_theme_social();?>
                        <ul>
                            <?php foreach ($socials as $social) {
                                $icon = Arr::get($icon_social, $social['field']);
                                $url = Option::get($social['field']);
                                if(!empty($icon) && !empty($url)) {
                                    ?>
                                    <li class="social-item <?php echo $social['field'];?>">
                                        <a href=""><?php echo $icon;?></a>
                                    </li>
                                <?php }
                            } ?>
                        </ul>
                    </div>
                </div>
                <div class="header-contact"><h3><?php echo __('LIÊN HỆ CHÚNG TÔI', 'page_contact_title_2');?></h3></div>
                <div class="contact-form">
                    <form id="js_contact_form" class="flexiContactForm" role="form" method="post">
                        <?php echo Admin::loading();?>
                        <div class="form-wrapper">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="<?php echo __('Họ tên của bạn', 'page_contact_placeholder_fullname');?>" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="<?php echo __('Email của bạn', 'page_contact_placeholder_email');?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="tel" class="form-control" id="phone" name="phone" placeholder="<?php echo __('Điện thoại của bạn', 'page_contact_placeholder_phone');?>" required>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" value="" id="content" name="content" placeholder="<?php echo __('Ghi chú', 'page_contact_placeholder_note');?>" style="overflow: hidden; word-wrap: break-word; height: 150px;"></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-effect-default btn-blue" id="contact-form-submit"><span><?php echo __('Gửi');?></span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<br />

<style type="text/css">
    .wrapper { background-color: #E6E6E6;}
    .header-contact h3 {
        color: #304e73;
        font-weight: 700;
        font-size: 25px;
        display: block;
        line-height: 1.5;
        position: relative;
        margin-bottom: 20px;
    }
    .header-contact h3::after {
        content: '';
        display: block;
        position: relative;
        margin-top: 8px;
        width: 50px;
        height: 1px;
        background: rgba(233, 102, 49, 1);
    }
	.contact-form-wrapper {
        --bs-gutter-x: 1.5rem;
        --bs-gutter-y: 0;
        display: flex;
        flex-wrap: wrap;
        margin-top: calc(-1 * var(--bs-gutter-y));
        margin-right: calc(-.5 * var(--bs-gutter-x));
        margin-left: calc(-.5 * var(--bs-gutter-x));
        -webkit-box-shadow: 0px 25px 70px #01358D1A;
        box-shadow: 0px 25px 70px #01358D1A;
        border-radius: 6px;
        overflow: hidden;
        background-color: #fff;
        margin-bottom: 20px;
    }
    .contact-form-wrapper .contact-text {
        letter-spacing: 0.32px;
        color: #5B6880;
        opacity: 1;
        font-size: 15px;
        line-height: 30px;
        font-weight: 400;
        margin:30px auto 40px auto;
    }
	.contact-form-wrapper .form-wrapper {
	    width: 100%;
	    overflow: hidden;
	}
	.contact-form-wrapper .form-group {
		position: relative; margin-bottom: 10px;
	}
	.contact-form-wrapper .form-control {
	    box-shadow: none;
        width: 100%;
        height: 52px;
        background: transparent;
        border: 1px solid #D9DDE4;
        border-radius: 4px;
        padding: 18px;
        font-size: 15px;
        line-height: 1.2;
        letter-spacing: 0.3px;
        color: #97A1B2;
	}
    .contact-form-wrapper .contact-info {
        padding: 10px 0;
    }
    .contact-form-wrapper .contact-info p {
        font-size: 15px;
        line-height: 28px;
        letter-spacing: 0.3px;
        color: #5B6880;
        margin-bottom: 20px;
    }
    .contact-form-wrapper .contact-info p span {
        color: #01358D;
        font-weight: 500;
    }
    .contact-form-wrapper .contact-map {
        padding-left: 0;
    }
    .contact-form-wrapper .contact-map iframe {
        height: 100%;
        width: 100%!important;
        min-height: 101ch;
        margin-bottom: -10px;
    }
    .contact-form-wrapper .social ul { list-style: none; margin-top: 30px;}
    .contact-form-wrapper .social ul li.social-item { display: inline-block; margin-right: 10px;}
    .contact-form-wrapper .social ul li.social-item a {
        display: block; width: 40px; height: 40px; border-radius: 50%; line-height: 40px; text-align: center;
        background-color: var(--theme-color);
        color:#fff;
    }
    .btn-effect-default {
       padding:8px 60px;
    }
    @media(max-width: 768px) {
        .contact-form-wrapper .contact-map {
            padding: 0;
        }
        .contact-form-wrapper .contact-map iframe {
            height: 100%;
            width: 100%!important;
            min-height: 200px;
            margin-bottom: -10px;
        }
    }
</style>

<script>
    $(function () {
        $('#js_contact_form').submit(function () {

            $('.loading').show();

            let form = $(this);

            let data = $(this).serializeJSON();

            data.action = 'ThemeAjax::contactSend';

            $.post(ajax, data, function () {}, 'json').done(function (response) {
                $('.loading').hide();
                show_message(response.message, response.status);
                if (response.status === 'success') {
                    form.trigger('reset');
                }
            });

            return false;
        });
    })
</script>