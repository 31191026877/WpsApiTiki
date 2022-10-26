<div class="col-md-12">
    <div class="box">
        <div class="box-content m-2">
            <div class="float-start">
                <a href="<?php echo admin_url('plugins?page=theme-layout&type=layout&object=layout');?>" class="btn <?php echo ($type_object == 'layout') ? 'btn-blue' : 'btn-green';?>">Layout</a>
                <a href="<?php echo admin_url('plugins?page=theme-layout&type=layout&object=post-category');?>" class="btn <?php echo ($type_object == 'post-category') ? 'btn-blue' : 'btn-green';?>">Post Category</a>
                <a href="<?php echo admin_url('plugins?page=theme-layout&type=layout&object=post');?>" class="btn <?php echo ($type_object == 'post') ? 'btn-blue' : 'btn-green';?>">Post</a>
                <a href="<?php echo admin_url('plugins?page=theme-layout&type=layout&object=banner');?>" class="btn <?php echo ($type_object == 'banner') ? 'btn-blue' : 'btn-green';?>">Banner</a>
            </div>
            <div class="float-end"><button type="submit" class="btn btn-green btn-icon"><?php echo Admin::icon('save');?> Lưu</button></div>
        </div>
    </div>
</div>