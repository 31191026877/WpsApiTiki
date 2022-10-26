<?php
if(isset($category) && have_posts($category) && !empty($category)) {
    $args = [
        'where'  => ['parent_id' => $category->id],
    ];
    $listCategorySub = ProductCategory::gets($args);
    ?>
    <div class="box-category-child">
        <div class="row">
            <?php foreach ($listCategorySub as $key => $value): ?>
                <div class="col-md-4 col-item">
                    <div class="category-item">
                        <a href="<?php echo Url::permalink($value->slug);?>" title="<?php echo $value->name;?>">
                            <div class="img effect-hover-zoom">
                                <?php echo Template::img($value->image, $value->name); ?>
                            </div>
                            <div class="title" style="<?php echo $value->name;?>">
                                <h3 class="title-item"><?php echo $value->name;?></h3>
                            </div>
                        </a>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
    <style>
        .box-category-child {text-align: center; }
        .box-category-child .category-item {
            position: relative; overflow: hidden; text-align: center; border-radius: 5px;
            margin-bottom: 10px;transition: all 0.25s cubic-bezier(.02,.01,.47,1);
        }
        .box-category-child .category-item:hover {
            border-radius: 10px;
            transform: translateY(-5px);
            box-shadow: 0 4px 60px 0 rgb(0 0 0 / 20%), 0 0 0 transparent;
        }
        .box-category-child .category-item .img { height: 200px;}
        .box-category-child .category-item .img img { width: 100%; height: 100%; object-fit: cover; }
        .box-category-child .category-item .title {
            text-align: left; vertical-align: bottom;
            position: absolute;
            z-index: 99;
            height: 100%; width: 100%;
            top:0;left: 0;
            background-image: linear-gradient(to top, rgb(0 0 0 / 40%) 0%, rgb(0 0 0 / 20%) 15%, rgb(0 0 0 / 10%) 30%, rgba(0,0,0,0) 100%);
        }
        .box-category-child .category-item .title h3.title-item {
            position: absolute;
            left: 10px; bottom: 10px;
            color: #fff;
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            margin-bottom: 5px;
        }

        @media (max-width: 1124px) {
            .box-category-child .category-item .img { height: 150px;}
        }
        @media (max-width: 768px) {
            .box-category-child .category-item .img { height: 150px;}
        }
        @media (max-width: 500px) {
            .box-category-child .category-item { margin-bottom: 10px; }
            .box-category-child .col-item:last-of-type .category-item {
                margin-bottom: 0px;
            }
            .box-category-child .category-item .title h3.title-item {
                font-size: 14px;
            }
        }
    </style>
    <?php
}
