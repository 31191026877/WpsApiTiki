<?php

class widget_api_tiki_test extends widget
{

    function __construct()
    {

        parent::__construct('widget_api_tiki_test', 'widget_api_tiki_test', ['container' => true, 'position' => 'left']);

        add_action('theme_custom_css', array($this, 'css'), 10);
    }

    function form($left = [], $right = [])
    {
        $this->left
            ->add('tikiUrl', 'url', ['label' => 'Tiki url sản phẩm']);
        parent::form($left, $right);
    }

    function widget()
    {

        $box = $this->container_box('widget_api_tiki_test');
        echo $box['before'];
?>
        <p>
            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                Button with data-bs-target
            </button>
        </p>
        <div class="collapse show" id="collapseExample">
            <div class="card card-body">
                Some placeholder content for the collapse component. This panel is hidden by default but revealed when the user activates the relevant trigger.
            </div>
        </div>
        <?php
        if ($this->name != '') { ?><div class="header-title">
                <h3 class="header"><?= $this->name; ?></h3>
            </div><?php }

                    ?>
        <?php
        $str = 'https://tiki.vn/bo-ga-goi-cotton-tici-ke-lidaco-cao-cap-caro-xanh-tang-01-vo-goi-om-p59387881.html?itm_campaign=tiki-reco_UNK_DT_UNK_UNK_maybe-you-like_maybe-you-like_pdp-product-discover-v1_202210110600_MD_batched_PID.59387903&itm_medium=CPC&itm_source=tiki-reco&spid=59387903';
        $strs = strrev($str);
        $count = strlen($strs);
        $idP1 = strpos($strs, 'lmth.');
        $str2 = Str::after($strs, 'lmth.');
        $idx = strpos($str2, 'p');
        $str3 = substr($str2, 0, ($idx));
        //create function get api categories with token header and access token
        function get_api_categories($url)
        {
            $headers = array(
                'Content-Type: application/json',
                'Authorization: Bearer 8022216669660690.SkpdkvSxr--m4uzGRQcd2_cqdf0N10Rb',
                'access_token: 8022216669660690.SkpdkvSxr--m4uzGRQcd2_cqdf0N10Rb',
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
        }

        $url = 'https://tiki.vn/api/v2/products/' . strrev($str3) . '?platform=web&spid=' . Str::after($str, 'spid=') . '';
        $categories = get_api_categories($url);
        //convert json to array
        $categories = json_decode($categories, true);
        //echho categories
        show_r($categories);
        ?>


<?php

        echo $box['after'];
    }

    function update($new_instance, $old_instance)
    {

        return $new_instance;
    }

    function css()
    {
    }
}

Widget::add('widget_api_tiki_test');
