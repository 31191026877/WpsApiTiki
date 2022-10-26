<div class="widget products-detail-box onlineSupport">
    <div class="sidebar-title"><h4 class="header"><?php echo $title;?></h4></div>
    <div class="supportimage">
        <?php Template::img($image, $title);?>
    </div>
    <h3 class="supportTitle3"><?php echo __('Để được hỗ trợ tốt nhất. Hãy gọi', 'product_support_call');?></h3>
    <div class="phoneNumber">
        <a href="tel:<?php echo option::get('contact_phone');?>" title="<?php echo option::get('contact_phone');?>"><?php echo option::get('contact_phone');?></a>
    </div>
    <div class="or"><span><?php echo __('HOẶC', 'product_support_or');?></span></div>
    <h3 class="supportTitle" style="margin: 15px 0 10px 0;"><?php echo __('hỗ trợ trực tuyến', 'product_support_title');?></h3>
    <a class="chatNow sprite-icon_chat" href="<?php echo $url;?>" title="Chat với chúng tôi"><?php echo __('Liên hệ với chúng tôi', 'product_support_button');?></a>
</div>
