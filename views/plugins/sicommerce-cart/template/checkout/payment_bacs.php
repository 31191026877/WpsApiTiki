<div id="list-bank" style="margin-top:10px;margin-left: 50px;">
<?php if( !empty($payment['bank'])) {
	foreach ($payment['bank'] as $bank) { ?>
		<div style="padding:10px 10px;border: 1px dashed #ccc; margin-bottom: 10px;">
			<p style="font-size: 15px">Tên tài khoản 	: <?php echo $bank['bacs_account_name'];?></p>
			<p style="font-size: 15px">Số tài khoản 	: <?php echo $bank['bacs_account_number'];?></p>
			<p style="font-size: 15px">Ngân hàng		: <b><?php echo $bank['bacs_bank_name'];?></b></p>
			<p style="font-size: 15px">Chi nhánh		: <?php echo $bank['bacs_bank_branch'];?></p>
		</div>
		<?php
	}
} ?>
</div>
<script>
    $(function () {
        $('input[name="_payment"]').change(function(){
            let _payment = $(this).val();
            if(_payment === 'bacs') {
                $('#list-bank').slideDown();
            }
            else {
                $('#list-bank').slideUp();
            }
        });
    })
</script>