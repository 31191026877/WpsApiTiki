<?php
Class Admin_Cart_Print {
    static public function addButtonOrderList() {
        echo '<button class="btn-default btn js_admin_order_item__print"><i class="fal fa-print"></i> In đơn hàng</button>';
    }
    static public function addButtonOrderItem($item) {
        echo '<button class="btn-default btn js_admin_order_item__print" data-id="'.$item->id.'"><i class="fal fa-print"></i></button>';
    }
    static public function addButtonOrderDetail($order) {
        ?>
        <button type="button" class="btn btn-default" onclick="PrintElem('js_admin_order_content_print')"><i class="fal fa-print"></i> In đơn hàng</button>
        <div class="hidden" id="js_admin_order_content_print"><?php cart_template('admin/order/html-order-print', array('order' => $order)); ?></div>
        <script>
            function PrintElem(elem) {
                let mywindow = window.open('', 'PRINT');
                mywindow.document.write('<html><head><title>' + document.title  + '</title>');
                mywindow.document.write('</head><body >');
                mywindow.document.write(document.getElementById(elem).innerHTML);
                mywindow.document.write('</body></html>');
                mywindow.document.close(); // necessary for IE >= 10
                mywindow.focus(); // necessary for IE >= 10*/
                mywindow.print();
                mywindow.close();
                return true;
            }
        </script>
        <?php
    }
    static public function addElementOrderPrint() {
        if(Template::getPage('plugins_index') && Request::Get('page') == 'order') {
            ?>
            <div class="hidden" id="js_admin_order_content_print"></div>
            <script>
                $(function () {
                    $(document).on('click', '.js_admin_order_item__print', function () {
                        let printId = $(this).attr('data-id');
                        if(!isset(printId)) {
                            printId = []; let i = 0;
                            $('.select:checked').each(function () { printId[i++] = $(this).val(); });
                        }
                        if(typeof printId == 'undefined' || printId.length === 0) {
                            show_message('Bạn chưa chọn đơn hàng nào', 'error');
                            return false;
                        }
                        let data = {
                            'action': 'Admin_Order_Ajax::prints',
                            'id' : printId
                        };
                        $jqxhr = $.post(ajax, data, function () {}, 'json').done(function (response) {
                            if (response.status === 'success') {
                                $('#js_admin_order_content_print').html(response.html);
                                let mywindow = window.open('', 'PRINT');
                                mywindow.document.write('<html><head><title>' + document.title  + '</title>');
                                mywindow.document.write('</head><body >');
                                mywindow.document.write(document.getElementById('js_admin_order_content_print').innerHTML);
                                mywindow.document.write('</body></html>');
                                mywindow.document.close(); // necessary for IE >= 10
                                mywindow.focus(); // necessary for IE >= 10*/
                                setTimeout(function (){ 
                                    mywindow.print();
                                    mywindow.close();                               
                                }, 500);

                            }
                            else {
                                show_message(response.message, response.status);
                            }
                        });
                        $jqxhr.fail(function (data) {});
                        $jqxhr.always(function (data) { });
                        return false;
                    });
                })
            </script>
            <?php
        }
    }
}

add_action( 'admin_order_action_bar_heading', 'Admin_Cart_Print::addButtonOrderList');
add_action('admin_order_table_column_action', 'Admin_Cart_Print::addButtonOrderItem', 10);
add_action('order_detail_header_action', 'Admin_Cart_Print::addButtonOrderDetail');
add_action('admin_footer', 'Admin_Cart_Print::addElementOrderPrint');
