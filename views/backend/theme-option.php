<?php Admin::partial('include/action_bar');?>
<div class="col-md-12 system">
    <?php Template::displayMessage();?>
    <?php Admin::loading('ajax_item_save_loader');?>
    <!-- Nav tabs -->
    <div class="system-tab">
        <ul class="nav nav-tabs" role="tablist">
            <?php
            $i = key($this->themeOptions['group']) ;
            if(isset($_COOKIE["of_current_opt"])) $i = $_COOKIE["of_current_opt"];
            foreach ($this->themeOptions['group'] as $key => $value) {
                if(isset($value['root']) && $value['root'] && !is_super_admin()) continue; ?>
            <li class="nav-item">
                <a href="#<?= $key;?>_pane" id="<?= $key;?>_tab" data-bs-toggle="tab" data-bs-target="#<?= $key;?>_pane" role="tab" aria-controls="<?= $key;?>_pane" aria-selected="true" class="nav-link <?php echo ($i == $key) ? 'active' : '';?>"><?= $value['icon'];?><span><?php echo $value['label'];?></span></a>
            </li>
            <?php } ?>
        </ul>
    </div>

    <script>
        $('#navId a').click(e => {
            e.preventDefault();
            $(this).tab('show');
        });
    </script>

    <!-- Tab panes -->
    <div class="system-tab-content tab-content">
        <?php foreach ($this->themeOptions['group'] as $key => $group) { ?>
        <div class="tab-pane fade <?php echo ($i == $key) ? 'show active' : '';?>" id="<?= $key;?>_pane" aria-labelledby="<?= $key;?>_tab" tabindex="0" role="tabpanel">
            <?php foreach ($group['sub'] as $keySub => $sub) { ?>
                <div class="box">
                    <a class="js_btn_collapse <?php echo ($key == $keySub) ? '' : 'active' ;?>" id="btn-<?php echo $key.'_'.$keySub;?>"><?php echo ($key == $keySub) ? '<i class="fal fa-chevron-up"></i>' : '<i class="fal fa-chevron-down"></i>' ;?></a>
                    <div class="header btn-collapse collapsed" data-bs-toggle="collapse" href="#<?php echo $key.'_'.$keySub;?>"><h2><?php echo $sub['label'];?></h2></div>
                    <div class="box-content collapse <?php echo ($key == $keySub) ? 'show' : '' ;?>" id="<?php echo $key.'_'.$keySub;?>">
                        <div class="row m-1"><?php echo $sub['form']->html(true);?></div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <?php } ?>
    </div>
</div>
<script>
    $(document).ready(function(){
        let box = $(".box-content.collapse");
        box.on("hide.bs.collapse", function(){
            let id = $(this).attr('id');
            let button = $('#btn-' + id);
            button.addClass('active');
            button.html('<i class="fal fa-chevron-down"></i>');
        });
        box.on("show.bs.collapse", function(){
            let id = $(this).attr('id');
            let button = $('#btn-' + id);
            button.removeClass('active');
            button.html('<i class="fal fa-chevron-up"></i>');
        });
    });
</script>