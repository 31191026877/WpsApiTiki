<div class="box">
    <div class="header"><h3>Cấu hình chung</h3></div>
    <div class="box-content">
        <div class="row m-1">
        <?php
        $Form = new FormBuilder();
        $Form->add('module_active', 'checkbox', [
            'label' => 'Hiển thị',
            'options' => [
                'form_contact' => 'Form contact',
                'box_sale'     => 'Khung khuyến mãi',
            ],
            'after' => '<div class="col-md-6">',
            'before' => '</div>',
        ], Product_Element::config('module_active'));
        $Form->html(false);
        ?>
        </div>
    </div>
</div>

<style>
    .select-img .checkbox img { width:50px; border:1px solid #ccc; }
    .select-img .checkbox input:checked + label img {
        border:1px solid #000;
    }
    .select-img .checkbox label span { display: none;}
</style>
