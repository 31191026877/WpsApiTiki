<script defer>
    $(function () {
        $(document).on('click', '.btn-quickview', function (e) {
            e.preventDefault();
            let pid = $(this).attr('data-id');
            $.ajaxSetup({
                beforeSend: function(xhr, settings) {
                    settings.data += '&csrf_test_name=' + encodeURIComponent(getCookie('csrf_cookie_name'));
                }
            });
            $.fancybox.open({
                src  : base + '/ajax?action=quickview_ajax_product_load&id=' + pid,
                type : 'ajax',
                opts : {
                    onComplete : function () {
                        console.log(pid);
                    }
                }
            });
        });
        $(document).on('click', '.fancybox-close-small', function() {
            $.fancybox.close();
        });
    });
</script>