<div class="col-lg-3 col-md-3 col-sm-6">
	<div class="wg-item">
		<div class="img">
			<?php Admin::img($item->image);?>
		</div>
		<div class="title">
            <div class="info">
                <h3 class="widget-name"><?php echo $item->title;?></h3>
                <div class="action">
                    <button class="wg-install btn-blue btn" data-url="<?php echo $item->id;?>" type="button">Import</button>
                    <a href="<?php echo (isset($item->review)) ? $item->review : '';?>" class="wg-review btn-blue btn" target="_blank">Preview</a>
                </div>
            </div>
		</div>
        <div class="author" style="position: absolute;bottom: 30px;text-align: center;width: 100%;opacity: 0.6">
            <span style="display: inline-block;background-color: #182535;padding: 10px;border-radius: 30px;color: #fff;">Tác giả: <b><?php echo $item->author;?></b></span>
        </div>
	</div>
</div>