<div class="box">
    <div class="header"><h3>DEMO</h3></div>
    <div class="box-content text-center">
        <img src="<?php echo PR_EL_PATH.'/assets/images/form-contact.png';?>" alt="">
    </div>
</div>
<div class="box">
    <div class="header"><h3>TEXT</h3></div>
    <div class="box-content row m-1">
        <?php
        $Form = new FormBuilder();
        $Form->add('form_contact[position]', 'number', ['label' => 'Thứ tự gắn vào hook "product_detail_info"'], Product_Element_Form_Contact::config('position'));
        $Form->add('form_contact[title]', 'text', ['label' => 'Tiêu đề'], Product_Element_Form_Contact::config('title'));
        $Form->add('form_contact[placeholder]', 'text', ['label' => 'Placeholder input'], Product_Element_Form_Contact::config('placeholder'));
        $Form->add('form_contact[button_icon]', 'text', ['label' => 'Button send icon'], Product_Element_Form_Contact::config('button_icon'));
        $Form->html(false);
        ?>
    </div>
</div>

<div class="box">
    <div class="header"><h3>STYLE</h3></div>
    <div class="box-content row m-1">
        <?php
        $Form->add('form_contact[padding]', 'text', ['label' => 'Padding khung'], Product_Element_Form_Contact::config('padding'));
        $Form->add('form_contact[bg_box]', 'color', ['label' => 'Màu nền Box'], Product_Element_Form_Contact::config('bg_box'));
        $Form->add('form_contact[bg_input]', 'color', ['label' => 'Màu nền input'], Product_Element_Form_Contact::config('bg_input'));
        $Form->add('form_contact[bg_button]', 'color', ['label' => 'Màu nền button'], Product_Element_Form_Contact::config('bg_button'));
        $Form->add('form_contact[bg_button_hover]', 'color', ['label' => 'Màu nền button hover'], Product_Element_Form_Contact::config('bg_button_hover'));
        $Form->add('form_contact[color_button]', 'color', ['label' => 'Màu text button'], Product_Element_Form_Contact::config('color_button'));
        $Form->add('form_contact[color_button_hover]', 'color', ['label' => 'Màu text button hover'], Product_Element_Form_Contact::config('color_button_hover'));
        $Form->html(false);
        ?>
    </div>
</div>

<style>
    .select-img .checkbox img { width:50px; border:1px solid #ccc; }
    .select-img .checkbox input:checked + label img {
        border:1px solid #000;
    }
    .select-img .checkbox label span { display: none;}
</style>
