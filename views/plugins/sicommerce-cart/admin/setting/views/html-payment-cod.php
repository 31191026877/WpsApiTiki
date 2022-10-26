<?php
$Form = new FormBuilder();
$Form->add('cod[enabled]', 'checkbox', ['label' => 'Bật/Tắt trả tiền khi nhận hàng',], (empty($payment['enabled'])) ? 0 : 'cod_enabled');
$Form->add('cod[title]', 'text', ['label' => 'Tiêu đề'], $payment['title']);
$Form->add('cod[description]', 'textarea', ['label' => 'Mô tả'], $payment['description']);
if(Language::hasMulti()) {
    foreach (Language::list() as $language_key => $language) {
        if($language_key == Language::default()) continue;
        $Form->add('cod[title_'.$language_key.']', 'text', ['label' => 'Tiêu đề ('.$language['label'].')'], (!empty($payment['title_'.$language_key])) ? $payment['title_'.$language_key] : '');
        $Form->add('cod[description_'.$language_key.']', 'textarea', ['label' => 'Mô tả ('.$language['label'].')'], (!empty($payment['description_'.$language_key])) ? $payment['description_'.$language_key] : '');
    }
}
$Form->add('cod[img]', 'image', ['label' => 'Icon'], $payment['img']);
$Form = apply_filters('admin_payment_'.$key.'_input_fields', $Form, $payment);
$Form->html(false);