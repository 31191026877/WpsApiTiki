<?php
$Form = new FormBuilder();
$Form->add('sliderTxtType', 'select', ['start' => 12,'label' => 'Kiểu tiêu đề slider', 'options' => ['out-slider' => 'Dưới slider', 'in-slider' => 'Trong slider']], (empty($options['sliderTxtType'])) ? 'in-slider' : $options['sliderTxtType']);
$Form->add('sliderTxtBg', 'color', ['start' => 6,'label' => 'Màu nền thumb'], (empty($options['sliderTxtBg'])) ? '' : $options['sliderTxtBg']);
$Form->add('sliderTxtColor', 'color', ['start' => 6,'label' => 'Màu chữ thumb'], (empty($options['sliderTxtColor'])) ? '' : $options['sliderTxtColor']);
$Form->add('sliderTxtBgActive', 'color', ['start' => 6,'label' => 'Màu nền thumb (active)'], (empty($options['sliderTxtBgActive'])) ? '' : $options['sliderTxtBgActive']);
$Form->add('sliderTxtActive', 'color', ['start' => 6,'label' => 'Màu chữ thumb (active)'], (empty($options['sliderTxtActive'])) ? '' : $options['sliderTxtActive']);
$Form->add('sliderTxtFontSize', 'tab', [
    'start' => 12,'label' => 'Cỡ chữ',
    'options' => ['10' => '10', '11' => '11', '13' => '13',  '14' => '14', '15' => '15', '16' => '16', '17' => '17', '18' => '18', '20' => '20',  '25' => '25', '30' => '30'],
], (empty($options['sliderTxtFontSize'])) ? '14' : $options['sliderTxtFontSize']);

$Form->html(false);