<h4>Caption 3</h4>
<div class="slider-layer-item">
    <h5>Layer 1</h5>
    <div class="p-2">
        <?php echo FormBuilder::render(['field' => 'caption[layer1][text]', 'type' => 'textarea', 'label' => 'Text layer 1'], $caption['layer1']['text']);?>
        <?php echo FormBuilder::render(['field' => 'caption[layer1][color]', 'type' => 'color', 'label' => 'Color layer 1'], $caption['layer1']['color']);?>
    </div>
</div>

<div class="slider-layer-item">
    <h5>Layer 2</h5>
    <div class="p-2">
        <?php echo FormBuilder::render(['field' => 'caption[layer2][text]', 'type' => 'text', 'label' => 'Text layer 2'], $caption['layer2']['text']);?>
        <?php echo FormBuilder::render(['field' => 'caption[layer2][color]', 'type' => 'color', 'label' => 'Color layer 2'], $caption['layer2']['color']);?>
    </div>
</div>