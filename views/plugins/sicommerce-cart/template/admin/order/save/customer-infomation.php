<?php
$fields = get_checkout_fields();

$Form = new FormBuilder();

$customer_id = 0;

if(!empty($customer) && have_posts($customer)) {

    $customer_id = $customer->id;

    $fields['billing']['billing_fullname']['value'] = $customer->firstname.' '.$customer->lastname;

    $fields['billing']['billing_email']['value'] = $customer->email;

    $fields['billing']['billing_phone']['value'] = $customer->phone;

    $fields['billing']['billing_address']['value'] = User::getMeta($customer->id, 'address', true);

    $fields = apply_filters('admin_order_add_customer_fields', $fields, $customer, (isset($order) && have_posts($order)) ? $order : [] );
}
?>
    <input type="hidden" name="customer_id" class="form-control" value="<?php echo $customer_id;?>">
    <!-- Tìm kiếm khách hàng -->
    <section class="ui-layout__section">
        <h3>Thanh Toán</h3>
        <div class="order-customer-info">
            <?php
            do_action('before_order_save_billing_customer');
            foreach ($fields['billing'] as $key => $input) {
                $Form->add($input['field'], $input['type'], $input, (!empty($input['value'])) ? $input['value'] : '');
            }
            $Form->html(false);
            do_action('after_order_save_billing_customer');?>
        </div>
        <div class="clearfix"> </div>
    </section>
<script>
    $(function() {
        $.ajaxSetup({
            beforeSend: function(xhr, settings) {
                settings.data += '&csrf_test_name=' + encodeURIComponent(getCookie('csrf_cookie_name'));
            }
        });

        let city = $('#billing_city');

        let district = $('#billing_districts');

        let ward = $('#billing_ward');

        city.select2();

        district.select2();

        ward.select2();

        city.each(function (index, value) {

            let data = {
                province_id: $(this).val(),
                district_id : '<?php echo User::getMeta($customer_id, 'districts', true);?>',
                action      : 'Cart_Ajax::loadDistricts'
            };

            $jqxhr = $.post(ajax, data, function () {}, 'json');

            $jqxhr.done(function (response) {
                if (response.status === 'success') {
                    district.html(response.data).promise().done(function () {
                        let data = {
                            district_id: district.val(),
                            ward_id : '<?php echo User::getMeta($customer_id, 'ward', true);?>',
                            action: 'Cart_Ajax::loadWard'
                        };
                        $.post(ajax, data, function () {}, 'json').done(function (response) {
                            if (response.status === 'success') {
                                ward.html(response.data);
                            }
                        });
                    });
                }
            });
        });

        city.change(function () {
            let data = {
                province_id: $(this).val(),
                action: 'Cart_Ajax::loadDistricts'
            };
            $jqxhr = $.post(ajax, data, function () {}, 'json');
            $jqxhr.done(function (response) {
                if (response.status === 'success') {
                    district.html(response.data);
                }
            });
        });

        district.change(function () {
            let data = {
                district_id: $(this).val(),
                action: 'Cart_Ajax::loadWard'
            };
            $jqxhr = $.post(ajax, data, function () {}, 'json');
            $jqxhr.done(function (response) {
                if (response.status === 'success') {
                    ward.html(response.data);
                }
            });
        });
    });
</script>
