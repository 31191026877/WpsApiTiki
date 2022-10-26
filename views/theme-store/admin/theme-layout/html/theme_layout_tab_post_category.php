<?php
    $layout_setting         = get_theme_layout_setting('post_category');

    $layout_post_category   = theme_layout_list(option::get('layout_post_category', 'layout-sidebar-right-banner-2'));

    $post_categories = PostCategory::gets(array('mutilevel' => 'post_categories')); unset($post_categories[0]);
?>
<div class="col-md-6">
    <div class="box">
        <div class="header"><h2>VERTICAL</h2></div>
        <div class="box-content m-2">
            <div class="col-md-3 col-lg-3">
                <div class="layout-item">
                    <label for="post-category-layout-object-vertical">
                        <div class="img"><?php Template::img('layout/layout-post-vertical.png');?></div>
                        <div class="name">
                            <input type="radio" value="vertical" name="post_category[style]" id="post-category-layout-object-vertical" <?php echo ($layout_setting['style'] == 'vertical') ? 'checked' : '';?>>
                            <span>List Vertical</span>
                        </div>
                    </label>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<div class="col-md-6">
    <div class="box">
        <div class="header"><h2>HORIZONTAL</h2></div>
        <div class="box-content m-2">
            <div class="row">
                <div class="col-md-3 col-lg-3">
                    <div class="layout-item">
                        <label for="post-category-layout-object-horizontal">
                            <div class="img"><?php Template::img('layout/layout-post-horizontal.png');?></div>
                            <div class="name">
                                <input type="radio" value="horizontal" name="post_category[style]" id="post-category-layout-object-horizontal" <?php echo ($layout_setting['style'] == 'horizontal') ? 'checked' : '';?>>
                                <span>List Horizontal</span>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="col-md-8 col-lg-8">
                    <div class="layout-item">
                        <?php
                            $input = array("field"=>"post_category[horizontal][category_row_count]", "label"=>"Số bài viết trên trên 1 hàng (desktop)", "type"=>"col", 'args' => array('max' => 5));
                            echo _form($input, $layout_setting['horizontal']['category_row_count']);
                        ?>
                        <?php
                            $input = array("field"=>"post_category[horizontal][category_row_count_tablet]", "label"=>"Số bài viết trên trên 1 hàng (tablet)", "type"=>"col", 'args' => array('max' => 5));
                            echo _form($input, $layout_setting['horizontal']['category_row_count_tablet']);
                        ?>
                        <?php
                            $input = array("field"=>"post_category[horizontal][category_row_count_mobile]", "label"=>"Số bài viết trên trên 1 hàng (mobile)", "type"=>"col", 'args' => array('max' => 5));
                            echo _form($input, $layout_setting['horizontal']['category_row_count_mobile']);
                        ?>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<?php if(isset($layout_post_category['sidebar'])) {?>
<div class="col-md-12">
    <div class="box">
        <div class="header"><h2>SIDEBAR</h2></div>
        <div class="box-content m-2">
            <div class="row">
                <!-- bài viết mới -->
                <div class="col-md-2 col-lg-2">
                    <?php  $input = array('field' => 'post_category[sidebar][new][toggle]', 'type'	=> 'switch', 'label' => 'Bài viết mới'); ?>
                    <?php echo _form($input, $layout_setting['sidebar']['new']['toggle']);?>
                </div>

                <div class="col-md-2 col-lg-2">
                    <label>Nguồn dữ liệu</label>
                    <select name="post_category[sidebar][new][data]" class="form-control" required>
                        <option value="post-category-current" <?php echo ($layout_setting['sidebar']['new']['data'] == 'post-category-current') ? 'selected' : '';?>>Lấy theo danh mục hiện tại</option>
                        <option value="0" <?php echo ($layout_setting['sidebar']['new']['data'] == 0) ? 'selected' : '';?>>Lấy theo tất cả danh mục</option>
                        <?php foreach ($post_categories as $cate_id => $cate_name) { ?>
                        <option value="<?php echo $cate_id;?>" <?php echo ($layout_setting['sidebar']['new']['data'] == $cate_id) ? 'selected' : '';?>><?php echo $cate_name;?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-md-2 col-lg-2">
                    <?php  $input = array('field' => 'post_category[sidebar][new][limit]', 'type'	=> 'number', 'label' => 'Số lượng bài viết'); ?>
                    <?php echo _form($input, $layout_setting['sidebar']['new']['limit']);?>
                </div>

                <div class="col-md-2 col-lg-2">
                    <?php  $input = array('field' => 'post_category[sidebar][new][title]', 'type'	=> 'text', 'label' => 'Tiêu đề'); ?>
                    <?php echo _form($input, $layout_setting['sidebar']['new']['title']);?>
                </div>
                <div class="clearfix"></div>

                <!-- Bài viết nổi bật -->
                <div class="col-md-2 col-lg-2">
                    <?php  $input = array('field' => 'post_category[sidebar][hot][toggle]', 'type'	=> 'switch', 'label' => 'Bài viết nổi bật'); ?>
                    <?php echo _form($input, $layout_setting['sidebar']['hot']['toggle']);?>
                </div>

                <div class="col-md-2 col-lg-2">

                    <label>Nguồn dữ liệu</label>

                    <select name="post_category[sidebar][hot][data]" class="form-control" required>
                        <option value="post-category-current" <?php echo ($layout_setting['sidebar']['hot']['data'] == 'post-category-current') ? 'selected' : '';?>>Lấy theo danh mục hiện tại</option>
                        <option value="0" <?php echo ($layout_setting['sidebar']['hot']['data'] == '0') ? 'selected' : '';?>>Lấy theo tất cả danh mục</option>
                        <?php foreach ($post_categories as $cate_id => $cate_name) { ?>
                        <option value="<?php echo $cate_id;?>" <?php echo ($layout_setting['sidebar']['hot']['data'] == $cate_id) ? 'selected' : '';?>><?php echo $cate_name;?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-md-2 col-lg-2">
                    <?php  $input = array('field' => 'post_category[sidebar][hot][limit]', 'type'	=> 'number', 'label' => 'Số lượng bài viết'); ?>
                    <?php echo _form($input, $layout_setting['sidebar']['hot']['limit']);?>
                </div>

                <div class="col-md-2 col-lg-2">
                    <?php  $input = array('field' => 'post_category[sidebar][hot][title]', 'type'	=> 'text', 'label' => 'Tiêu đề'); ?>
                    <?php echo _form($input, $layout_setting['sidebar']['hot']['title']);?>
                </div>
                <div class="clearfix"></div>

                <!-- bài viết theo danh mục con -->
                <div class="col-md-2 col-lg-2">
                    <?php  $input = array('field' => 'post_category[sidebar][sub][toggle]', 'type'	=> 'switch', 'label' => 'Bài viết theo danh mục con'); ?>
                    <?php echo _form($input, $layout_setting['sidebar']['sub']['toggle']);?>
                </div>

                <div class="col-md-2 col-lg-2">
                    <label>Nguồn dữ liệu</label>
                    <select name="post_category[sidebar][sub][data]" class="form-control" required>
                        <option value="post-category-current" <?php echo ($layout_setting['sidebar']['sub']['data'] == 'post-category-current') ? 'selected' : '';?>>Lấy theo danh mục hiện tại</option>
                        <?php foreach ($post_categories as $cate_id => $cate_name) { ?>
                        <option value="<?php echo $cate_id;?>" <?php echo ($layout_setting['sidebar']['sub']['data'] == $cate_id) ? 'selected' : '';?>><?php echo $cate_name;?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-md-2 col-lg-2">
                    <?php  $input = array('field' => 'post_category[sidebar][sub][limit]', 'type'	=> 'number', 'label' => 'Số lượng bài viết'); ?>
                    <?php echo _form($input, $layout_setting['sidebar']['sub']['limit']);?>
                </div>

                <div class="col-md-2 col-lg-2">
                    <label>Trạng thái dữ liệu</label>
                    <select name="post_category[sidebar][sub][status]" class="form-control" required>
                        <option value="new" <?php echo ($layout_setting['sidebar']['sub']['status'] == 'new') ? 'selected' : '';?>>Bài viết mới</option>
                        <option value="hot" <?php echo ($layout_setting['sidebar']['sub']['status'] == 'hot') ? 'selected' : '';?>>Bài viết nổi bật</option>
                    </select>
                </div>
                <div class="clearfix"></div>

                <!-- lấy theo sidebar -->
                <div class="col-md-2 col-lg-2">
                    <?php  $input = array('field' => 'post_category[sidebar][sidebar][toggle]', 'type'	=> 'switch', 'label' => 'Widget sidebar'); ?>
                    <?php echo _form($input, $layout_setting['sidebar']['sidebar']['toggle']);?>
                </div>

                <div class="col-md-2 col-lg-2">
                    <label>Nguồn dữ liệu</label>
                    <select name="post_category[sidebar][sidebar][data]" class="form-control" required>
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

<div class="col-md-12">
    <div class="box">
        <div class="header"><h2>BANNER</h2></div>
        <div class="box-content m-2">
            <div class="row">
                <div class="col-md-3 col-lg-3">
                    <div class="layout-item">
                        <label for="post-category-banner-in-container">
                            <div class="name">
                                <input type="radio" value="in-container" name="post_category[banner]" id="post-category-banner-in-container" <?php echo ($layout_setting['banner'] == 'in-container') ? 'checked' : '';?>>
                                <span>CONTAINER BANNER</span>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="col-md-3 col-lg-3">
                    <div class="layout-item">
                        <label for="post-category-banner-full-width">
                            <div class="name">
                                <input type="radio" value="full-width" name="post_category[banner]" id="post-category-banner-full-width" <?php echo ($layout_setting['banner'] == 'full-width') ? 'checked' : '';?>>
                                <span>FULL WIDTH BANNER</span>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>