<div class="box" id="customer_content">
	<div class="box-content">
        <div class="customer-profile">
            <div class="customer-profile__avatar">
                <?php Template::img('https://yt3.ggpht.com/-tcGz0UiyfkE/AAAAAAAAAAI/AAAAAAAAAAA/XkN5ucCEyBg/w800-h800/photo.jpg');?>
            </div>
            <div class="customer-profile__name">
                <h3><?php echo $customer->firstname.' '.$customer->lastname;?></h3>
                <p><?php echo User::getMeta($customer->id, 'address', true);?></p>
            </div>
            <?php if($customer->username == '') { ?>
            <div class="customer-profile-action">
                <a class="btn btn-default btn-customer-active" data-fancybox data-src="#customer-active-account" href="javascript:;"> <i class="fal fa-user-check"></i>  Kich hoạt tài khoản</a>
            </div>
            <?php } ?>
        </div>

        <section class="ui-layout__section">
            <div class="customer-order-statistical">

                <div class="type--centered">
                    <p class="type--subdued"> Đơn hàng mới nhất </p>
                    <?php $order_id = (int)User::getMeta($customer->id, 'order_recent', true) - 1000;?>
                    <a class="" href="<?php echo admin_url('plugins?page=order&view=detail&id='.$order_id);?>"> #<?php echo User::getMeta($customer->id, 'order_recent', true);?>  </a>
                </div>
                
                <div class="type--centered">
                    <p class="type--subdued"> Tổng chi tiêu </p>
                    <h3 class=""> <?php echo (!empty($customer->order_total)) ? number_format($customer->order_total) : 0;?> ₫ </h3>
                </div>

                <div class="type--centered">
                    <p class="type--subdued"> Tổng đơn hàng </p>
                    <h3 class=""> <?php echo (!empty($customer->order_count)) ? number_format($customer->order_count) : 0;?> đơn hàng </h3>
                </div>

            </div>
        </section>
        
	</div>
</div>

<div style="display: none;" id="customer-active-account">
    <h2>Kích hoạt tài khoản</h2>
    <form action="" autocomplete="off" id="customer-active-account__form">

        <input type="hidden" name="customer_id" value="<?php echo $customer->id;?>">

        <div class="col-md-12" id="box_username">
            <label for="username" class="control-label">Tài khoản đăng nhập</label>
            <div class="group">
                <input type="text" name="username" value="" placeholder="Tên đăng nhập của tài khoản khách hàng." class="form-control " required>
            </div>
        </div>

        <div class="form-group col-md-6">
            <label for="">Mật khẩu</label>
            <input name="password" type="password" value="" class="form-control" placeholder="Nhập mật khẩu" required>
        </div>

        <div class="form-group col-md-6">
            <label for="">Nhập lại mật khẩu</label>
            <input name="re_password" type="password" value="" class="form-control" placeholder="Nhập lại mật khẩu" required>
        </div>

        <?php do_action('form_customer_active_account', $customer);?>

        <div class="ghtk-order-created__footer">
            <div class="text-right"><button type="submit" class="btn btn-blue">Lưu</button></div>
        </div>
    </form>
</div>
<style>
    #customer-active-account { max-width:500px; }
    #customer-active-account h2 {
        background-color:#2C3E50; color:#fff; margin:0; padding:10px;
        font-size:18px;
    }
    #customer-active-account form {
        padding:10px;
        overflow:hidden;
    }
    #customer-active-account form .group{
        margin-bottom:10px;
    }
</style>
<script>
    $(function() {
        $('#customer-active-account__form').submit(function(){
            
            var data = $(this).serializeJSON();

            data.action = 'admin_ajax_customer_active_account';

            $jqxhr   = $.post(ajax, data, function() {}, 'json');

            $jqxhr.done(function( response ) {

                show_message(response.message, response.status);

                if(response.status === 'success') {
                    $.fancybox.close();
                    $('.btn-customer-active').remove();
                }
            });
            return false;
        });
    });
</script>