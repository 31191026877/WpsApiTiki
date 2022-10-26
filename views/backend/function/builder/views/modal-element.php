<div class="modal fade" id="js_widget_element_modal">
    <div class="modal-dialog modal-lg" style="--bs-modal-width:1000px">
        <div class="modal-content">
            <div class="header"><h2>Widget Element</h2></div>
            <div class="modal-body">
                <?php echo Admin::loading();?>
                <div class="box-content scrollbar">
                    <div class="js_element_box" style="display: none" id="widgetService"></div>
                    <div class="element_box js_element_box" style="display: none" id="headerService"></div>
                    <div class="element_box js_element_box" style="display: none" id="navigationService"></div>
                    <div class="element_box js_element_box" style="display: none" id="topBarService"></div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-icon btn btn-white" data-bs-dismiss="modal"> <i class="fal fa-times"></i> Đóng</button>
            </div>
        </div>
    </div>
</div>

<script id="js_widget_service_item_template" type="text/x-custom-template">
    <div class="element_item widget_service_item" style="background-image: url('${image}'); background-repeat: no-repeat;" data-id="${id}">
        <a class="element_item__author" href="#">${author}</a>
        <a class="element_item__heading name" href="#">${title}</a>
        <a class="element_item__btn add-section-btn" href="#" data-url="${id}" title="Add section" data-builder-action="downloadWidget"><i class="fal fa-cloud-download"></i></a>
        <a class="element_item__btn status" href="#">Đang download</a>
        <img src="${image}" alt="">
    </div>
</script>