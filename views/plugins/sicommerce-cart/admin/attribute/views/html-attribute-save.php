<form method="post" accept-charset="utf-8" id="js_attribute_form_save">
    <?php
        if(isset($object) && have_posts($object)) {
            echo '<input type="hidden" name="id" class="form-control" value="'.$object->id.'">';
        }
        Admin::partial('include/form/form', ['object' => (isset($object) && have_posts($object)) ? $object : []]);
    ?>
</form>
<script>
    $(function() {
        $('.js_admin_form_btn__save').click(function (){
            $('#js_attribute_form_save').trigger('submit');
            return false;
        })
        $('#js_attribute_form_save').submit(function() {

            $('.loading').show();

            let data 		= $(this).serializeJSON();

            $(this).find('textarea').each(function(index, el) {
                let textareaId 	= $(this).attr('id');
                let value 		= $(this).val();
                if($(this).hasClass('tinymce') === true || $(this).hasClass('tinymce-shortcut') === true){
                    value 	= document.getElementById(textareaId+'_ifr').contentWindow.document.body.innerHTML;
                }
                data[$(this).attr('name')] = value;
            });

            data.action     =  'Admin_attributes_Ajax::save';

            data.sort = [];

            $('#js_attribute_main_list').find('.attribute-item').each(function(index) {
                data.sort.push($(this).attr('data-id'));
            });

            let jqxhr = $.post(ajax, data, function () {}, 'json');

            jqxhr.done(function(response) {

                show_message(response.message, response.status);

                $('.loading').hide();

                if(response.status === 'success') {

                    let url = base + 'plugins?page=attribute';

                    if(typeof $('#js_attribute_form_save input[name="id"]').val() != 'undefined') {
                        url += '&view=edit&id='+$('#js_attribute_form_save input[name="id"]').val();
                    }
                    window.location = url;
                }
            });

            return false;
        });
    });
</script>