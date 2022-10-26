<div class="clearfix"></div>
<div class="js_product_pagination">
    <?php if(isset($pagination) && is_object($pagination) && method_exists($pagination, 'html' )) echo $pagination->html();?>
</div>
