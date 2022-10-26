<?php
$Form = new FormBuilder();
$Form->add('bacs[enabled]', 'checkbox', ['label' => 'Bật chuyển khoản ngân hàng'], (!empty($payment['enabled'])) ? 'bacs_enabled' : '');
$Form->add('bacs[title]', 'text', ['label' => 'Tiêu đề'], $payment['title']);
$Form->add('bacs[description]', 'textarea', ['label' => 'Mô tả'], $payment['description']);
if(Language::hasMulti()) {
    foreach (Language::list() as $language_key => $language) {
        if($language_key == Language::default()) continue;
        $Form->add('bacs[title_'.$language_key.']', 'text', ['label' => 'Tiêu đề ('.$language['label'].')'], (!empty($payment['title_'.$language_key])) ? $payment['title_'.$language_key] : '');
        $Form->add('bacs[description_'.$language_key.']', 'textarea', ['label' => 'Mô tả ('.$language['label'].')'], (!empty($payment['description_'.$language_key])) ? $payment['description_'.$language_key] : '');
    }
}
$Form->add('bacs[img]', 'image', ['label' => 'Icon'], $payment['img']);
$Form = apply_filters('admin_payment_'.$key.'_input_fields', $Form, $payment);
$Form->html(false);
?>
<div class="col-md-12">
    <label class="control-label"><?php echo __('Ngân Hàng');?></label>
    <div class="form-group group" id="bacs_accounts">
        <table class="table table-bordered wcmc-setting-bacs" cellspacing="0">
            <thead>
            <tr>
                <th class="sort">&nbsp;</th>
                <th><?php echo __('Tên tài khoản');?></th>
                <th><?php echo __('Số tài khoản');?></th>
                <th><?php echo __('Tên ngân hàng');?></th>
                <th><?php echo __('Chi nhánh');?></th>
                <th></th>
            </tr>
            </thead>
            <tbody class="accounts ui-sortable">
                <?php if(have_posts($payment['bank'])) {?>
                    <?php foreach ($payment['bank'] as $key => $account): ?>
                        <tr class="account wcmc-setting-bacs__item">
                            <td class="sort"></td>
                            <td><input type="text" class="form-control" value="<?php echo $account['bacs_account_name'];?>" name="bacs_account_name[<?php echo $key;?>]"></td>
                            <td><input type="text" class="form-control" value="<?php echo $account['bacs_account_number'];?>" name="bacs_account_number[<?php echo $key;?>]"></td>
                            <td><input type="text" class="form-control" value="<?php echo $account['bacs_bank_name'];?>" name="bacs_bank_name[<?php echo $key;?>]"></td>
                            <td><input type="text" class="form-control" value="<?php echo $account['bacs_bank_branch'];?>" name="bacs_bank_branch[<?php echo $key;?>]"></td>
                            <td class="sort">
                                <button class="btn-delete btn-icon btn-red">Xóa</button>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php } ?>
            </tbody>
            <tfoot>
            <tr>
                <th colspan="7">
                    <a href="#" class="add btn-green btn">+ Thêm tài khoản</a>
                </th>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
<div class="clearfix"></div>
<style type="text/css">
	.radio label, .checkbox label { padding-left: 0; }
    table.table tr { border:1px solid #ccc; }
</style>
<script type="text/javascript">
	$(function() {
		$('#bacs_accounts').on( 'click', 'a.add', function(){
			let size = $('#bacs_accounts').find('tbody .account').length;
			$('<tr class="account wcmc-setting-bacs__item">\
					<td class="sort"></td>\
					<td><input type="text" class="form-control" name="bacs_account_name[' + size + ']" /></td>\
					<td><input type="text" class="form-control" name="bacs_account_number[' + size + ']" /></td>\
					<td><input type="text" class="form-control" name="bacs_bank_name[' + size + ']" /></td>\
					<td><input type="text" class="form-control" name="bacs_bank_branch[' + size + ']" /></td>\
					<td class="sort">\
						<button class="btn-delete btn-icon btn-red">Xóa</button>\
					</td>\
				</tr>').appendTo('#bacs_accounts table tbody');
			return false;
		});
		$('#bacs_accounts').on( 'click', 'button.btn-delete', function(){
			$(this).closest('tr.wcmc-setting-bacs__item').remove();
			return false;
		});
	});
</script>