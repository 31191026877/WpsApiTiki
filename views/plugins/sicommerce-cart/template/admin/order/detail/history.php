<div class="box" id="order_history">
	<div class="box-content">
		<header class="order__title"> <div class="order__title_wrap"> <h2>LỊCH SỬ</h2> </div> </header>
		<div class="order_cart__section">
            <div class="timeline-container_new">
                <div class="timeline-new__wrapper__content--body">
                    <?php foreach ($histories as $key => $his) { ?>
                    <div class="timeline-container_new--position">
						<div class="timeline-event-contentnew__icon">
							<div class="icon icon-login"><i class="fal fa-sign-in"></i></div>
						</div>
						<div class="timeline-item-new--border--padding">
							<div class="timeline-new__infomation">
								<div>
                                    <span class="timeline-new__infomation__name"></span>
                                    <span class="timeline-new__infomation__time"><?php echo $his->created;?></span>
                                </div>
								<div class="timeline-new__infomation__message"><span><?php echo base64_decode($his->message);?></span></div>
							</div>
						</div>
					</div>
                    <?php } ?>
                </div>
            </div>
        
        </div>
	</div>
</div>

<style>
    .timeline-container_new {
    border: 1px solid #ebeef0;
    border-radius: 3px;
    background-color: #fff;
}
.timeline-container_new .timeline-container_new--position {
    position: relative;
    margin-left: 30px;
    word-break: break-all;
}
.timeline-container_new .timeline-container_new--position:before {
    content: "";
    width: 12px;
    height: 12px;
    border: 3px solid #ebeef0;
    border-radius: 50%;
    position: absolute;
    top: 17px;
    left: -3.9px;
    background-color: #ebeef0;
    z-index: 2;
}
.timeline-container_new .timeline-container_new--position .timeline-event-contentnew__icon {
    position: absolute;
    top: 21px;
    left: -11px;
    z-index: 2;
}
.timeline-container_new .timeline-container_new--position .timeline-event-contentnew__icon .icon {
    position: relative;
    display: inline-block;
    vertical-align: middle;
    background-color: #21469b;
    color: #fff;
    width: 30px;
    height: 30px;
    line-height: 30px;
    text-align: center;
    border-radius: 50%;
}
.timeline-container_new .timeline-item-new--border--padding {
    border-left: 3px solid #ebeef0;
    padding: 15px 15px 15px 0;
}
.timeline-container_new .timeline-container_new--position .timeline-new__infomation {
    margin-left: 30px;
}
.timeline-container_new .timeline-container_new--position .timeline-new__infomation__name {
    font-weight: 600;
    font-size: 14px;
    color: #212121;
}
.timeline-container_new .timeline-container_new--position .timeline-new__infomation__time {
    color: #6c798f;
    font-size: 14px;
    font-weight: 400;
}
.timeline-container_new .timeline-container_new--position .timeline-new__infomation__message {
    margin-top: 5px;
}
</style>