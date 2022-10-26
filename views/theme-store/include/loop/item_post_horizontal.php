<div class="item item-post-horizontal wow animated fadeIn">
    <div class="img">
        <a href="<?= Url::permalink($val->slug); ?>"><?= Template::img($val->image, $val->title, ['lazy' => 'default']); ?></a>
    </div>
    <div class="title">
        <div class="post-info">
            <i class="far fa-calendar"></i><b><?php echo date("d/m/y", strtotime($val->created)); ?></b>&nbsp; Đăng bởi: <b>Admin</b>
        </div>
        <div class="headerDesc">
            <h3 class="header"><a href="<?= Url::permalink($val->slug); ?>"><?= $val->title; ?></a></h3>
            <div class="description"><a href="<?= Url::permalink($val->slug); ?>"><?= str_word_cut(Str::clear($val->excerpt), 30); ?></a></div>
        </div>
    </div>
</div>