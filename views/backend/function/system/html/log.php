<div class="col-md-12">
    <div class="timeline-container_new">
        <div class="timeline-new__wrapper__content--body">
            <?php foreach ($logActive as $date => $listLog) { ?>
                <div class="timeline-container_new--position">
                    <div class="timeline-event-content_new">
                        <div class="timeline-item-new--border--padding">
                            <div class="timeline-new__date text-uppercase"><?php echo $date;?></div>
                        </div>
                    </div>
                </div>
                <?php foreach ($listLog as $key => $log) { ?>
                    <div class="timeline-container_new--position">
                        <div class="timeline-event-contentnew__icon">
                            <div class="icon icon-<?php echo $log['action'];?>"><?php echo AdminActiveLog::icon($log['action']);?></div>
                        </div>
                        <div class="timeline-item-new--border--padding">
                            <div class="timeline-new__infomation">
                                <div><span class="timeline-new__infomation__name"><?php echo $log['fullname'];?></span>
                                    <span class="timeline-new__infomation__time">- <?php echo date('H:i a', $log['time']);?></span></div>
                                <div class="timeline-new__infomation__message"><span><?php echo $log['fullname'];?> đã <?php echo $log['message'];?></span></div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</div>
