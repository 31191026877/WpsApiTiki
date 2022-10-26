<div id="left-panel">
    <div class="list-elements" id="widget-local">
        <div class="heading">
            <a href="#" class="text-secondary">Widgets</a>
            <input data-builder-action="searchWidget" data-builder-on="keyup" type="text" class="form-control" placeholder="search..">
        </div>
        <div class="tree">
            <ul id="js_widget_list"></ul>
        </div>
    </div>
    <div class="drag-elements">
        <div class="heading">
            <!-- TAB NAVIGATION -->
            <ul class="nav nav-tabs" id="elements-tabs" role="tablist" style="overflow: inherit">
                <li class="nav-item">
                    <a class="nav-link active" id="widget-tab" href="#widget" role="tab" data-bs-toggle="tab" aria-controls="widget"><i class="fal fa-layer-group" data-bs-toggle="tooltip" data-bs-placement="top" title="Sidebar Layout"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-builder-action="showElement" href="#headerService"><i class="fad fa-border-top" data-bs-toggle="tooltip" data-bs-placement="top" title="Header"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-builder-action="showElement" href="#navigationService"><i class="fal fa-bars" data-bs-toggle="tooltip" data-bs-placement="top" title="Navigation"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-builder-action="showElement" href="#topBarService"><i class="fal fa-border-top" data-bs-toggle="tooltip" data-bs-placement="top" title="Top Bar"></i></a>
                </li>
            </ul>
            <!-- TAB CONTENT -->
            <div class="tab-content">
                <div class="tab-pane sections fade active show" id="widget">
                    <?php include_once 'builder-tab-widget.php';?>
                </div>
            </div>
        </div>
    </div>
</div>

<script id="js_widget_item_template" type="text/x-custom-template">
    <li id="menuItem_${id}" class="js_widget_item" style="display: list-item;" data-id="${id}" data-key="${widget_id}">
        <div class="widget_sidebar">
            <div class="handle"></div>
            <div class="widget_sidebar_header">
                <span class="text-left pull-left">${widget_name}</span>
                <div class="action pull-right">
                    <a href="#" class="icon-copy" data-builder-action="copyWidget"><i class="fal fa-clone"></i></a> &nbsp;&nbsp;
                    <a href="#" class="icon-delete" data-builder-action="deleteWidget"><i class="fal fa-trash-alt"></i></a> &nbsp;&nbsp;
                    <a href="#" class="icon-edit" data-builder-action="editWidget"><i class="fal fa-cog"></i></a> &nbsp;&nbsp;
                </div>
            </div>
            <div class="title">
                <h3 class="widget-name">${name}</h3>
                <p style="margin:0" class="widget-key">${widget_id}</p>
            </div>
        </div>
    </li>
</script>
<script id="js_element_item_template" type="text/x-custom-template">
    <div class="element_item" style="background-image: url('${image}'); background-repeat: no-repeat;" data-id="${id}" data-type="${type}" data-folder="${folder}">
        <a class="element_item__heading" href="#">${title}</a>
        <div class="element_item__action"> ${button} </div>
        <img src="${image}" loading="lazy">
    </div>
</script>