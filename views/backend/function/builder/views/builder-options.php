<?php foreach ($themeOptions as $themeOptionKey => $themeOption) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a href="#themeOption_<?php echo $themeOptionKey;?>" data-builder-action="toggleOptions"><?php echo $themeOption['label'];?></a>
            </h4>
        </div>
        <div id="themeOption_<?php echo $themeOptionKey;?>" class="panel-collapse collapse js_theme_options_content">
            <div class="panel-body scrollbar">
                <?php foreach ($themeOption['sub'] as $keySub => $sub) { ?>
                    <div class="header">
                        <h2 style="color:#000;"><?php echo $sub['label'];?></h2>
                    </div>
                    <div class="row m-1"><?php echo $sub['form']->html(true);?></div>
                <?php } ?>
            </div>
            <div class="text-end"><button class="btn btn-white" data-builder-action="closeOptions">Đóng</button></div>
        </div>
    </div>
<?php } ?>