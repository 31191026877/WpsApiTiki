<div class="row">
    <div class="col-md-3">
        <div class="ui-title-bar__group" style="padding-bottom:5px;">
            <h3 class="ui-title-bar__title" style="font-size:20px;">Lý do hủy đơn</h3>
            <p style="margin-top: 10px; margin-left: 1px; color: #8c8c8c">Quản lý các lý do khách hàng hủy đơn hàng</p>
        </div>
    </div>
    <div class="col-md-9">
        <div class="box">
            <div class="box-content" style="padding:10px;">
                <div class="row">
                    <table class="table table-bordered" id="order_cancelled_reason_item">
                        <thead>
                        <tr>
                            <th>Lý do</th>
                            <th>#</th>
                        </tr>
                        </thead>
                        <tbody class="accounts ui-sortable">
                        <?php if(!empty($order_cancelled_reason)) {?>
                            <?php foreach ($order_cancelled_reason as $key => $reason): ?>
                                <tr class="account">
                                    <td><textarea class="form-control" name="reason[<?php echo $key;?>]" class="form-control"><?php echo $reason;?></textarea></td>
                                    <td style="width:100px;" class="sort">
                                        <button class="btn-delete btn-icon btn-red">Xóa</button>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        <?php } ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th colspan="7"><a href="#" class="add btn-white btn">+ Thêm Item</a></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
<hr />

<script type="text/javascript">
    $(function() {
        var list_question = $('#order_cancelled_reason_item');
        list_question.on( 'click', 'a.add', function(){
            var size = list_question.find('tbody .account').length;
            $('<tr class="account">\
                <td><textarea class="form-control" name="reason[' + size + ']" class="form-control"></textarea></td>\
                <td class="sort" style="width:100px;">\
                    <button class="btn-delete btn-icon btn-red">Xóa</button>\
                </td>\
            </tr>').appendTo('#order_cancelled_reason_item tbody');
            return false;
        });
        list_question.on( 'click', 'button.btn-delete', function(){
            $(this).closest('tr.account').remove();
            return false;
        });
    });
</script>