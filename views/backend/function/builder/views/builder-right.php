<div id="right-panel">
    <div class="component-properties">
        <!-- TAB NAVIGATION -->
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item"><a class="nav-link" href="#widget_editor" role="tab" data-bs-toggle="tab"><i class="fal fa-th"></i> Widget Edit</a></li>
            <li class="nav-item"><a class="nav-link active" href="#theme" role="tab" data-bs-toggle="tab"><i class="fal fa-pencil-paintbrush"></i> Style</a></li>
            <li class="nav-item"><a class="nav-link" href="#fonts" role="tab" data-bs-toggle="tab"><i class="fal fa-font-case"></i> Fonts</a></li>
        </ul>
        <!-- TAB CONTENT -->
        <div class="tab-content">
            <div class="tab-pane fade" id="widget_editor">
                <?php echo Admin::loading();?>
                <div class="js_widget_editor_content"></div>
            </div>
            <div class="active tab-pane fade show" id="theme">
                <div class="panel-group scrollbar" id="themeOption" role="tablist" aria-multiselectable="true">
                    <?php include 'builder-options.php';?>
                </div>
            </div>
            <div class="tab-pane fade scrollbar" id="fonts">
                <?php include 'builder-fonts.php';?>
            </div>
        </div>

    </div>
</div>