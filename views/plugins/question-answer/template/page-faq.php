<?php
$categories = PostCategory::gets(Qr::set('cate_type', 'question-answer-category'));
if(!have_posts($categories)) {
    $objects    = Posts::gets(Qr::set('post_type', QA_KEY));
}
?>
<div class="container">
    <div class="wrap">
        <?php if(have_posts($categories)) {
            foreach ($categories as $category) { $objects =  Posts::gets(Qr::set('post_type', QA_KEY)->whereByCategory($category));?>
                <section class="question-section">
                    <div class="heading"><h3><?php echo $category->name;?></h3></div>
                    <?php foreach ($objects as $key => $object) { ?>
                        <div class="question-wrap">
                            <a data-bs-toggle="collapse" href="#collapse_<?php echo $object->id;?>" role="button" aria-expanded="false" aria-controls="collapse_<?php echo $object->id;?>"><?php echo ($key+1).'. '.$object->title;?></a>
                            <div class="collapse" id="collapse_<?php echo $object->id;?>"><?php echo $object->content;?></div>
                        </div><!-- end of Catagory -->
                    <?php } ?>
                </section><!-- End of Questions -->
            <?php }
        }
        else {
            ?>
            <section class="question-section">
                <?php foreach ($objects as $key => $object) { ?>
                    <div class="question-wrap">
                        <a data-bs-toggle="collapse" href="#collapse_<?php echo $object->id;?>" role="button" aria-expanded="false" aria-controls="collapse_<?php echo $object->id;?>"><?php echo ($key+1).'. '.$object->title;?></a>
                        <div class="collapse" id="collapse_<?php echo $object->id;?>"><?php echo $object->content;?></div>
                    </div><!-- end of Catagory -->
                <?php } ?>
            </section><!-- End of Questions -->
            <?php
        } ?>

        <div class="question-section-form">
            <div class="header-title"><h3 class="header">Gửi câu hỏi cho chúng tôi</h3></div>
            <form action="" method="post" id="js_question_form__save">
                <?php echo Admin::loading();?>
                <div class="form-group">
                    <label for="">Họ tên</label>
                    <input type="text" class="form-control" name="name" placeholder="Họ và tên của bạn" required>
                </div>
                <div class="form-group">
                    <label for="">Email</label>
                    <input type="email" class="form-control" name="email" placeholder="Email của bạn" required>
                </div>
                <div class="form-group">
                    <label for="">Câu hỏi</label>
                    <textarea name="question" class="form-control" cols="30" rows="10" required placeholder="Câu hỏi của bạn"></textarea>
                </div>
                <button type="submit" class="btn btn-effect-default btn-theme btn-block">Gửi</button>
            </form>
        </div>
    </div>
</div>

<style>
    body {
        -webkit-animation: bugfix infinite 1s;
    }
    @-webkit-keyframes bugfix {
        from {
            padding: 0;
        }
        to {
            padding: 0;
        }
    }

    .wrap {
        position: relative;
        width: 780px;
        max-width: 100%;
        margin: 0 auto;
    }
    .question-section h3 {
        font-size: 30px; font-weight: bold;
        margin-top: 30px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--theme-color);
        display: inline-block;
    }
    .question-section .question-wrap {
        width: 100%;
        border-bottom: 1px solid rgba(238,238,238, 0.5);
        padding: 30px 0 0 0;
    }
    .question-section .question-wrap a {
        cursor: pointer;
        display: block;
        margin-bottom: 0;
        -webkit-transition: all 0.55s;
        transition: all 0.55s;
        color: #000;
        font-weight: bold;
        font-size: 20px;
        line-height: 1.4;
        text-transform: none;
        padding-bottom: 30px;
    }
    .question-section .question-wrap a:hover {
        color:var(--theme-color);
    }
    .question-section .question-wrap .collapse  {
        font-size: 16px!important; line-height: 26px!important;
    }
    .question-section .question-wrap .collapse.show {
        padding-bottom: 30px;
    }

    .question-section-form {
        margin-top: 30px;
    }
    .question-section-form .header-title h3 {
        margin-top: 30px;
        margin-bottom: 30px;
        font-size: 40px;
    }
    .question-section-form form {
        position: relative;
        width: 500px;
        max-width: 100%;
        box-shadow: 0px 15px 35px rgb(0 0 0 / 10%);
        padding: 14px;
        border-radius: 10px;
        margin: 0 auto 20px;
        background-color: #fff;
    }
    .question-section-form label {
        font-weight: bold;
        margin-bottom: 10px;
    }
    .question-section-form .form-control {
        margin-bottom: 10px;
    }
</style>
<script>
    $(function () {
        $('#js_question_form__save').submit(function () {

            let loader = $(this).find('.loading');

            let data = $(this).serializeJSON();

            data.action = 'ajax_question_save';

            loader.show();

            let jqxhr = $.post(ajax, data, function () { }, 'json');

            jqxhr.done(function (response) {
                loader.hide();
                show_message(response.message, response.status);
                if (response.status === 'success') {
                    $('#js_question_form__save').trigger('reset');
                }
            });

            return false;
        });
    })
</script>