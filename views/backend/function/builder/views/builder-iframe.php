<div id="canvas">
    <div id="iframe-wrapper">
        <div id="iframe-layer">
            <div class="loading-message">
                <div class="animation-container">
                    <div class="dot dot-1"></div>
                    <div class="dot dot-2"></div>
                    <div class="dot dot-3"></div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1">
                    <defs>
                        <filter id="goo">
                            <feGaussianBlur in="SourceGraphic" stdDeviation="10" result="blur"></feGaussianBlur>
                            <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 21 -7"></feColorMatrix>
                        </filter>
                    </defs>
                </svg>
                <!-- https://codepen.io/Izumenko/pen/MpWyXK -->
            </div>
            <div id="highlight-box">
                <div id="highlight-name" style=""></div>
                <div id="section-actions" class="outside" style=""><a id="js_add_widget" href="" class="highlight-btn-add" title="Add element"><i class="fal fa-plus"></i></a></div>
            </div>
            <div id="select-box" class="">
                <div id="select-actions" style="">
                    <a id="js_delete_widget" href="" title="Remove element"><i class="fal fa-trash"></i></a>
                </div>
            </div>
            <div id="widget-box-add" class="drag-elements">
                <ul class="nav nav-tabs">
                    <li class="nav-item active">
                        <a class="nav-link" href="#"><i class="fal fa-2x fa-cube"></i></a>
                    </li>
                </ul>
                <button class="btn-close" id="js_widget_iframe__close"><i class="fal fa-times"></i></button>
                <div class="tab-content">
                    <div class="search">
                        <input class="form-control component-search" placeholder="Search components" type="text" data-vvveb-action="addBoxComponentSearch" data-vvveb-on="keyup">
                    </div>
                    <div class="tab-content-box">
                        <ul id="js_iframe_widget_list" class="iframe_widget_list"></ul>
                    </div>
                </div>
            </div>
        </div>
        <iframe src="" id="iframe1"></iframe>
    </div>
</div>
<script id="js_iframe_widget_item_template" type="text/x-custom-template">
    <li class="iframe_widget" data-id="${id}" data-key="${widget_id}">
        <div class="iframe_widget_item">
            <div class="action">
                <a href="#" class="icon-edit" data-builder-action="addWidget"><i class="fal fa-plus"></i></a>
            </div>
            <div class="title">
                <h3 class="widget-name">${name}</h3>
                <p style="margin:0" class="widget-key">${widget_id}</p>
            </div>
        </div>
    </li>
</script>