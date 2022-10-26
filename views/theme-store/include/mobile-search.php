<div class="panel--sidebar td-search-wrap-mob" id="search-sidebar">
    <div class="td-drop-down-search" aria-labelledby="td-header-search-button">
        <form method="get" class="td-search-form" action="search">
            <div role="search" class="td-search-input">
                <input id="td-header-search-mob" type="text" value="" name="keyword" autocomplete="off" placeholder="<?php echo __('Tìm kiếm');?>...">
                <input type="hidden" value="products" name="type">
            </div>
            <button class="td-search-button" type="submit"><i class="fal fa-search"></i></button>
        </form>
        <?php echo Admin::loading();?>
        <div id="td-aj-search-mob"></div>
    </div>
    <div class="panel__close"><a href="#" class="js_panel__close"><i class="fal fa-times"></i></a></div>
</div>

<div id="td-search-wrap-dest" style="display: none;">
    <div class="search">
        <form class="navbar-form form-search" action="search?type=products" method="get" role="search" style="margin:0;padding:0;">
            <div class="form-group" style="margin:0;padding:0;">
                <input class="form-control search-field" type="text" value="" name="keyword" placeholder="<?php echo __('Tìm kiếm');?>" id="searchInput">
                <input type="hidden" value="products" name="type">
            </div>
            <button type="submit" class="btn btn-search btn-default" style="width:50px;"><i class="fa fa-search" aria-hidden="true"></i></button>
        </form>
    </div>
</div>