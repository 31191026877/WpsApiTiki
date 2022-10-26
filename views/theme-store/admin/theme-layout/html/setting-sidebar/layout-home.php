<div class="col-md-12">
    <div class="box">
        <div class="header" style="margin-bottom: 15px;"><h2>Home Layout</h2></div>
        <div class="box-content row">
            <?php for($i = 1; $i <= 3; $i++) { ?>
                <div class="col-md-4 col-lg-4">
                    <div class="layout-item">
                        <label for="home-layout-<?php echo $i;?>">
                            <div class="img"><?php Template::img('layout/home-layout-'.$i.'.png');?></div>
                            <div class="name">
                                <input type="radio" value="layout-home-<?php echo $i;?>" name="layout[home-layout]" id="home-layout-<?php echo $i;?>" <?php echo ($layout_home == 'layout-home-'.$i) ? 'checked' : '';?>>
                                <span>Home <?php echo $i;?></span>
                            </div>
                        </label>
                    </div>
                </div>
            <?php } ?>
            <div class="clearfix"></div>
        </div>
    </div>
</div>