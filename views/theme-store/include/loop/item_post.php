<div class="item">
    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 img" data-aos="fade-right" data-aos-duration="500">
        <a href="<?= Url::permalink($val->slug);?>" class="effect-hover-zoom">
            <?= Template::img($val->image, $val->title, ['lazy' => 'default']);?>
            <div class="date-box" data-aos="fade-right" data-aos-duration="600">
                <span><?= date("d", strtotime($val->created));?></span>
                <br> Tháng <?= date("m", strtotime($val->created));?>
            </div>
        </a>
    </div>
    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 title" data-aos="fade-up" data-aos-duration="800">
        <h3 class="header"><a href="<?= Url::permalink($val->slug);?>"><?= $val->title;?></a></h3>
        <div class="post-info">
            <?= date("d/m/Y", strtotime($val->created));?> | <i class="fal fa-user-edit"></i> Đăng bởi admin
        </div>
        <div class="description"><?= Str::clear($val->excerpt);?></div>
    </div>
</div>
<div class="cleart"></div>