<?php
if(!function_exists('admin_add_assets'))  {

    function admin_add_assets() {
        $template_assets = get_instance()->template->get_assets();
        Admin::asset()->location('header')->add('login',            $template_assets.'css/login.css', ['page' => ['users_login']]);
        Admin::asset()->location('header')->add('ToastMessages',    PLUGIN.'/ToastMessages/jquery.toast.css');
        Admin::asset()->location('header')->add('FontAwesome',      PLUGIN.'/font-awesome/css/all.min.css');
        if(Auth::check()) {
            Admin::asset()->location('header')->add('daterangepicker', PLUGIN . '/daterangepicker/daterangepicker.css');
            Admin::asset()->location('header')->add('select2', PLUGIN . '/select2/css/select2.min.css');
            Admin::asset()->location('header')->add('icheck', PLUGIN . '/icheck/skins/square/blue.css');
            Admin::asset()->location('header')->add('fancybox', PLUGIN . '/fancybox-3.0/jquery.fancybox.css');
            Admin::asset()->location('header')->add('bs-delete', PLUGIN . '/bootstrap-confirm-delete/bootstrap-confirm-delete.css');
            Admin::asset()->location('header')->add('spectrum', PLUGIN . '/spectrum/spectrum.min.css');
            Admin::asset()->location('header')->add('datepicker', PLUGIN . '/air-datepicker/datepicker.min.css');
            Admin::asset()->location('header')->add('code', PLUGIN . '/codemirror/lib/codemirror.css');
            Admin::asset()->location('header')->add('code', PLUGIN . '/codemirror/addon/hint/show-hint.css');
            Admin::asset()->location('header')->add('code', PLUGIN . '/codemirror/addon/dialog/dialog.css');
            Admin::asset()->location('header')->add('code', PLUGIN . '/codemirror/addon/display/fullscreen.css');
            Admin::asset()->location('header')->add('code', PLUGIN . '/codemirror/addon/search/matchesonscrollbar.css');
            Admin::asset()->location('header')->add('code', PLUGIN . '/codemirror/theme/icecoder.css');
            Admin::asset()->location('header')->add('code', PLUGIN . '/codemirror/theme/darkpastel.css');
            Admin::asset()->location('header')->add('editable', $template_assets . 'add-on/bootstrap-editable/css/bootstrap-editable.css');
        }
        Admin::asset()->location('header')->add('theme', $template_assets . 'css/style.css?v=' . Cms::version());
        Admin::asset()->location('header')->add('jquery', $template_assets .'js/jquery.js');
        Admin::asset()->location('footer')->add('ToastMessages',  PLUGIN.'/ToastMessages/jquery.toast.js');
        Admin::asset()->location('footer')->add('jquery-ui',      $template_assets.'js/jquery-ui.js?v=1.0');
        Admin::asset()->location('footer')->add('bootstrap',      $template_assets.'add-on/bootstrap-5.2.0/js/bootstrap.bundle.min.js');
        Admin::asset()->location('footer')->add('SerializeJSON',  $template_assets.'js/SerializeJSON.js');
        if(Auth::check()) {
            Admin::asset()->location('footer')->add('dateRangePicker', PLUGIN . '/daterangepicker/moment.min.js');
            Admin::asset()->location('footer')->add('dateRangePicker', PLUGIN . '/daterangepicker/daterangepicker.js');
            Admin::asset()->location('footer')->add('select2', PLUGIN . '/select2/js/select2.min.js');
            Admin::asset()->location('footer')->add('sortable',       $template_assets.'add-on/sortable/sortable.min.js');
            Admin::asset()->location('footer')->add('bs-editable',    $template_assets.'add-on/bootstrap-editable/js/bootstrap-editable.min.js');
            Admin::asset()->location('footer')->add('iCheck',         PLUGIN.'/icheck/icheck.min.js');
            Admin::asset()->location('footer')->add('fancybox',       PLUGIN.'/fancybox-3.0/jquery.fancybox.js');
            Admin::asset()->location('footer')->add('bs-delete',      PLUGIN.'/bootstrap-confirm-delete/bootstrap-confirm-delete.js');
            Admin::asset()->location('footer')->add('spectrum',    PLUGIN.'/spectrum/spectrum.min.js');
            Admin::asset()->location('footer')->add('sortable',       PLUGIN.'/sortable/jquery.nestable.js', array('menu_index'));
            Admin::asset()->location('footer')->add('tinymce',        PLUGIN.'/tinymce/tinymce.min.js');
            Admin::asset()->location('footer')->add('code',        PLUGIN.'/codemirror/lib/codemirror.js');
            Admin::asset()->location('footer')->add('code',        PLUGIN.'/codemirror/mode/css/css.js');
            Admin::asset()->location('footer')->add('code',        PLUGIN.'/codemirror/mode/javascript/javascript.js');
            Admin::asset()->location('footer')->add('code',        PLUGIN.'/codemirror/mode/htmlmixed/htmlmixed.js');
            Admin::asset()->location('footer')->add('code',        PLUGIN.'/codemirror/mode/php/php.js');
            Admin::asset()->location('footer')->add('code',        PLUGIN.'/codemirror/addon/hint/show-hint.js');
            Admin::asset()->location('footer')->add('code',        PLUGIN.'/codemirror/addon/hint/css-hint.js');
            Admin::asset()->location('footer')->add('code',        PLUGIN.'/codemirror/addon/hint/javascript-hint.js');
            Admin::asset()->location('footer')->add('code',        PLUGIN.'/codemirror/addon/dialog/dialog.js');
            Admin::asset()->location('footer')->add('code',        PLUGIN.'/codemirror/addon/display/fullscreen.js');
            Admin::asset()->location('footer')->add('code',        PLUGIN.'/codemirror/addon/search/searchcursor.js');
            Admin::asset()->location('footer')->add('code',        PLUGIN.'/codemirror/addon/search/search.js');
            Admin::asset()->location('footer')->add('code',        PLUGIN.'/codemirror/addon/scroll/annotatescrollbar.js');
            Admin::asset()->location('footer')->add('code',        PLUGIN.'/codemirror/addon/search/matchesonscrollbar.js');
            Admin::asset()->location('footer')->add('code',        PLUGIN.'/codemirror/addon/edit/closebrackets.js');
            Admin::asset()->location('footer')->add('code',        PLUGIN.'/codemirror/addon/edit/matchbrackets.js');
            Admin::asset()->location('footer')->add('code',        PLUGIN.'/codemirror/keymap/sublime.js');
            Admin::asset()->location('footer')->add('datepicker',  PLUGIN.'/air-datepicker/datepicker.min.js');
            Admin::asset()->location('footer')->add('datepicker',  PLUGIN.'/air-datepicker/i18n/datepicker.vi.js');
            Admin::asset()->location('footer')->add('theme',       $template_assets.'js/theme.js', array('page' => ['theme_index','theme_editor','theme_option','home_system']));
            Admin::asset()->location('footer')->add('menu',        $template_assets.'js/menu.js?v='.Cms::version(),    array('page' => ['menu_index']));
            Admin::asset()->location('footer')->add('widgets',     $template_assets.'js/widget.js?v='.Cms::version());
            Admin::asset()->location('footer')->add('gallery',     $template_assets.'js/gallery.js?v='.Cms::version());
            Admin::asset()->location('footer')->add('plugin',      $template_assets.'js/plugin.js?v='.Cms::version(),  array('page' => ['plugins_index', 'plugins_download', 'plugins_widget', 'widgets_index']));
            Admin::asset()->location('footer')->add('ajax',        $template_assets.'js/ajax.js');
            Admin::asset()->location('footer')->add('user',        $template_assets.'js/user.js');
            Admin::asset()->location('footer')->add('popover',     $template_assets.'js/popover.js');
            Admin::asset()->location('footer')->add('formBuilder', $template_assets.'js/form-builder.js');
            Admin::asset()->location('footer')->add('script',      $template_assets.'js/script.js?v='.Cms::version());

            if(Admin::is() && Template::isPage('plugins_index') && Request::get('page') == 'builder') {
                Admin::asset()->location('header')->add('builder', $template_assets.'less/builder.css');
                Admin::asset()->location('footer')->add('builder', $template_assets.'js/builder.js');
            }
        }
    }

    add_action('admin_init', 'admin_add_assets');
}