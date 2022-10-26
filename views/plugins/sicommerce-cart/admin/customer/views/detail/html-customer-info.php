<div class="box">
	<section class="box-content">
			<section class="ui-layout__section" style="overflow: hidden;">
                <header class="ui-layout__title pull-left"><h2>Thông tin liên hệ</h2></header>
                <a class="btn btn-default pull-right"  href="#" id="js_customer_btn__edit">
                    <i class="fal fa-user-edit"></i>  Sửa thông tin
                </a>
            </section>
			<section class="ui-layout__section" id="customer-info">
                <p class=""><span><?php echo $customer->firstname.' '.$customer->lastname;?></span></p>
                <p class=""><span><?php echo $customer->phone;?></span></p>
                <p class=""><span><?php echo $customer->email;?></span></p>
                <p class=""><span><?php echo User::getMeta($customer->id, 'address', true);?></span></p>
                <?php
                    $city       = User::getMeta($customer->id, 'city', true);
                    $districts  = User::getMeta($customer->id, 'districts', true);
                ?>
                <p class=""><span><?php echo (!empty($city)) ? Cart_Location::cities($city) : '';?></span></p>
                <p class=""><span><?php echo (!empty($districts)) ? Cart_Location::districts($city, $districts) : '';?></span></p>

                <?php do_action('edit_user_profile_info', $customer);?>
            </section>
            <section class="ui-layout__section" style="display: none;" id="customer-edit">
                <form action="" autocomplete="off" id="customer-edit__form">
                    <div class="row">
                        <input type="hidden" name="id" value="<?php echo $customer->id;?>">
                        <?php
                        $Form = new FormBuilder();
                        $fields = customer_fields();
                        foreach ($fields as $key => $field) {
                            $value = '';
                            if(isset($customer->{$key})) $value = $customer->{$key};
                            if(isset($field['metadata']) && $field['metadata'] == true) {
                                $value = User::getMeta($customer->id, $key, true);
                            }
                            $Form->add($field['field'], $field['type'], $field, $value);
                        }
                        $Form->html(false);
                        ?>
                        <div class="clearfix"></div>
                        <div class="col-md-12">
                            <?php do_action('form_customer_edit', $customer);?>
                            <?php do_action('edit_user_profile', $customer);?>
                        </div>
                    </div>
                    <div class="ghtk-order-created__footer">
                        <div class="text-right"><button type="submit" class="btn btn-blue">Lưu</button></div>
                    </div>
                </form>
            </section>
    </section>
</div>


<style>
    .fancybox-slide > * { padding:0; }
    #customer-edit { max-width:500px; }
    #customer-edit h2 {
        background-color:#2C3E50; color:#fff; margin:0; padding:10px;
        font-size:18px;
    }
    #customer-edit form {
        padding:10px;
        overflow:hidden;
    }
    #customer-edit form .group{
        margin-bottom:10px;
    }
    .select2-container { z-index:99999; width: 100%!important; }
</style>

<script>
    $(function() {
        $.ajaxSetup({
            beforeSend: function(xhr, settings) {
                settings.data += '&csrf_test_name=' + encodeURIComponent(getCookie('csrf_cookie_name'));
            }
        });
        $('#js_customer_btn__edit').click(function () {
            $('#customer-edit').toggle();
            $('#customer-info').toggle();
            return false;
        });
        $('#customer-edit__form').submit(function(){
            let data = $(this).serializeJSON();
            data.action = 'Ajax_Admin_User_Action::saveProfile';
            $.post(ajax, data, function() {}, 'json').done(function( response ) {
                show_message(response.message, response.status);
                if(response.status === 'success') {}
            });
            return false;
        });

        let city = $('#city');
        let districts = $('#districts');
        city.select2(); districts.select2();
        city.each(function (index, value) {
            let data = {
                province_id : $(this).val(),
                district_id : '<?php echo User::getMeta($customer->id, 'districts', true);?>',
                action      : 'Cart_Ajax::loadDistricts'
            };
            $jqxhr   = $.post(ajax , data, function() {}, 'json');
            $jqxhr.done(function(response) {
                if(response.status === 'success') {
                    districts.html(response.data);
                }
            });
        });
        city.change(function() {
            let data = {
                province_id : $(this).val(),
                action: 'Cart_Ajax::loadDistricts'
            };

            $jqxhr   = $.post(ajax, data, function() {}, 'json');

            $jqxhr.done(function(response) {
                if(response.status === 'success') {
                    districts.html(response.data);
                }
            });
        });
    });
</script>
