<?php
$upload_dir = 'uploads/source/';

$thum_w 	= 150;

$medium_w 	= 380;

$large_w 	= 580;

$thum_h 	= 150;

$medium_h 	= 380;

$large_h 	= 580;

if(isset($_SESSION['upload_dir'])) 	$upload_dir = $_SESSION['upload_dir'];

if(isset($_SESSION['thum_w'])) 		$thum_w 	= $_SESSION['thum_w'];

if(isset($_SESSION['medium_w'])) 	$medium_w 	= $_SESSION['medium_w'];

if(isset($_SESSION['large_w'])) 	$large_w 	= $_SESSION['large_w'];

if(isset($_SESSION['thum_h'])) 		$thum_h 	= $_SESSION['thum_h'];

if(isset($_SESSION['medium_h'])) 	$medium_h 	= $_SESSION['medium_h'];

if(isset($_SESSION['large_h'])) 	$large_h 	= $_SESSION['large_h'];

$config['upload_dir']                           = $upload_dir;
/*
|--------------------------------------------------------------------------
| relative path from filemanager folder to upload folder
|--------------------------------------------------------------------------
*/
$config['current_path']                         = './../../../uploads/source/';
/*
|--------------------------------------------------------------------------
| relative path from filemanager folder to thumbs folder
|--------------------------------------------------------------------------
*/
$config['thumbs_base_path']                     = './../../../uploads/thumbs/';
/*
|--------------------------------------------------------------------------
| default language file name
|--------------------------------------------------------------------------
*/
$config['default_language']                     = 'vi';
//**********************
// Hidden files and folders
//**********************
$config['hidden_files']                         = array( 'config.php', 'custom.php' );
/******************
 * AVIARY config
 *******************/
$config['aviary_active'] 						= false;
//************************************
//Thumbnail for external use creation
//************************************
$config['fixed_image_creation']                 = true;
$config['fixed_path_from_filemanager']          = array( './../../../uploads/thumbnail/', './../../../uploads/medium/',  './../../../uploads/large/');
$config['fixed_image_creation_name_to_prepend'] = array( '', '', '' );
$config['fixed_image_creation_to_append']       = array( '', '', '' );
$config['fixed_image_creation_width']           = array( $thum_w, $medium_w, $large_w );
$config['fixed_image_creation_height']          = array( $thum_h, $medium_h, $large_h );
$config['fixed_image_creation_option']          = array( 'auto', 'auto', 'auto' );


$config['relative_image_creation']              = false;
$config['relative_path_from_current_pos']       = array( './../../../uploads/thumbnail/', './../../../uploads/medium/', './../../../uploads/large/');
$config['relative_image_creation_width']        = array( $thum_w, $medium_w, $large_w );
$config['relative_image_creation_height']       = array( $thum_h, $medium_h, $large_h );
$config['relative_image_creation_option']       = array( 'auto', 'auto', 'auto' );


if(!function_exists('slug_name')){

    function slug_name($value = NULL){

        $chars = array(
            'a'	=>	array('ấ','ầ','ẩ','ẫ','ậ','Ấ','Ầ','Ẩ','Ẫ','Ậ','ắ','ằ','ẳ','ẵ','ặ','Ắ','Ằ','Ẳ','Ẵ','Ặ','á','à','ả','ã','ạ','â','ă','Á','À','Ả','Ã','Ạ','Â','Ă'),
            'e' =>	array('ế','ề','ể','ễ','ệ','Ế','Ề','Ể','Ễ','Ệ','é','è','ẻ','ẽ','ẹ','ê','É','È','Ẻ','Ẽ','Ẹ','Ê'),
            'i'	=>	array('í','ì','ỉ','ĩ','ị','Í','Ì','Ỉ','Ĩ','Ị'),
            'o'	=>	array('ố','ồ','ổ','ỗ','ộ','Ố','Ồ','Ổ','Ô','Ộ','ớ','ờ','ở','ỡ','ợ','Ớ','Ờ','Ở','Ỡ','Ợ','ó','ò','ỏ','õ','ọ','ô','ơ','Ó','Ò','Ỏ','Õ','Ọ','Ô','Ơ'),
            'u'	=>	array('ứ','ừ','ử','ữ','ự','Ứ','Ừ','Ử','Ữ','Ự','ú','ù','ủ','ũ','ụ','ư','Ú','Ù','Ủ','Ũ','Ụ','Ư'),
            'y'	=>	array('ý','ỳ','ỷ','ỹ','ỵ','Ý','Ỳ','Ỷ','Ỹ','Ỵ'),
            'd'	=>	array('đ','Đ'),
        );

        foreach ($chars as $key => $arr) {
            foreach ($arr as $val) {
                $value = str_replace($val, $key, $value);
            }
        }

        $value = str_replace('-', ' ', trim($value));

        $value = preg_replace('/[^a-z0-9-.()]+/i', ' ', $value);

        $value = trim(preg_replace('/\s\s+/', ' ', $value));

        return strtolower(str_replace(' ', '-', trim($value)));
    }
}

/*****************************
1. Custom USE_ACCESS_KEYS : config/config.php line 32
2. Custom function fix_filename: include/utils.php line 734
3. Custom function upcount_name: include/UploadHandler.php line 482
4. Custom Class UploadHandler upcount_name_callback: include/UploadHandler.php line 478
5. Custom Class UploadHandler onUploadEnd: include/UploadHandler.php line 1488
6. Fix error empty $field_id: dialog.php line 417
 ***************************************************/

if(!isset($_SESSION['watermark'])) {

    $_SESSION['watermark'] = [
        'image_watermark' 			=> false,
        'image_watermark_position' 	=> 'br',
        'image_watermark_padding'  	=> 10
    ];
}


$config['image_watermark'] = $_SESSION['watermark']['image_watermark'];
# Could be a pre-determined position such as:
#           tl = top left,
#           t  = top (middle),
#           tr = top right,
#           l  = left,
#           m  = middle,
#           r  = right,
#           bl = bottom left,
#           b  = bottom (middle),
#           br = bottom right
#           Or, it could be a co-ordinate position such as: 50x100
$config['image_watermark_position']  = $_SESSION['watermark']['image_watermark_position'];
# padding: If using a pre-determined position you can
#         adjust the padding from the edges by passing an amount
#         in pixels. If using co-ordinates, this value is ignored.
$config['image_watermark_padding'] = $_SESSION['watermark']['image_watermark_padding'];