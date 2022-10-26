<div class="col-xs-12 col-md-12">
    <div class="box">
        <div class="box-content" style="padding:10px;">

            <div class="form-group" style="overflow:hidden;">
                <label for="input" class="control-label col-md-3">Trang nội dung</label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9 row">
                    <?php echo _form([ 'field' => 'gallery_template_support[page]', 'type' => 'switch'], $gallery_support['page']);?>
                </div>
            </div>

            <?php foreach ($categories as $key => $cateType) { $cate = Taxonomy::getCategory($cateType); ?>
            <div class="form-group" style="overflow:hidden;">
                <label for="input" class="control-label col-md-3"><?php echo $cate['labels']['name'];?></label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9 row">
                    <?php echo _form([ 'field' => 'gallery_template_support[category]['.$cateType.']', 'type' => 'switch'], $gallery_support['category'][$cateType]);?>
                </div>
            </div>
            <?php } ?>

            <?php foreach ($posts as $key => $postType) { $post = Taxonomy::getPost($postType); ?>
            <div class="form-group" style="overflow:hidden;">
                <label for="input" class="control-label col-md-3"><?php echo $post['labels']['name'];?></label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9 row">
                    <?php echo _form([ 'field' => 'gallery_template_support[post]['.$postType.']', 'type' => 'switch'], $gallery_support['post'][$postType]);?>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>