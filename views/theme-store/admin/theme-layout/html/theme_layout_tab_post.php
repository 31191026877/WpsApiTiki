<?php
    $layout_setting     = get_theme_layout_setting('post');

    $layout_post        = theme_layout_list(option::get('layout_post', 'layout-sidebar-right-banner-2'));

    $post_categories    = PostCategory::gets(array('mutilevel' => 'post_categories')); unset($post_categories[0]);
?>
<?php if(isset($layout_post['sidebar'])) {?>
<div class="col-md-12">
    <div class="box">
        <div class="header"><h2>SIDEBAR</h2></div>
        <div class="box-content m-2">
            <div class="row">
                <!-- bài viết mới -->
                <div class="col-md-2 col-lg-2">
                    <?php  $input = array('field' => 'post[sidebar][new][toggle]', 'type'	=> 'switch', 'label' => 'Bài viết mới'); ?>
                    <?php echo _form($input, $layout_setting['sidebar']['new']['toggle']);?>
                </div>

                <div class="col-md-2 col-lg-2">
                    <label>Nguồn dữ liệu</label>
                    <select name="post[sidebar][new][data]" class="form-control" required>
                        <option value="post-category-current" <?php echo ($layout_setting['sidebar']['new']['data'] == 'post-category-current') ? 'selected' : '';?>>Lấy theo danh mục hiện tại</option>
                        <option value="0" <?php echo ($layout_setting['sidebar']['new']['data'] == 0) ? 'selected' : '';?>>Lấy theo tất cả danh mục</option>
                        <?php foreach ($post_categories as $cate_id => $cate_name) { ?>
                        <option value="<?php echo $cate_id;?>" <?php echo ($layout_setting['sidebar']['new']['data'] == $cate_id) ? 'selected' : '';?>><?php echo $cate_name;?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-md-2 col-lg-2">
                    <?php  $input = array('field' => 'post[sidebar][new][limit]', 'type'	=> 'number', 'label' => 'Số lượng bài viết'); ?>
                    <?php echo _form($input, $layout_setting['sidebar']['new']['limit']);?>
                </div>

                <div class="col-md-2 col-lg-2">
                    <?php  $input = array('field' => 'post[sidebar][new][title]', 'type'	=> 'text', 'label' => 'Tiêu đề'); ?>
                    <?php echo _form($input, $layout_setting['sidebar']['new']['title']);?>
                </div>
                <div class="clearfix"></div>

                <!-- Bài viết nổi bật -->
                <div class="col-md-2 col-lg-2">
                    <?php  $input = array('field' => 'post[sidebar][hot][toggle]', 'type'	=> 'switch', 'label' => 'Bài viết nổi bật'); ?>
                    <?php echo _form($input, $layout_setting['sidebar']['hot']['toggle']);?>
                </div>

                <div class="col-md-2 col-lg-2">

                    <label>Nguồn dữ liệu</label>

                    <select name="post[sidebar][hot][data]" class="form-control" required>
                        <option value="post-category-current" <?php echo ($layout_setting['sidebar']['hot']['data'] == 'post-category-current') ? 'selected' : '';?>>Lấy theo danh mục hiện tại</option>
                        <option value="0" <?php echo ($layout_setting['sidebar']['hot']['data'] == '0') ? 'selected' : '';?>>Lấy theo tất cả danh mục</option>
                        <?php foreach ($post_categories as $cate_id => $cate_name) { ?>
                        <option value="<?php echo $cate_id;?>" <?php echo ($layout_setting['sidebar']['hot']['data'] == $cate_id) ? 'selected' : '';?>><?php echo $cate_name;?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-md-2 col-lg-2">
                    <?php  $input = array('field' => 'post[sidebar][hot][limit]', 'type'	=> 'number', 'label' => 'Số lượng bài viết'); ?>
                    <?php echo _form($input, $layout_setting['sidebar']['hot']['limit']);?>
                </div>

                <div class="col-md-2 col-lg-2">
                    <?php  $input = array('field' => 'post[sidebar][hot][title]', 'type'	=> 'text', 'label' => 'Tiêu đề'); ?>
                    <?php echo _form($input, $layout_setting['sidebar']['hot']['title']);?>
                </div>
                <div class="clearfix"></div>

                <!-- bài viết theo danh mục con -->
                <div class="col-md-2 col-lg-2">
                    <?php  $input = array('field' => 'post[sidebar][sub][toggle]', 'type'	=> 'switch', 'label' => 'Bài viết theo danh mục con'); ?>
                    <?php echo _form($input, $layout_setting['sidebar']['sub']['toggle']);?>
                </div>

                <div class="col-md-2 col-lg-2">
                    <label>Nguồn dữ liệu</label>
                    <select name="post[sidebar][sub][data]" class="form-control" required>
                        <option value="post-category-current" <?php echo ($layout_setting['sidebar']['sub']['data'] == 'post-category-current') ? 'selected' : '';?>>Lấy theo danh mục hiện tại</option>
                        <?php foreach ($post_categories as $cate_id => $cate_name) { ?>
                        <option value="<?php echo $cate_id;?>" <?php echo ($layout_setting['sidebar']['sub']['data'] == $cate_id) ? 'selected' : '';?>><?php echo $cate_name;?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-md-2 col-lg-2">
                    <?php  $input = array('field' => 'post[sidebar][sub][limit]', 'type'	=> 'number', 'label' => 'Số lượng bài viết'); ?>
                    <?php echo _form($input, $layout_setting['sidebar']['sub']['limit']);?>
                </div>

                <div class="col-md-2 col-lg-2">
                    <label>Trạng thái dữ liệu</label>
                    <select name="post[sidebar][sub][status]" class="form-control" required>
                        <option value="new" <?php echo ($layout_setting['sidebar']['sub']['status'] == 'new') ? 'selected' : '';?>>Bài viết mới</option>
                        <option value="hot" <?php echo ($layout_setting['sidebar']['sub']['status'] == 'hot') ? 'selected' : '';?>>Bài viết nổi bật</option>
                    </select>
                </div>
                <div class="clearfix"></div>

                <!-- Bài viết liên quan -->
                <div class="col-md-2 col-lg-2">
                    <?php  $input = array('field' => 'post[sidebar][related][toggle]', 'type'	=> 'switch', 'label' => 'Bài viết liên quan'); ?>
                    <?php echo _form($input, $layout_setting['sidebar']['related']['toggle']);?>
                </div>

                <div class="col-md-2 col-lg-2">
                    <?php  $input = array('field' => 'post[sidebar][related][limit]', 'type'	=> 'number', 'label' => 'Số lượng bài viết'); ?>
                    <?php echo _form($input, $layout_setting['sidebar']['related']['limit']);?>
                </div>

                <div class="col-md-2 col-lg-2">
                    <?php  $input = array('field' => 'post[sidebar][related][title]', 'type'	=> 'text', 'label' => 'Tiêu đề'); ?>
                    <?php echo _form($input, $layout_setting['sidebar']['related']['title']);?>
                </div>
                <div class="clearfix"></div>

                <!-- lấy theo sidebar -->
                <div class="col-md-2 col-lg-2">
                    <?php  $input = array('field' => 'post[sidebar][sidebar][toggle]', 'type'	=> 'switch', 'label' => 'Widget sidebar'); ?>
                    <?php echo _form($input, $layout_setting['sidebar']['sidebar']['toggle']);?>
                </div>

                <div class="col-md-2 col-lg-2">
                    <label>Nguồn dữ liệu</label>
                    <select name="post[sidebar][sidebar][data]" class="form-control" required>
                        <?php foreach ($ci->sidebar as $sidebar_id => $sidebar) { ?>
                        <option value="<?php echo $sidebar_id;?>" <?php echo ($layout_setting['sidebar']['sidebar']['data'] == $sidebar_id) ? 'selected' : '';?>>Sidebar -- <?php echo $sidebar['name'];?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
<?php } ?>