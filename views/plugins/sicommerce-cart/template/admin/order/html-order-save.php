<?php
    $products = Product::gets(Qr::set()->select('id', 'title', 'price', 'price_sale', 'order', 'image')->limit(20));
	$users = User::gets(Qr::set()->select('id', 'firstname', 'lastname', 'username', 'email', 'phone')->limit(20));
?>
<form name="order" action="" method="post" id="order_save__form">
	<?php echo form_open(); ?>
	<div class="ui-layout order-header"><h1>Tạo đơn hàng</h1></div>
	<div class="ui-layout order-sections">
        <div class="row">
            <div class="col-md-8">
                <?php do_action('order_save_sections_primary', (isset($order)) ? $order : [] ); ?>
                <div class="box" id="order_amount"></div>
            </div>
            <div class="col-md-4">
                <?php do_action('order_save_sections_secondary', (isset($order)) ? $order : [] ); ?>
            </div>
        </div>
	</div>
</form>

<style>
    .input-popover-group, .page-content .box, .page-content .box .box-content {
        overflow:inherit;
    }

    .popover__ul .option  { cursor: pointer; }

    /** search product template **/
    #box_order_search_product .item-pr {
        overflow:hidden;
        -webkit-box-align: center;
        -webkit-align-items: center;
        -ms-flex-align: center;
        align-items: center;
        padding:5px;
    }
    #box_order_search_product .item-pr .item-pr__img { float:left; }
    #box_order_search_product .item-pr .item-pr__img img { width:30px; height:30px; }
    #box_order_search_product .item-pr .item-pr__title, .item-pr .item-pr__price {
        float:left;
        -webkit-box-flex: 1; -webkit-flex: 1 1 0%; -ms-flex: 1 1 0%; flex: 1 1 0%;
        padding: 7px;
        max-width: 100%; min-width: 0;
        color: #31373d;
    }
    #box_order_search_product .item-pr .item-pr__price {  float:right; }

    /** list product template **/
    .order_product_list { }
    .order_product_list .order_product__item {
        overflow:hidden;
        -webkit-box-align: center;  -webkit-align-items: center; -ms-flex-align: center; align-items: center;
        width:100%;
        padding: 10px 20px;
        border-bottom: 1px solid #eee;
    }
    .order_product_list .order_product__item .order_product__item-img { float:left; }
    .order_product_list .order_product__item .order_product__item-img img { width:30px; height:30px; }

	.order_product_list .order_product__item .-left { float:left; }
	.order_product_list .order_product__item .-right { float:right; }

    .order_product_list .order_product__item .-item {
        padding-left: 5px;
        padding-right: 5px;
        -webkit-box-flex: 1;
        -webkit-flex: 1 1 0%;
        -ms-flex: 1 1 0%;
        flex: 1 1 0%;
        padding: 7px;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
        max-width: 100%;
        min-width: 0;
        color: #31373d;
    }

	.order_product_list .order_product__item .order_product__item-name { width:300px; padding-right:15px;}
	.order_product_list .order_product__item .order_product__item-price { width:100px;}


    /** search user template **/
	.item-us {
        overflow:hidden;
        -webkit-box-align: center;
        -webkit-align-items: center;
        -ms-flex-align: center;
        align-items: center;
        padding:5px;
    }
    .item-us .item-us__img { float:left; }
    .item-us .item-us__img img { width:30px; height:30px; }
    .item-us .item-us__title {
        float:left;
		width:calc(100% - 30px);
        padding-left: 5px;
        padding-right: 5px;
        -webkit-box-flex: 1;
        -webkit-flex: 1 1 0%;
        -ms-flex: 1 1 0%;
        flex: 1 1 0%;
        padding: 7px;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
        max-width: 100%;
        min-width: 0;
        color: #31373d;
    }
</style>


<style type="text/css">
    section.ui-layout__section {
        padding:10px 20px;
    }

    section.ui-layout__section+section.ui-layout__section {
	    border-top: 1px solid #dfe4e8;
    }
    section.ui-layout__section~section.ui-layout__section {
        border-top: 1px solid #ebeef0;
    }

    section.ui-layout__section header.ui-layout__title h2 {
		font-size: 18px; font-weight: 600; line-height: 2.4rem; margin: 0;
		-webkit-box-flex: 1;
	    -webkit-flex: 1 1 auto;
	    -ms-flex: 1 1 auto;
	    flex: 1 1 auto;
	    min-width: 0;
    	max-width: 100%;
        display:inline-block;
	}

    section.ui-layout__section h3 {
        font-size: 13px;
        font-weight: 600;
        line-height: 1.6rem;
        text-transform: uppercase;
        margin-top: 0;
    }

    #order_amount .wc-order-data-row {
	    border-bottom: 1px solid #dfdfdf;
	    padding: 1.5em 2em;
	    background: #f8f8f8;
	    line-height: 2em;
	    text-align: right;
	}
	#order_amount .wc-order-data-row::after, #order_amount .wc-order-data-row::before {
	    content: ' ';
	    display: table;
	}

	#order_amount .wc-order-data-row  .wc-order-totals {
	    float: right;
	    width: 50%;
	    margin: 0;
	    padding: 0;
	    text-align: right;
	}
	
	#order_amount .wc-order-data-row  .wc-order-totals .label{
	    color:#333;
	    font-size: 15px;
	}
</style>