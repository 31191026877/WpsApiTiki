<script type="text/javascript">

    $(function(){

        let nav = $('header');



        let nav_p = nav.position();



        $(window).scroll(function () {

            if ($(this).scrollTop() > nav_p.top) {

                nav.addClass('fixed');
                // $(".backtop").css('opacity', 1);

            } else {

                nav.removeClass('fixed');
                // $(".backtop").css('opacity', 0);

            }

        });

    });

</script>