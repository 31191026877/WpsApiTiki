<div class="box products-<?php echo (!empty($id)) ? $id : '';?>">
    <div class="header-title"><h3 class="header"><?php echo $heading;?></h3></div>
    <div class="box-content" style="overflow: inherit">
        <section class="question-section">
            <?php foreach ($questions as $key => $question) { ?>
                <div class="question-wrap">
                    <a data-bs-toggle="collapse" href="#collapse_<?php echo $question->id;?>" role="button" aria-expanded="false" aria-controls="collapse_<?php echo $question->id;?>"><?php echo $question->title;?></a>
                    <div class="collapse" id="collapse_<?php echo $question->id;?>"><?php echo $question->content;?></div>
                </div><!-- end of Catagory -->
            <?php } ?>
        </section><!-- End of Questions -->
    </div>
</div>

<style>
    .question-section .question-wrap {
        width: 100%;
        border-bottom: 1px solid rgba(238,238,238, 0.5);
        padding: 10px 0 0 0;
    }
    .question-section .question-wrap a {
        cursor: pointer;
        display: block;
        margin-bottom: 0;
        -webkit-transition: all 0.55s;
        transition: all 0.55s;
        color: #000;
        font-weight: 500;
        font-size: 15px;
        line-height: 1.4;
        text-transform: none;
        padding-bottom: 10px;
    }
    .question-section .question-wrap a:hover {
        color:var(--theme-color);
    }
    .question-section .question-wrap .collapse  {
        font-size: 13px!important; line-height: 25px!important;
    }
    .question-section .question-wrap .collapse.show {
        padding-bottom: 10px;
    }
</style>