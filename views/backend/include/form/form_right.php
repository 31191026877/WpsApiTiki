<?php if (!empty($FormRightHtml)) { ?>
    <?php foreach ($FormRightHtml as $id => $form): $cookie = get_cookie("boxCollapse_".$id); ?>
        <div class="box">
            <a class="btn-collapse js_btn_collapse <?php echo (empty($cookie)) ? '':'active';?>" id="js_btn_collapse_<?php echo $id;?>" data-bs-toggle="collapse" href="#<?php echo $id;?>"><?php echo (get_cookie("boxCollapse_".$id)== null)?'<i class="fal fa-chevron-up"></i>':'<i class="fal fa-chevron-down"></i>';?></a>
            <div class="header" style="<?php echo (empty($cookie)) ? 'display:none;':'display:block;';?>"><h3 class="pull-left"><?php echo $form['name'];?></h3></div>
            <div class="box-content collapse <?php echo (empty($cookie)) ? 'show' : '';?>" id="<?php echo $id;?>">
                <?php if(Metabox::has($id)) { Metabox::render($id, (isset($object)) ? $object : []); } ?>
                <div class="row m-1">
                    <?php echo $form['html'];?>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    <?php endforeach ?>
<?php } ?>

<?php do_action('before_admin_form_right');?>
