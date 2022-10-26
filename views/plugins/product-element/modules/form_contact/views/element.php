<div class="pre-form-contact">
    <p class="pre-form-contact__title"><?php echo $config['title'];?></p>
    <div class="pre-form-contact__box">
        <form method="post" class="form email-register-form">
            <div class="form-group">
                <div class="input">
                    <input name="data" type="text" class="form-control input-lg" required placeholder="<?php echo $config['placeholder'];?>">
                    <input name="form_key" type="hidden" value="product_form_contact">
                    <input name="url" type="hidden" value="<?php echo Url::current();?>">
                </div>
                <button type="submit" class="btn"><?php echo html_entity_decode($config['button_icon']);?></button>
            </div>
        </form>
    </div>
</div>
<style>
    :root {
        --pre-form-contact-padding           : <?php echo $config['padding'];?>;
        --pre-form-contact-bg_box            : <?php echo (!empty($config['bg_box'])) ? $config['bg_box'] : 'var(--theme-color)';?>;
        --pre-form-contact-bg_input          : <?php echo $config['bg_input'];?>;
        --pre-form-contact-bg_button         : <?php echo $config['bg_button'];?>;
        --pre-form-contact-bg_button_hover   : <?php echo $config['bg_button_hover'];?>;
        --pre-form-contact-color_button      : <?php echo $config['color_button'];?>;
        --pre-form-contact-color_button_hover: <?php echo $config['color_button_hover'];?>;
    }
</style>