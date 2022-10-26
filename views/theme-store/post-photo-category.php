<div class="photo-gallery-category">
    <?php foreach ($objects as $key => $item): ?>
    <div class="col-xs-12 col-sm-6 col-md-4">
        <div class="item <?php echo ($key == 0) ?'active':'';?>">
            <div class="img">
                <a href="<?php echo Url::permalink($item->slug);?>">
                    <?php Template::img($item->image, $item->title);?>
                    <div class="title">
                        <h3><?php echo $item->title;?></h3>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<nav class="text-center">
    <?= (isset($pagination))?$pagination->html():'';?>
</nav>
<script>
    $(function () {
        let itemW = $('.photo-gallery-category .item').first().width();
        $('.photo-gallery-category .item .img').css('height', itemW+'px');
    });
</script>

<style type="text/css">
    .photo-gallery-category { overflow: hidden; }
    .photo-gallery-category .item { margin: 10px 0; overflow: hidden; position: relative }
    .photo-gallery-category .item .img {
        overflow: hidden;
        border-radius:5px;
    }
    .photo-gallery-category .item .img img {
        -webkit-transform: scale(1.03);
        -ms-transform: scale(1.03);
        transform: scale(1.03);
        -webkit-transition: -webkit-transform .5s ease-out;
        transition: -webkit-transform .5s ease-out;
        -o-transition: transform .5s ease-out;
        transition: transform .5s ease-out;
        transition: transform .5s ease-out,-webkit-transform .5s ease-out;
        width:100%; height: 100%; object-fit: cover;
    }
    .photo-gallery-category .item .title {
        position: absolute; top:0; left:0;
        width: 100%; height: 100%;
        background-color: rgba(0,0,0,0.5);
        padding:20px;
        opacity: 0;
        transition: 0.8s all;
    }
    .photo-gallery-category .item .title h3 {
        margin-top: 60%;
        font-size: 18px; line-height: 25px; letter-spacing: 5px;
        text-align: left;
        color:#fff;
        text-transform: uppercase;
        opacity: 0;
        margin-left: -10px;
        transition: 0.5s all;
    }
    .photo-gallery-category .item .title h3:after {
        content: "";
        display: table;
        margin: 10px 0;
        height: 1px;
        width: 0;
        background: #fff;
        transition: 0.8s all;
    }
    .photo-gallery-category .item:hover .img,
    .photo-gallery-category .item.active .img {
        box-shadow: 2px 2px 20px #333;
    }
    .photo-gallery-category .item:hover .title,
    .photo-gallery-category .item.active .title {
        opacity: 1; transition: 0.3s all;
    }
    .photo-gallery-category .item:hover .title h3,
    .photo-gallery-category .item.active .title h3 {
        opacity: 1;
        margin-left: 0px;
        transition: 0.5s all;
    }
    .photo-gallery-category .item:hover .title h3:after,
    .photo-gallery-category .item.active .title h3:after {
        width: 100%;
        transition: 0.8s all;
    }
    .photo-gallery-category .item:hover img {
        -webkit-transform: scale(1.03) translateX(1%);
        -ms-transform: scale(1.03) translateX(1%);
        transform: scale(1.03) translateX(1%);
    }
    @media (max-width: 768px) {
        .photo-gallery-category .item .img {
            box-shadow: 2px 2px 20px #333;
        }
        .photo-gallery-category .item .title {
            opacity: 1; transition: 0.3s all;
        }
        .photo-gallery-category .item .title h3 {
            opacity: 1;
            margin-left: 0px;
            transition: 0.5s all;
        }
        .photo-gallery-category .item .title h3:after {
            width: 100%;
            transition: 0.8s all;
        }
    }
</style>