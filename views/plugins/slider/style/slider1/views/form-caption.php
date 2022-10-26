<div class="slider_demo_caption" >
    <ul>
        <!-- SLIDE  -->
        <li data-transition="fade" data-slotamount="5" data-masterspeed="700" >
            <!-- MAIN IMAGE -->
            <img src="<?= SliderRevolution::$path;?>images-demo/bg1.jpg"  alt="slidebg1"  data-bgfit="cover" data-bgposition="left top" data-bgrepeat="no-repeat">
            <?php SliderRevolutionHtml::caption($caption_key, $caption);?>
        </li>
        <li data-transition="fade" data-slotamount="5" data-masterspeed="700" >
            <!-- MAIN IMAGE -->
            <img src="<?= SliderRevolution::$path;?>images-demo/bg1.jpg"  alt="slidebg1"  data-bgfit="cover" data-bgposition="left top" data-bgrepeat="no-repeat">
            <?php SliderRevolutionHtml::caption($caption_key, $caption);?>
        </li>
    </ul>
</div>
<script type="text/javascript">
    var revapi;
    $(document).ready(function() {
        revapi = $('.slider_demo_caption').revolution({
            delay:3000,
            startheight:600,
            hideThumbs:10
        });
    });	//ready
</script>