<div class="theme-setting-sidebar">

    <a class="theme-setting-sidebar-toggle" href="#"><i class="fad fa-cogs"></i></a>

    <a class="theme-setting-sidebar-close" href="#"><i class="fal fa-times"></i></a>

    <form class="theme-setting-sidebar__form" id="theme-setting-sidebar__form">
        <div class="theme-setting-sidebar-content">
            <h4 class="text-uppercase mb-0">Theme Customizer</h4>
            <div class="option-cms row">
                <div class="col-md-4">
                    <button type="button" class="js_theme_setting_sidebar__clear btn btn-effect-default btn-blue" data-type="css">Xóa File Minify css</button>
                </div>
                <div class="col-md-4">
                    <button type="button" class="js_theme_setting_sidebar__clear btn btn-effect-default btn-blue" data-type="js">Xóa File Minify js</button>
                </div>
                <div class="col-md-4">
                    <button type="button" class="js_theme_setting_sidebar__clear btn btn-effect-default btn-blue" data-type="cache">Xóa File cache</button>
                </div>
            </div>
            <hr/>
            <div class="theme-setting-sidebar__template" style="overflow:hidden;">
                <?php do_action('setting_sidebar_template'); ?>
            </div>
            <div class="theme-setting-sidebar__hook" style="overflow:hidden;">
                <?php
                include_once 'hook/hook.php';
                ?>
            </div>
        </div>
        <div class="theme-setting-sidebar-button">
            <button id="theme-setting-sidebar_btn__submit" form="theme-setting-sidebar__form" class="btn btn-effect-default btn-red btn-block"><?php echo Admin::icon('save');?>  SAVE</button>
        </div>
    </form>
</div>
<style>
    .theme-setting-sidebar {
        width: 550px;
        right: -550px;
        padding: 0;
        margin-bottom:30px;
        background-color: #FFF;
        z-index: 9999;
        position: fixed;
        top: 0;
        bottom: 0;
        height: 100vh;
        -webkit-transition: right .4s cubic-bezier(.05,.74,.2,.99);
        -o-transition: right .4s cubic-bezier(.05,.74,.2,.99);
        -moz-transition: right .4s cubic-bezier(.05,.74,.2,.99);
        transition: right .4s cubic-bezier(.05,.74,.2,.99);
        -webkit-backface-visibility: hidden;
        -moz-backface-visibility: hidden;
        backface-visibility: hidden;
        box-shadow: 0 0 10px 1px rgba(68,102,242,.05);
        font-family:-apple-system, BlinkMacSystemFont, "San Francisco", "Segoe UI", Roboto, "Helvetica Neue", sans-serif;
    }
    .theme-setting-sidebar .theme-setting-sidebar-toggle {
        background: #fff;
        color: #143FEF;
        box-shadow: 0 0 15px 3px rgba(176,185,189,.3);
        display: block;
        border-radius: 8px 0 0 8px;
        position: absolute;
        top: 50%;
        width: 50px;
        height: 50px;
        left: -50px;
        font-size: 20px;
        text-align: center;
        line-height: 50px;
        cursor: pointer;
        transition: all .3s ease;
    }
    .theme-setting-sidebar.open .theme-setting-sidebar-toggle {
        background: #143FEF;
        color: #fff;
    }
    .theme-setting-sidebar.open {
        right: 0;
    }
    .theme-setting-sidebar-close {
        position: absolute;
        right: 30px;
        top: 20px;
        padding: 7px;
        width: auto;
        z-index: 10;
        color: #626262;
    }
    .theme-setting-sidebar-content {
        position: relative;
        height: 100%;
        max-height:90vh;
        overflow-y: auto!important;
        overflow-anchor: none;
        -ms-overflow-style: none;
        touch-action: auto;
        -ms-touch-action: auto;
        padding:20px;
        font-family:-apple-system, BlinkMacSystemFont, "San Francisco", "Segoe UI", Roboto, "Helvetica Neue", sans-serif;
    }
    .theme-setting-sidebar-content h4 {
        font-family:-apple-system, BlinkMacSystemFont, "San Francisco", "Segoe UI", Roboto, "Helvetica Neue", sans-serif;
        font-weight: 500;
        line-height: 1.2;
        color: #2C2C2C;
        margin-bottom:10px;
    }
    .theme-setting-sidebar-content small {
        font-size: smaller;
        font-weight: 400;
        line-height: 1.45;
        color: #626262;
    }
    .theme-setting-sidebar-content .header {
        background-color: #fff;
        padding: 10px 10px 10px 10px;
        overflow: hidden;
        text-transform: uppercase;
        border-bottom: 1px dashed #ccc;
    }
    .theme-setting-sidebar-content .nav-tabs>li>a {
        font-size:11px;
        padding: 8px;
        border-radius: 0;
        color:#626262;
    }
    .theme-setting-sidebar-content .nav-tabs>li>a span {
        font-family:-apple-system, BlinkMacSystemFont, "San Francisco", "Segoe UI", Roboto, "Helvetica Neue", sans-serif;
    }
    .theme-setting-sidebar-content .nav-tabs>li>a i{
        padding-right:5px;
    }
    .theme-setting-sidebar-content .tab-content>.tab-pane .box { padding:0; }
    .theme-setting-sidebar-content .header h2, .theme-setting-sidebar-content .header h3, .theme-setting-sidebar-content .header h4 {
        font-family:-apple-system, BlinkMacSystemFont, "San Francisco", "Segoe UI", Roboto, "Helvetica Neue", sans-serif;
        margin-bottom: 0px;
        font-weight: 500;
        line-height: 1.2;
        color: #2C2C2C;
        text-transform: uppercase;
        font-size:18px;
    }
    .theme-setting-sidebar-content p {
        font-size:12px;
        font-family:-apple-system, BlinkMacSystemFont, "San Francisco", "Segoe UI", Roboto, "Helvetica Neue", sans-serif;
    }
    .theme-setting-sidebar-content label {
        font-size:10px;
    }
    .theme-setting-sidebar-content .form-control {
        -webkit-appearance: none;
        -webkit-box-shadow: none;
        box-shadow: none;
        -webkit-transition: -webkit-box-shadow 0.25s linear, border 0.25s linear, color 0.25s linear, background-color 0.25s linear;
        -o-transition: box-shadow 0.25s linear, border 0.25s linear, color 0.25s linear, background-color 0.25s linear;
        transition: box-shadow 0.25s linear, border 0.25s linear, color 0.25s linear, background-color 0.25s linear;
        -moz-appearance: none;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
    }
    .theme-setting-sidebar-content .row {
        margin-left:-5px; margin-right:-5px;
    }
    .theme-setting-sidebar-content [class*=col-md-] {
        padding-left:5px; padding-right:5px;
    }
    .theme-setting-sidebar .btn-blue {
        width: 100%; font-size: 10px; padding: 5px 0; border-radius: 5px;
    }
    .theme-setting-sidebar-button {
        display: block;
        background: rgb(255, 255, 255);
        /*position: absolute;*/
        /*bottom: 0;*/
        /*left: 0;*/
        width: 100%;
        z-index: 50;
        padding: 10px 23px 10px 23px;
        margin-bottom: 0;
    }
    @media(max-width:500px) {
        .theme-setting-sidebar { display:none; }
    }
</style>
<script defer >
    $(function(){
        $('.theme-setting-sidebar .theme-setting-sidebar-toggle, .theme-setting-sidebar-close').click(function () {
            $('.theme-setting-sidebar').toggleClass('open');
            return false;
        });
        let ThemeSettingSidebarHandler = function () {
            $(document)
                .on('click', '.js_theme_setting_sidebar__clear',   this.clear)
                .on('submit', '#theme-setting-sidebar__form',   this.save)
        };
        ThemeSettingSidebarHandler.prototype.clear  = function (e) {
            let type = $(this).attr('data-type');
            let data = {
                action: 'ajax_theme_setting_sidebar_clear',
                type  : type,
            };
            $.post(base + '/ajax', data, function (response) { }, 'json').done(function (response) {
                show_message(response.message, response.status);
            });
            return false;
        };
        ThemeSettingSidebarHandler.prototype.save  = function (e) {
            let data = $(this).serializeJSON();
                data.action = 'ajax_theme_setting_sidebar_save';
            $.post(ajax, data, function (response) {}, 'json').done(function (response) {
                show_message(response.message, response.status);
            });
            return false;
        };
        new ThemeSettingSidebarHandler();
    });
</script>