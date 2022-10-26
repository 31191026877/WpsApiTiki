<div class="ui-layout customer-sections">
    <div class="row">
        <div class="col-md-8">
            <?php
            /**
             * customer_detail_sections_primary
             */
            echo do_action('customer_detail_sections_primary', $customer );
            ?>
        </div>
        <div class="col-md-4">
            <?php
            /**
             * customer_detail_sections_secondary
             */
            echo do_action('customer_detail_sections_secondary', $customer );
            ?>
        </div>
    </div>
</div>

<style type="text/css">

    section.ui-layout__section {
        padding:20px;
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

    .customer-profile { padding:20px; overflow:hidden; }
    
    .customer-profile .customer-profile__avatar {
        float:left; width:60px; height:50px; border-radius:50%; overflow:hidden; padding-right:10px;
    }
    .customer-profile .customer-profile__avatar img{
        width:50px; height:50px; border-radius:50%;
    }
    .customer-profile .customer-profile__name {
        float:left; width:calc(100% - 60px);
    }
    .customer-profile .customer-profile__name h3 {
        font-size: 15px;
        font-weight: 600;
        line-height: 2.4rem;
        margin:0;
    }
    .customer-profile .customer-profile__name p {
        color: #637381;
    }

    .customer-profile .customer-profile-action {
        position: absolute; right: 20px; top:20px;
    }

    .customer-order-statistical {
        display: -webkit-box;
        display: -moz-box;
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;
        
        -webkit-flex-flow: row wrap;
        justify-content: space-around;
    }
    .customer-order-statistical .type--centered {
        -webkit-box-flex: 0;
        -webkit-flex: 0 0 auto;
        -ms-flex: 0 0 auto;
        flex: 0 0 auto;
        margin-top: 1.6rem;
        margin-left: 1.6rem;
        text-align: center;
    }

    .customer-order-statistical .type--centered a {
        font-size: 16px; font-weight:600;
    }

    .customer-order-list  .order-item {
        overflow:hidden;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        margin-bottom:10px;
    }
    .customer-order-list  .order-item > [class*="order-item__"] {
        -ms-flex-preferred-size: 0;
        flex-basis: 0;
        -ms-flex-positive: 1;
        flex-grow: 1;
    }
    .customer-order-list  .order-item .order-item__right {
        color: #637381;
    }
</style>