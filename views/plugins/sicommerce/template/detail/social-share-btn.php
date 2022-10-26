<div class="social-block">
    <script src="https://sp.zalo.me/plugins/sdk.js"></script>
    <div class="social-btns">
        <a class="btn facebook" href="javascript:;" onclick="window.open('http://www.facebook.com/sharer.php?u=<?= fullurl();?>', 'Chia sẽ sản phẩm này cho bạn bè', 'menubar=no,toolbar=no,resizable=no,scrollbars=no, width=600,height=455')"><i class="fab fa-facebook-f"></i></a>
        <a class="btn twitter" href="javascript:;" onclick="window.open('https://twitter.com/home?status=<?= fullurl();?>')"><i class="fab fa-twitter"></i></a>
        <a class="btn google" href="javascript:;" onclick="window.open('https://mail.google.com/mail/u/0/?view=cm&to&su=<?= $object->title;?>&body=<?= fullurl();?>&bcc&cc&fs=1&tf=1', 'Chia sẽ sản phẩm này cho bạn bè', 'menubar=no,toolbar=no,resizable=no,scrollbars=no, width=600,height=455')">
            <?php echo Template::img(Url::base(PRODUCT_PATH.'assets/images/Google-Icon.png'));?>
        </a>
        <script src="https://sp.zalo.me/plugins/sdk.js"></script>
        <a class="btn skype zalo-share-button" data-href="<?php echo Url::current();?>" data-oaid="579745863508352884" data-layout="4" data-color="blue" data-customize=true>
            <?php echo Template::img(Url::base(PRODUCT_PATH.'assets/images/Zalo-Icon.png')) ;?>
        </a>
        <a class="btn skype" data-fancybox="gallery" href="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=<?php echo Url::current();?>">
            <?php echo Template::img(Url::base(PRODUCT_PATH.'assets/images/qr-code.png'));?>
        </a>
    </div>
</div>
