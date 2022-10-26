<?php
$shipping = shipping_gateways();
?>
<div class="order_shipping_list">
    <div class="table">
        <div class="row">
            <div class="col-h col col-20">Đối tác vận chuyển</div>
            <div class="col-h col col-35">Dịch vụ</div>
            <div class="col-h col col-20">Thời gian dự kiến</div>
            <div class="col-h col col-25">Phí dự kiến</div>
        </div>
        <?php
        foreach ($shipping as $key => $ship) {
            if(method_exists($ship['class'],'listService')) {
                $itemFunction = $ship['class'].'::listService';
                $itemList = $itemFunction($ship, $order);
                ?>
                <div class="row">
                    <div class="col col-20 border-right col-img">
                        <?php Template::img($ship['icon']);?>
                    </div>
                    <div class="col col-80 padding-0">
                        <?php foreach ($itemList as $itemId => $item) {?>
                        <div class="row">
                            <div class="col col-44">
                                <label for="shipping_type_<?php echo $key;?>_<?php echo $itemId;?>">
                                    <input type="radio" value="<?php echo $key.'__'.$itemId;?>" name="shipping_type" id="shipping_type_<?php echo $key;?>_<?php echo $itemId;?>"> <?php echo $item['label'];?>
                                </label>
                            </div>
                            <div class="col col-25"><?php echo $item['expected_delivery_time'];?></div>
                            <div class="col col-25"><?php echo $item['fee'];?> đ</div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <?php
            }
        } ?>
    </div>
</div>

<style>
    .order_shipping_list {padding:0 10px; }
    .order_shipping_list .table {
        overflow: hidden;border: 1px solid #ccc;
    }
    .order_shipping_list .table label {
        cursor: pointer; margin-bottom: 0;
    }
    .order_shipping_list .table .row {
        margin-left: 0;  margin-right: 0;
        border-bottom: 1px solid #ccc;
    }
    .order_shipping_list .table .padding-0{
        padding:0!important;
    }
    .order_shipping_list .table .border-right{
        border-right: 1px solid #ccc;
    }
    .order_shipping_list .table .col {
        float: left; padding:10px;
    }
    .order_shipping_list .table .col-img {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }
    .order_shipping_list .table .col-img img {
        width: 50px;
    }
    .order_shipping_list .table>.col {
        border: 1px solid #ccc;
    }
    .order_shipping_list .table .col-h {
        background-color: #F4F6F8; color: #000; font-weight: bold; font-size: 12px;
    }

    .order_shipping_list .table .col-20 { width: 20%; }
    .order_shipping_list .table .col-25 { width: 25%; }
    .order_shipping_list .table .col-30 { width: 30%; }
    .order_shipping_list .table .col-35 { width: 35%; }
    .order_shipping_list .table .col-44 { width: 44%; }
    .order_shipping_list .table .col-70 { width: 70%; }
    .order_shipping_list .table .col-80 { width: 80%; }
</style>
