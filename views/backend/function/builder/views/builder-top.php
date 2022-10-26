<div id="top-panel">
    <a href="<?php echo Url::admin();?>"><img src="<?php echo Cms::logo();?>" alt="" class="pull-left" id="logo"></a>
    <div class="btn-group " role="group">
        <button class="btn btn-light" title="Toggle file manager" id="toggle-file-manager-btn" data-builder-action="toggleWidgetLocal" data-bs-toggle="button" aria-pressed="false">
            <img src="http://www.vvveb.com/vvvebjs/libs/builder/icons/file-manager-layout.svg" width="20px" height="20px">
        </button>
        <button class="btn btn-light" title="Toggle left column" id="toggle-left-column-btn" data-builder-action="toggleLeftColumn" data-bs-toggle="button" aria-pressed="false">
            <img src="http://www.vvveb.com/vvvebjs/libs/builder/icons/left-column-layout.svg" width="20px" height="20px">
        </button>
        <button class="btn btn-light" title="Toggle right column" id="toggle-right-column-btn" data-builder-action="toggleRightColumn" data-bs-toggle="button" aria-pressed="false">
            <img src="http://www.vvveb.com/vvvebjs/libs/builder/icons/right-column-layout.svg" width="20px" height="20px">
        </button>
    </div>
    <div class="btn-group me-3" role="group">
        <button class="btn btn-light" title="Fullscreen (F11)" id="fullscreen-btn" data-bs-toggle="button" aria-pressed="false" data-builder-action="fullscreen"><i class="fal fa-expand-arrows"></i></button>
        <button class="btn btn-light" title="ReLoad (F5)" id="reload-btn" data-bs-toggle="button" aria-pressed="false" data-builder-action="reload"><i class="fal fa-redo"></i></button>
        <a class="btn btn-light" href="<?php echo Url::base();?>" target="_blank"><i class="fal fa-home"></i></a>
    </div>
    <div class="btn-group me-3 responsive-btns" role="group">
        <button id="mobile-view" data-view="mobile" class="btn btn-light" title="Mobile view" data-builder-action="viewport">
            <i class="fal fa-mobile-android"></i>
        </button>

        <button id="tablet-view" data-view="tablet" class="btn btn-light" title="Tablet view" data-builder-action="viewport">
            <i class="fal fa-tablet-android"></i>
        </button>

        <button id="desktop-view" data-view="" class="btn btn-light" title="Desktop view" data-builder-action="viewport">
            <i class="fal fa-desktop"></i>
        </button>
    </div>
    <div class="btn-group me-3" role="group">
        <button class="btn btn-blue btn-icon" title="Export (Ctrl + E)" id="save-btn" data-builder-action="save">
            <?php echo Admin::icon('edit');?> <span>Save page</span>
        </button>
    </div>
</div>