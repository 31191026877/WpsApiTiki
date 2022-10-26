<?php
$FormBuilder = new FormBuilder();

$formAdmin = formAdmin();

$FormLeftTopHtml    = $formAdmin->toHtml('leftTop');

$FormLeftBottomHtml = $formAdmin->toHtml('leftBottom');

$FormRightHtml      = $formAdmin->toHtml('right');

$FormTabsHtml      = $formAdmin->toHtml('tabs');

$FormLangHtml      = $formAdmin->toHtml('lang');

echo form_open();

if(!Template::isClass('plugins') && Template::isMethod('index')) {
    Admin::loading('ajax_loader');
    include_once('form_short.php');
}
else if(have_posts($formAdmin)) {
    Admin::loading('ajax_loader');
    if(have_posts($FormRightHtml) && ( have_posts($FormLangHtml) || have_posts($FormLeftTopHtml) || have_posts($FormLeftBottomHtml))) {
        echo '<div class="row">';
        echo '<div class="col-sm-8 col-md-8 col-form-left">';
        include_once('form_left.php');
        echo '</div>';
        echo '<div class="col-sm-4 col-md-4 col-form-right">';
        include_once('form_right.php');
        echo '</div>';
        echo '</div>';
    }
    else {
        echo '<div class="row">';
        echo '<div class="col-sm-12 col-md-12 col-form-left">';
        include_once('form_left.php');
        include_once('form_right.php');
        echo '</div>';
        echo '</div>';
    }
}
?>
<script>
    $(document).ready(function(){
        let boxContent = $(".box-content.collapse");
        $(".js_btn_collapse").on("click", function(){
            $(this).closest('.box').toggleClass('ov-hidden');
        });

        const collection = document.getElementsByClassName("box-content");
        for (let i = 0; i < collection.length; i++) {
            let id = collection[i].getAttribute("id");
            if(typeof id != 'undefined') {
                let btnCollapse = $('#js_btn_collapse_' + id);
                collection[i].addEventListener('hide.bs.collapse', event => {
                    console.log(id);
                    btnCollapse.addClass('active');
                    btnCollapse.closest('.box').find('.header').show();
                    btnCollapse.html('<i class="fal fa-chevron-down"></i>');
                    setCookie('boxCollapse_'+id, true, 60);
                })
                collection[i].addEventListener('show.bs.collapse', event => {
                    console.log(id);
                    btnCollapse.removeClass('active');
                    btnCollapse.closest('.box').find('.header').hide();
                    btnCollapse.html('<i class="fal fa-chevron-up"></i>');
                    delCookie('boxCollapse_'+id);
                })
            }
        }
    });
</script>
<?php if (isset($object->id)) { ?><input type="hidden" name="id" value="<?php echo $object->id;?>"><?php } ?>