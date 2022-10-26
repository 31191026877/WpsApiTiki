<div class="box">
    <div class="header"> <h2>Nội dung</h2> </div>
    <div class="box-content">
        <div class="row m-1">
        <?php  $input = array('field' => 'product_content[category][enable]', 'type' => 'switch', 'label' => 'Bật tắt danh mục sản phẩm'); ?>
        <?php echo FormBuilder::render($input, sicommerce::config('product_content.category.enable'));?>

        <?php  $input = array('field' => 'product_content[content_top][enable]', 'type' => 'switch', 'label' => 'Bật tắt nội dung đầu trang'); ?>
        <?php echo FormBuilder::render($input, sicommerce::config('product_content.content_top.enable'));?>

        <?php  $input = array('field' => 'product_content[content_bottom][enable]', 'type' => 'switch', 'label' => 'Bật tắt nội dung cuối trang'); ?>
        <?php echo FormBuilder::render($input, sicommerce::config('product_content.content_bottom.enable'));?>
        </div>
    </div>
</div>