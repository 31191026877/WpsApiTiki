<?php
    $products = Product::gets(Qr::set('trash',0)->select('id', 'title', 'price', 'price_sale', 'order', 'image')->limit(20));
?>
<div class="box" id="order_product_items">
    <div class="box-content">
        <section class="ui-layout__section">
            <header class="ui-layout__title"><h2>Thông tin sản phẩm</h2></header>
        </section>
        
        <!-- danh sách sản phẩm -->
        <div class="order_product_list">
        <?php if(isset($order) && have_posts($order)) { ?>
            <?php foreach ($order->items as $product_item) {

                $product_item->variation = Order::getItemMeta( $product_item->id,'variable', true);

                $input_id = $product_item->product_id.'_'.$product_item->variation;

                $pr = '<div class="order_product__item order_product__item_'.$input_id.'">';
                //input
                $pr .= '<div class="order_product__item-input">';
                $pr .= '<input name="line_items['.$input_id.'][productID]"      class="line_item_id" value="'.$product_item->product_id.'" type="hidden">';
                $pr .= '<input name="line_items['.$input_id.'][productName]"    class="line_item_name" value="'.$product_item->title.'" type="hidden">';
                $pr .= '<input name="line_items['.$input_id.'][productPrice]"   class="line_item_price" value="'.$product_item->price.'" type="hidden">';
                $pr .= '<input name="line_items['.$input_id.'][productQuantity]"   class="line_item_quantity" value="'.$product_item->quantity.'" type="hidden">';
                $pr .= '<input name="line_items['.$input_id.'][productVariation]"   class="line_item_variation" value="'.$product_item->variation.'" type="hidden">';
                $pr .= '</div>';
                //img
                $pr .= '<div class="order_product__item-img -left -item"><img src="'.get_img_link($product_item->image).'" class="img-responsive" alt="Image"></div>';
                //title
                $pr .= '<div class="order_product__item-name -left -item"><span> '.$product_item->title.'</span></div>';
                //price
                $pr .= '<div class="order_product__item-price -left -item"><span>'.$product_item->price.'₫</span> </div>';
                //input quantity
                $pr .= '<div class="order_product__item-quantity -left -item"><input value="'.$product_item->quantity.'" style="display:inline-block; text-align: center;min-width: 5em;width:0" id="line_items_0_.quantity" class="form-control" min="1" type="number"></div>';
                //input delete
                $pr .= '<div class="order_product__item-delete -right -item"><a><i class="fal fa-trash"></i></a></div>';
                //total
                $pr .= '<div class="order_product__item-total -right -item"><span>'.$product_item->subtotal.'</span>₫ </div>';
                $pr .= '</div>';

                echo $pr;
            }
            ?>
        <?php } ?>
        
        </div>

        <div class="clearfix"> </div>
        <!-- tìm kiếm sản phẩm -->
        <section class="ui-layout__section">
            <div class="col-md-12" id="box_order_search_product" style="padding:0;">
                <label for="">Chọn sản phẩm</label>
                <div class="group input-popover-group" data-name="order_search_product" id="order_search_product" data-module="order_search_product" data-key-type="product">
                    
                    <div class="collections hidden"><ul class="collection-list"></ul></div>

                    <input type="text" class="form-control input-popover-search" placeholder="Tìm kiếm sản phẩm">

                    <div class="popover-content">
                        <div class="popover__tooltip"></div>
                        <div class="popover__scroll">
                            <ul class="popover__ul" style="display: block;">
                                <?php foreach ($products as $key => $item) { echo popover_order_product_template_item($item); } ?>
                            </ul>
                            <div class="popover__loading text-center" style="display: none;">
                                Đang tải…
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            <div class="clearfix"> </div>
        </section>
        <div class="clearfix"> </div>
        
    </div>
</div>