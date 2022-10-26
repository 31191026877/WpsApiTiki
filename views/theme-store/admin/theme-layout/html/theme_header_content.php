 <?php foreach ($header_data as $folder => $item): ?>
<div class="col-md-12 header_service" data-id="<?php echo $item['id'];?>" data-folder="<?php echo $folder;?>" data-type="<?php echo $type;?>">
	<div class="box">
        <div class="header"> <h3><?php echo $item['title'];?></h3> </div>
		<div class="box-content">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-9 header_service__img"> <?php Template::img($item['image']);?> </div>
					<div class="col-md-3 header_service__action">
						<?php if( !is_dir($path.'/'.$folder) ) { ?>
							<button type="button" class="header-install btn-green btn btn-block">DOWNLOAD</button>
						<?php } else { ?>
							<?php if( empty($header_style_active[$type][$folder]) ) { ?>
								<button type="button" class="header-active btn-green btn" >KÍCH HOẠT</button>
								<button type="button" class="header-remove btn-red btn">GỞ BỎ</button>
							<?php } else { ?>
								<button type="button" class="header-unactive btn-white btn">NGƯNG KÍCH HOẠT</button>
							<?php } ?>
						<?php } ?>
					</div>   
				</div>
			</div>
		</div>
	</div>
</div>
<?php endforeach ?>

<script type="text/javascript">
	$(function(){

		let ThemeElementHandler = function() {
			$( document )
				//cài widget
				.on('click', '.header-install', this.download)
				.on('click', '.header-active', this.active)
				.on('click', '.header-unactive', this.unactive)
		};

        ThemeElementHandler.prototype.download = function(e) {

			let button = $(this);

			let item   = $(this).closest('.header_service');

			let id 		= item.attr('data-id');

			let type 	= item.attr('data-type');

			button.text('Đang download');

			let data = {
				'action' 		: 'Theme_Ajax_Element::download',
				'id' 			: id,
				'type' 			: type,
			};

			$jqxhr   = $.post(ajax, data, function(data) {}, 'json');

			$jqxhr.done(function( data ) {

				show_message(data.message, data.status);

				if(data.status === 'success') {

					button.text('Đang cài đặt');

					setTimeout( function()  {
                        ThemeElementHandler.prototype.install( item, button );
					}, 500);

					
				}
			});

			return false;
		}

        ThemeElementHandler.prototype.install = function( item, button ) {

			let id 		= item.attr('data-id');

			let type 	= item.attr('data-type');

			let header_action = button.closest('.header_service__action');

			let data = {
				'action' 		: 'Theme_Ajax_Element::install',
				'id' 			: id,
				'type' 			: type,
			};

			$jqxhr   = $.post(ajax, data, function(data) {}, 'json');

			$jqxhr.done(function( data ) {

				show_message(data.message, data.status);

				if( data.status === 'success' ) {

					button.text('Đã cài đặt');

					button.remove();

					header_action.append('<button type="button" class="header-active btn-green btn btn-block">KÍCH HOẠT</button><button type="button" class="header-remove btn-red btn btn-block">GỞ BỎ</button>');
				}

			});

			return false;
		};

        ThemeElementHandler.prototype.active = function( e ) {

			let button = $(this);

			let item   = $(this).closest('.header_service');

			let type 	= item.attr('data-type');

			let folder 	= item.attr('data-folder');

			let header_action = $(this).closest('.header_service__action');

			let data = {
				'action' 		: 'Theme_Ajax_Element::active',
				'folder' 		: folder,
				'type' 			: type,
			};

			$jqxhr   = $.post(ajax, data, function(data) {}, 'json');

			$jqxhr.done(function( data ) {

				show_message(data.message, data.status);

				if( data.status === 'success' ) {

					$('.header-unactive').each(function(){
						header_service__action = $(this).closest('.header_service__action');
						header_service__action.find('.header-unactive').remove();
						header_service__action.append('<button type="button" class="header-active btn-green btn btn-block">KÍCH HOẠT</button><button type="button" class="header-remove btn-red btn btn-block">GỞ BỎ</button>');
					});

					header_action.find('.header-active').remove();
					header_action.find('.header-remove').remove();
					header_action.append('<button type="button" class="header-unactive btn-white btn btn-block">NGƯNG KÍCH HOẠT</button>');
				}
			});

			return false;
		};

        ThemeElementHandler.prototype.unactive = function( e ) {

			let button = $(this);

			let item   = $(this).closest('.header_service');

			let type 	= item.attr('data-type');

			let folder 	= item.attr('data-folder');

			let header_action = $(this).closest('.header_service__action');

			let data = {
				'action' 		: 'Theme_Ajax_Element::unActive',
				'folder' 		: folder,
				'type' 			: type,
			};

			$jqxhr   = $.post(ajax, data, function(data) {}, 'json');

			$jqxhr.done(function( data ) {

				show_message(data.message, data.status);

				if( data.status === 'success' ) {
					header_action.find('.header-unactive').remove();
					header_action.append('<button type="button" class="header-active btn-green btn btn-block">KÍCH HOẠT</button><button type="button" class="header-remove btn-red btn btn-block">GỞ BỎ</button>');
				}
			});

			return false;
		};

		new ThemeElementHandler();
	});
</script>