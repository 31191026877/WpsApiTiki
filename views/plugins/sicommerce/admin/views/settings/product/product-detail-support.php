<div class="box">
    <div class="header"> <h2>HỖ TRỢ</h2> </div>
    <div class="box-content">
        <div class="row m-1">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-3">
                        <?php  $input = array('field' => 'product_support[enable]', 'type' => 'switch', 'label' => 'Bật tắt item'); ?>
                        <?php echo _form($input, $product_support['enable']);?>
                    </div>
                    <div class="col-md-12">
                        <?php foreach (Language::list() as $key => $language) {
                            $item_key = '_'.$key;
                            if($key == Language::default()) $item_key = '';
                            $input = array('field' => 'product_support[title'.$item_key.']', 'type'	=> 'text', 'label' => 'Tiêu đề ('.$language['label'].')');
                            echo _form($input, (isset($product_support['title'.$item_key])) ? $product_support['title'.$item_key] : '');
                        }
                        ?>
                    </div>
                    <div class="col-md-12">
                        <?php  $input = array('field' => 'product_support[image]', 'type'	=> 'image', 'label' => 'Ảnh hỗ trợ'); ?>
                        <?php echo _form($input, $product_support['image']);?>
                    </div>
                    <div class="col-md-12">
                        <?php  $input = array('field' => 'product_support[url]', 'type'	=> 'text', 'label' => 'Url liên kết'); ?>
                        <?php echo _form($input, $product_support['url']);?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>