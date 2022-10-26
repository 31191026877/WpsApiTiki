<?php echo admin_notices();?>
<ul id="list_dashboard_widget">
	<?php Dashboard::render() ;?>
</ul>

<div class="clearfix"></div>
<div class="col-md-12">
	<a href="#" class="manage-dashboard-btn" data-fancybox data-src="#manage-dashboard-widget"><i class="fa fa-plus"></i> Manage Widgets</a>
	<a href="#" class="manage-dashboard-btn dashboard-sort-widget" data-action="on"><i class="fad fa-sort-up"></i> Bật Sắp Xếp</a>
</div>
<div style="display: none" id="manage-dashboard-widget">
	<form class="manage-dashboard-widget__list">
		<?php foreach (Dashboard::getAll() as $id_widget => $widget): ?>
			<?php $widget_en = (isset($dashboard_info[$id_widget])) ? $dashboard_info[$id_widget] : 1; ?>
			<section class="wrap_widget_posts_recent">
				<div class="widget_info">
                    <?php if(!empty($widget['option']['icon'])) {
                        echo $widget['option']['icon'];
                    } else { ?>
                        <i class="fas fa-edit" style="background-color: #f3c200"></i>
                    <?php } ?>

	                <span class="widget_name"><?php echo $widget['title'];?></span>
                </div>
                <div class="swc_wrap">
                	<?php echo _form([ 'field' => 'dashboard['.$id_widget.']', 'type'  => 'switch' ], $widget_en );?>
                </div>
            </section>
		<?php endforeach ?>
		<div class="clearfix"></div>
		<button type="submit" class="btn btn-icon btn-blue"><?php echo Admin::icon('save');?> Save</button>
	</form>
</div>
<style>
	.wrapper .content .page-content { margin-top:0; min-height: 445px;}
	.wrapper .box .box-content { height: 445px; overflow: hidden;}
	.manage-dashboard-btn {
	    display: inline-block;
	    color: #bcc3c7;
	    padding: 10px 15px;
	    font-size: 14px;
	    font-weight: 400;
	    border: 1px dashed #bcc3c7;
	    border-radius: 2px;
	    margin-bottom: 15px;
	    max-width: 155px;
	}
	#manage-dashboard-widget { min-width: 400px; max-width: 100%; }
	.wrap_widget_posts_recent {
	    height: 65px;
	    line-height: 45px;
	}
	.wrap_widget_posts_recent .widget_info { width: calc(100% - 110px); float: left; }
	.wrap_widget_posts_recent i {
	    font-size: 30px;
	    width: 45px;
	    height: 45px;
	    color: #fff;
	    line-height: 45px;
	    text-align: center;
	    float: left;
	}
	.wrap_widget_posts_recent .widget_name { padding-left: 10px; }
	.wrap_widget_posts_recent .swc_wrap {
	    height: 20px;
	    width: 100px;
	    float: right;
	}
	.wrap_widget_posts_recent .swc_wrap label.control-label { display: none; }
	.toggle__handler { top:0; }
</style>
<script async>
	$(function () {
		let dashboard_sortable;
        $('.dashboard-sort-widget').click(function () {
            let action = $(this).attr('data-action');
            if(action === 'on') {
                dashboard_sortable = Sortable.create(list_dashboard_widget, {
                    animation: 200,
                    onEnd: function (/**Event*/evt) {
                        let o = 0;
                        let d = {};
                        $.each($(".list-dashboard-item"), function(e) {
                            i = $(this).attr("data-id"); d[o] = i; o++;
                        });
                        $.post(ajax, { 'action':'Ajax_Admin_Dashboard_Action::sort', 'data' : d }, function(data) {}, 'json').done(function(response) {
                            show_message(response.message, response.status);
                        });
                    },
                });
                $(this).attr('data-action', 'off');
                $(this).html('<i class="fad fa-sort-up"></i> Tắt Sắp Xếp');
            }
            if(action === 'off') {
                let state = dashboard_sortable.option("disabled"); // get
                dashboard_sortable.option("disabled", !state);
                $(this).attr('data-action', 'on');
                $(this).html('<i class="fad fa-sort-up"></i> Bật Sắp Xếp');
            }
            return false;
        });
		$('.manage-dashboard-widget__list').submit(function () {
			let data = $(this).serializeJSON();
			data.action = 'Ajax_Admin_Dashboard_Action::save';
			$.post(ajax, data , function(data) {}, 'json').done(function( response ) {
			    show_message(response.message, response.status);
			});
			return false;
		});
	});
</script>