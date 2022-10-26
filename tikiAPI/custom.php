<?php
// add menu vao admin
function add_menu_admin()
{
    $args = [
        'icon'  => '<img src="icon-post.png">',
        'callback' => 'plugin_demo_callback',
        'position' => 'products_categories'
    ];
    AdminMenu::addSub('products', 'plugin_demo', 'TIKI API GET', 'plugins?page=plugin_demo', $args);
}
add_action('admin_init', 'add_menu_admin');
// callback action render view
function plugin_demo_callback()
{
?>
    <h1>Module get thông tin sản phẩm tiki từ url người dùng</h1>
    <input class="form-control" name="urlTiki" type="textarea" placeholder="Nhập url sản phẩm tiki" required>
    <button type="submit" name="submit" class="btn btn-effect-default btn-theme mt-3 addInfo">Thêm</button>
    <p class="showDINA"></p>
    <script defer>
        $('.addInfo').on('click touch', function() {
            var urlTiki = $('input[name="urlTiki"]').val();
            if (!urlTiki) {
                alert('Vui long nhập link sản phẩm');
            } else {
                var data = {
                    'tiki_url': urlTiki,
                    'action': 'ajax_insert_info',
                }
                $process = $.post(base + '/ajax', data, function(data) {}, 'json');

                $process.done(function(result) {
                    show_message(result.message, result.status);
                })
                return false;
            }
        });
    </script>
<?php
}
// xu ly ajax
function ajax_insert_info()
{
    $post = InputBuilder::post();
    $result = [];
    // Lấy url tiki thông qua ajax
    $urlTiki = $post['tiki_url'];
    // get spid dùng hàm cắt chuỗi spid
    $spID = Str::after($urlTiki, 'spid=');
    // get id ->đảo ngược chuỗi cất chuổi rồi substring
    $strs = strrev($urlTiki);
    $str2 = Str::after($strs, 'lmth.');
    $idx = strpos($str2, 'p');
    $str3 = substr($str2, 0, ($idx));
    $idSP =  strrev($str3);
    // Dựa theo api get info
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
        $results = curl_exec($ch);
        curl_close($ch);
        return $results;
    }
    // if ((!empty($idSP)) && (!empty($spID))) {
    $url = 'https://tiki.vn/api/v2/products/' . $idSP . '?platform=web&spid=' . $spID . '';
    $spInfo = get_api_categories($url);
    $spInfo = json_decode($spInfo, true);
    // check nếu có thì tôi thêm còn ko thì not vì nếu thất bại thì mảng $spInfo sẽ trả về mảng gồm array error
    // HÀM dưới này nếu ko trả về gì ->success, trả về 1 (true) ->fail
    $check = Arr::exists($spInfo, 'error');

    if (empty($check)) {
        $args = ['title' => $spInfo['name'], 'content' => $spInfo['description'], 'image' => $spInfo['thumbnail_url'], 'price' => $spInfo['price'], 'price_sale' => $spInfo['original_price']];
        Product::insert($args);
        $result['status'] = 'success';
        $result['message'] = 'insert thành công.';
    }

    else{
        $result['status'] = 'failed';
        $result['message'] = 'Thất bại.Vui lòng nhập đúng link sản phẩm';
    }


    echo json_encode($result);
}
// Đăng ký ajax cho admin (chỉ chạy trong luồng admin)
Ajax::admin('ajax_insert_info');
