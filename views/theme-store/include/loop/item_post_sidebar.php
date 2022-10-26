<div class="item">
    <div class="item_content">
        <div class="img">
            <a href="<?php echo Url::permalink($val->slug);?>" title="<?php echo $val->title;?>">
                <?php Template::img($val->image, $val->title, ['lazy' => 'default']);?>
            </a>
        </div>
        <div class="title">
            <h3>
                <a href="<?php echo Url::permalink($val->slug);?>" title="<?php echo $val->title;?>">
                    <?php echo $val->title;?>
                </a>
            </h3>
             <div class="time"><?= date('D m, Y', strtotime($val->created));?></div>
        </div>
    </div>
</div>