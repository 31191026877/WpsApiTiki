<div class="systemTabs-container">
    <div class="box">
        <div class="box-content p-3">
            <div class="row">
            <?php foreach ($systemTabs as $key => $tabs) {?>
                <div class="col-lg-4 col-sm-6 col-12">
                    <a href="<?php echo Url::admin('system/'.$key);?>">
                        <div class="systemTab-item">
                            <div class="systemTab-item__icon">
                                <?php echo $tabs['icon'];?>
                            </div>
                            <div class="systemTab-item__info">
                                <div class="systemTab-item__title"><?php echo $tabs['label'];?></div>
                                <div class="systemTab-item__description"><?php echo (!empty($tabs['description'])) ? $tabs['description'] : '';?></div>
                            </div>
                        </div>
                    </a>
                </div>
            <?php } ?>
            </div>
        </div>
    </div>
</div>

<style>
    .systemTab-item {
        display: flex; padding:15px;
    }
    .systemTab-item__icon {
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -webkit-align-items: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: center;
        -webkit-justify-content: center;
        -ms-flex-pack: center;
        justify-content: center;
        -webkit-flex-shrink: 0;
        -ms-flex-negative: 0;
        flex-shrink: 0;
        width: 50px;
        height:  50px;
        margin-right: 1.6rem;
        border-radius: 3px;
        background-color: #f4f6f8;
        font-size: 20px;
        color: var(--theme-color);
    }
    .systemTab-item__info {
        flex-grow: 1;
        max-width: 100%;
    }
    .systemTab-item__title {
        font-weight: 700;
        color: #0d3064;
        font-size:15px;
    }
    .systemTab-item__description {
        color: grey;
    }
    .systemTab-item:hover {
        background-color: #f9fafb;
        text-decoration: none;
        outline: none;
    }
    .systemTab-item:hover .systemTab-item__icon {
        background-color: #dfe3e8;
    }
</style>