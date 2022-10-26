<?php
$Form = new FormBuilder();
?><div class="col-xs-12 col-md-12">
    <div class="box">
        <div class="box-content" style="padding:10px;">
            <?php
                foreach ($socials as $key => $input) {
                    $input['after'] = '<div class="col-md-4">';
                    $input['before'] = '</div>';
                    $Form->add($input['field'], $input['type'], $input, option::get($input['field']));
                }
                $Form->html(false);
            ?>
        </div>
    </div>
</div>