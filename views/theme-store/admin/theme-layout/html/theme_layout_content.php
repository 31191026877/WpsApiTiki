<form id="theme_layout_main" class="row">
    <?php include 'theme_layout_tab_item.php';?>
    <?php
        if($type_object == 'layout') include 'theme_layout_tab_layout.php';
        if($type_object == 'post-category') include 'theme_layout_tab_post_category.php';
        if($type_object == 'post') include 'theme_layout_tab_post.php';
        if($type_object == 'banner') include 'theme_layout_tab_banner.php';
    ?>
</form>

<style>
    .layout-item {
        overflow:hidden;
        border:2px solid #F3F4F6;
        margin-bottom:15px;
        cursor: pointer;
    }
    .layout-item label{
        cursor: pointer;
        width:100%; margin-bottom:0;
    }
    .layout-item .img img { width:100%;}
    .layout-item .name {
        background-color:#F3F4F6;
        text-align:center;
        padding:10px;
        font-size:10px;
    }
    @media (min-width: 1200px) {
        .col-lg-1 {
            width: 12%;
        }
    }
</style>

<script type="text/javascript">
	$(function(){
		$( document ).on('submit', '#theme_layout_main', function( e ) {
            let form = $(this);
            let data   = form.serializeJSON();
            data.action = 'Theme_Ajax_Element::save';
            $jqxhr   = $.post(ajax, data, function(data) {}, 'json');
            $jqxhr.done(function( data ) {
                show_message(data.message, data.status);
                if( data.status === 'success' ) {
                }
            });
            return false;
        })
	});
</script>