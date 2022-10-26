<input type="hidden" name="style" value="style_2" id="style">
<?php
$Form = new FormBuilder();

$Form->add('btn_bg_color', 'color', [
    'label' => 'Màu nền button',
    'after' => '<div class="col-md-6"><div class="form-group">',
    'before' => '</div></div>'
], (isset($item['btn_bg_color'])) ? $item['btn_bg_color'] : '');

$Form->add('txt_color', 'color', [
    'label' => 'Màu chữ button',
    'after' => '<div class="col-md-6"><div class="form-group">',
    'before' => '</div></div>'
], (isset($item['txt_color'])) ? $item['txt_color'] : '');

$Form->add('btn_bg_color_hover', 'color', [
    'label' => 'Màu nền button (hover)',
    'after' => '<div class="col-md-6"><div class="form-group">',
    'before' => '</div></div>'
], (isset($item['btn_bg_color_hover'])) ? $item['btn_bg_color_hover'] : '');
$Form->add('txt_color_hover', 'color', [
    'label' => 'Màu chữ button (hover)',
    'after' => '<div class="col-md-6"><div class="form-group">',
    'before' => '</div></div>'
], (isset($item['txt_color_hover'])) ? $item['txt_color_hover'] : '');

$Form->add('txt_quickview', 'text', [
    'label' => 'Chữ button quick view',
    'after' => '<div class="col-md-6"><div class="form-group">',
    'before' => '</div></div>'
], (isset($item['txt_quickview'])) ? htmlentities($item['txt_quickview']) : '');
$Form->add('txt_buy', 'text', [
    'label' => 'Chữ button mua ngay',
    'after' => '<div class="col-md-6"><div class="form-group">',
    'before' => '</div></div>'
], (isset($item['txt_buy'])) ? htmlentities($item['txt_buy']) : '');
$Form->add('txt_view', 'text', [
    'label' => 'Chữ button view',
    'after' => '<div class="col-md-12"><div class="form-group">',
    'before' => '</div></div>'
], (isset($item['txt_view'])) ? htmlentities($item['txt_view']) : '');
if(Language::hasMulti()) {
    foreach (Language::list() as $key => $lang) {
        if($key == Language::default()) continue;
        $Form->add('txt_quickview_'.$key, 'text', [
            'label' => 'Chữ button quick view ('.$lang['label'].')',
            'after' => '<div class="col-md-6"><div class="form-group">',
            'before' => '</div></div>'
        ], (isset($item['txt_quickview_'.$key])) ? htmlentities($item['txt_quickview_'.$key]) : '');
        $Form->add('txt_buy_'.$key, 'text', [
            'label' => 'Chữ button mua ngay ('.$lang['label'].')',
            'after' => '<div class="col-md-6"><div class="form-group">',
            'before' => '</div></div>'
        ], (isset($item['txt_buy_'.$key])) ? htmlentities($item['txt_buy_'.$key]) : '');
        $Form->add('txt_view_'.$key, 'text', [
            'label' => 'Chữ button view ('.$lang['label'].')',
            'after' => '<div class="col-md-12"><div class="form-group">',
            'before' => '</div></div>'
        ], (isset($item['txt_view_'.$key])) ? htmlentities($item['txt_view_'.$key]) : '');
    }
}

$Form->html(false);