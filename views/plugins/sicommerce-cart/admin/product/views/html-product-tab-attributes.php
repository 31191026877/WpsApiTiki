<div class="attributes-form" id="add-group-attributes">
    <select id="product_option_id" class="form-control" required="required" style="width: 250px;"> <?php
        if ( $options = Attributes::gets() ) :
            foreach ( $options as $val ) : ?>
                <option value="<?php echo $val->id;?>"><?php echo $val->title;?></option>
                <?php
            endforeach;
        else : ?>
        <?php
        endif; ?>
    </select>
	<button type="button" class="btn btn-green save-attributes"><?php echo Admin::icon('add');?> Thêm</button>
</div>
<hr style="margin: 0;"/>
<div class="attributes-group" id="result-attributes-items" role="tablist" aria-multiselectable="true" style="margin-bottom: 10px;"></div>
