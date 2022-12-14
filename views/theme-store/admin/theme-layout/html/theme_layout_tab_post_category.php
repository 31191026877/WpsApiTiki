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
                            $input = array("field"=>"post_category[horizontal][category_row_count]", "label"=>"S??? b??i vi???t tr??n tr??n 1 h??ng (desktop)", "type"=>"col", 'args' => array('max' => 5));
                            echo _form($input, $layout_setting['horizontal']['category_row_count']);
                        ?>
                        <?php
                            $input = array("field"=>"post_category[horizontal][category_row_count_tablet]", "label"=>"S??? b??i vi???t tr??n tr??n 1 h??ng (tablet)", "type"=>"col", 'args' => array('max' => 5));
                            echo _form($input, $layout_setting['horizontal']['category_row_count_tablet']);
                        ?>
                        <?php
                            $input = array("field"=>"post_category[horizontal][category_row_count_mobile]", "label"=>"S??? b??i vi???t tr??n tr??n 1 h??ng (mobile)", "type"=>"col", 'args' => array('max' => 5));
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
                <!-- b??i vi???t m???i -->
                <div class="col-md-2 col-lg-2">
                    <?php  $input = array('field' => 'post_category[sidebar][new][toggle]', 'type'	=> 'switch', 'label' => 'B??i vi???t m???i'); ?>
                    <?php echo _form($input, $layout_setting['sidebar']['new']['toggle']);?>
                </div>

                <div class="col-md-2 col-lg-2">
                    <label>Ngu???n d??? li???u</label>
                    <select name="post_category[sidebar][new][data]" class="form-control" required>
                        <option value="post-category-current" <?php echo ($layout_setting['sidebar']['new']['data'] == 'post-category-current') ? 'selected' : '';?>>L???y theo danh m???c hi???n t???i</option>
                        <option value="0" <?php echo ($layout_setting['sidebar']['new']['data'] == 0) ? 'selected' : '';?>>L???y theo t???t c??? danh m???c</option>
                        <?php foreach ($post_categories as $cate_id => $cate_name) { ?>
                        <option value="<?php echo $cate_id;?>" <?php echo ($layout_setting['sidebar']['new']['data'] == $cate_id) ? 'selected' : '';?>><?php echo $cate_name;?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-md-2 col-lg-2">
                    <?php  $input = array('field' => 'post_category[sidebar][new][limit]', 'type'	=> 'number', 'label' => 'S??? l?????ng b??i vi???t'); ?>
                    <?php echo _form($input, $layout_setting['sidebar']['new']['limit']);?>
                </div>

                <div class="col-md-2 col-lg-2">
                    <?php  $input = array('field' => 'post_category[sidebar][new][title]', 'type'	=> 'text', 'label' => 'Ti??u ?????'); ?>
                    <?php echo _form($input, $layout_setting['sidebar']['new']['title']);?>
                </div>
                <div class="clearfix"></div>

                <!-- B??i vi???t n???i b???t -->
                <div class="col-md-2 col-lg-2">
                    <?php  $input = array('field' => 'post_category[sidebar][hot][toggle]', 'type'	=> 'switch', 'label' => 'B??i vi???t n???i b???t'); ?>
                    <?php echo _form($input, $layout_setting['sidebar']['hot']['toggle']);?>
                </div>

                <div class="col-md-2 col-lg-2">

                    <label>Ngu???n d??? li???u</label>

                    <select name="post_category[sidebar][hot][data]" class="form-control" required>
                        <option value="post-category-current" <?php echo ($layout_setting['sidebar']['hot']['data'] == 'post-category-current') ? 'selected' : '';?>>L???y theo danh m???c hi???n t???i</option>
                        <option value="0" <?php echo ($layout_setting['sidebar']['hot']['data'] == '0') ? 'selected' : '';?>>L???y theo t???t c??? danh m???c</option>
                        <?php foreach ($post_categories as $cate_id => $cate_name) { ?>
                        <option value="<?php echo $cate_id;?>" <?php echo ($layout_setting['sidebar']['hot']['data'] == $cate_id) ? 'selected' : '';?>><?php echo $cate_name;?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-md-2 col-lg-2">
                    <?php  $input = array('field' => 'post_category[sidebar][hot][limit]', 'type'	=> 'number', 'label' => 'S??? l?????ng b??i vi???t'); ?>
                    <?php echo _form($input, $layout_setting['sidebar']['hot']['limit']);?>
                </div>

                <div class="col-md-2 col-lg-2">
                    <?php  $input = array('field' => 'post_category[sidebar][hot][title]', 'type'	=> 'text', 'label' => 'Ti??u ?????'); ?>
                    <?php echo _form($input, $layout_setting['sidebar']['hot']['title']);?>
                </div>
                <div class="clearfix"></div>

                <!-- b??i vi???t theo danh m???c con -->
                <div class="col-md-2 col-lg-2">
                    <?php  $input = array('field' => 'post_category[sidebar][sub][toggle]', 'type'	=> 'switch', 'label' => 'B??i vi???t theo danh m???c con'); ?>
                    <?php echo _form($input, $layout_setting['sidebar']['sub']['toggle']);?>
                </div>

                <div class="col-md-2 col-lg-2">
                    <label>Ngu???n d??? li???u</label>
                    <select name="post_category[sidebar][sub][data]" class="form-control" required>
                        <option value="post-category-current" <?php echo ($layout_setting['sidebar']['sub']['data'] == 'post-category-current') ? 'selected' : '';?>>L???y theo danh m???c hi???n t???i</option>
                        <?php foreach ($post_categories as $cate_id => $cate_name) { ?>
                        <option value="<?php echo $cate_id;?>" <?php echo ($layout_setting['sidebar']['sub']['data'] == $cate_id) ? 'selected' : '';?>><?php echo $cate_name;?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-md-2 col-lg-2">
                    <?php  $input = array('field' => 'post_category[sidebar][sub][limit]', 'type'	=> 'number', 'label' => 'S??? l?????ng b??i vi???t'); ?>
                    <?php echo _form($input, $layout_setting['sidebar']['sub']['limit']);?>
                </div>

                <div class="col-md-2 col-lg-2">
                    <label>Tr???ng th??i d??? li???u</label>
                    <select name="post_category[sidebar][sub][status]" class="form-control" required>
                        <option value="new" <?php echo ($layout_setting['sidebar']['sub']['status'] == 'new') ? 'selected' : '';?>>B??i vi???t m???i</option>
                        <option value="hot" <?php echo ($layout_setting['sidebar']['sub']['status'] == 'hot') ? 'selected' : '';?>>B??i vi???t n???i b???t</option>
                    </select>
                </div>
                <div class="clearfix"></div>

                <!-- l???y theo sidebar -->
                <div class="col-md-2 col-lg-2">
                    <?php  $input = array('field' => 'post_category[sidebar][sidebar][toggle]', 'type'	=> 'switch', 'label' => 'Widget sidebar'); ?>
                    <?php echo _form($input, $layout_setting['sidebar']['sidebar']['toggle']);?>
                </div>

                <div class="col-md-2 col-lg-2">
                    <label>Ngu???n d??? li???u</label>
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