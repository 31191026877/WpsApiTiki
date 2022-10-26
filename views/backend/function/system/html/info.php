<?php
global $required_php_version;
function getMySQLVersion() {
    $output = get_model()->query("SHOW VARIABLES LIKE 'version'");
    return $output[0]->Value;
}

$errors = model()::table('error_logs')->orderBy('time', 'desc')->get();
/*
$directory = $_SERVER['DOCUMENT_ROOT'];
$size = 0;
foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory)) as $file){
    $size += $file->getSize();
}*/
?>
<div class="row">
    <div class="col-md-3">
        <div class="ui-title-bar__group" style="padding-bottom:5px;">
            <h3 class="ui-title-bar__title" style="font-size:20px;">SYSTEM ENVIRONMENT</h3>
            <p style="margin-top: 10px; margin-left: 1px; color: #8c8c8c">Thông tin môi trường hệ thống</p>
        </div>
    </div>
    <div class="col-md-9">
        <div class="box">
            <div class="box-content" style="padding:10px;">
                <div class="row system-info-detail">
                    <ul>
                        <li>CMS Version: <b><?php echo Cms::version();?></b></li>
                        <li>Timezone: <b><?php echo date_default_timezone_get();?></b></li>
                        <li>Debug Mode: <b><?php echo (DEBUG_LOG == true || DEBUG == true) ? '<span class="status success"><i class="fal fa-check"></i></span>' : '<span class="status error"><i class="fal fa-times"></i></span>';?></b></li>
                        <li>Upload Dir Writable: <b><?php echo (is_writable(Path::upload())) ? '<span class="status success"><i class="fal fa-check"></i></span>' : '<span class="status error"><i class="fal fa-times"></i></span>';?></b></li>
                        <li>Cache Dir Writable: <b><?php echo (is_writable('views/cache')) ? '<span class="status success"><i class="fal fa-check"></i></span>' : '<span class="status error"><i class="fal fa-times"></i></span>';?></b></li>
                        <li>Assets/Css Dir Writable: <b><?php echo (is_writable(Path::theme('assets/css'))) ? '<span class="status success"><i class="fal fa-check"></i></span>' : '<span class="status error"><i class="fal fa-times"></i></span>';?></b></li>
                        <li>Assets/Js Dir Writable: <b><?php echo (is_writable(Path::theme('assets/js'))) ? '<span class="status success"><i class="fal fa-check"></i></span>' : '<span class="status error"><i class="fal fa-times"></i></span>';?></b></li>
                    </ul>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
<hr />

<div class="row">
    <div class="col-md-3">
        <div class="ui-title-bar__group" style="padding-bottom:5px;">
            <h3 class="ui-title-bar__title" style="font-size:20px;">Server Environment</h3>
            <p style="margin-top: 10px; margin-left: 1px; color: #8c8c8c">Thông tin môi trường máy chủ</p>
        </div>
    </div>
    <div class="col-md-9">
        <div class="box">
            <div class="box-content" style="padding:10px;">
                <div class="row system-info-detail">
                    <ul>
                        <li>PHP Version: <b><?php echo PHP_VERSION;?> <?php echo (version_compare(PHP_VERSION, $required_php_version, '>=')) ? '<span class="status success"><i class="fal fa-check"></i></span>' : '<span class="status error"><i class="fal fa-times"></i></span>';?></b></li>
                        <li>Server Software: <b><?php echo Device::getPlatform();?></b></li>
                        <li>Server OS: <b><?php echo $_SERVER['SERVER_SOFTWARE'];?></b></li>
                        <li>Database: <b>Mysql <?php echo getMySQLVersion();?></b></li>
                    </ul>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
<hr />

<div class="row">
    <div class="col-md-3">
        <div class="ui-title-bar__group" style="padding-bottom:5px;">
            <h3 class="ui-title-bar__title" style="font-size:20px;">Server Extension</h3>
            <p style="margin-top: 10px; margin-left: 1px; color: #8c8c8c">Thông tin Extension máy chủ</p>
        </div>
    </div>
    <div class="col-md-9">
        <div class="box">
            <div class="box-content" style="padding:10px;">
                <div class="row system-info-detail">
                    <ul>
                        <li>Mysqli Extension: <b><?php echo (function_exists('mysqli_connect')) ? '<span class="status success"><i class="fal fa-check"></i></span>' : '<span class="status error"><i class="fal fa-times"></i></span>';?></b></li>
                        <li>OpenSSL Extension: <b><?php echo (extension_loaded('openssl')) ? '<span class="status success"><i class="fal fa-check"></i></span>' : '<span class="status error"><i class="fal fa-times"></i></span>';?></b></li>
                        <li>Mbstring Extension: <b><?php echo (extension_loaded('mbstring')) ? '<span class="status success"><i class="fal fa-check"></i></span>' : '<span class="status error"><i class="fal fa-times"></i></span>';?></b></li>
                        <li>CURL Extension: <b><?php echo (extension_loaded('curl')) ? '<span class="status success"><i class="fal fa-check"></i></span>' : '<span class="status error"><i class="fal fa-times"></i></span>';?></b></li>
                        <li>File info Extension: <b><?php echo (extension_loaded('fileinfo')) ? '<span class="status success"><i class="fal fa-check"></i></span>' : '<span class="status error"><i class="fal fa-times"></i></span>';?></b></li>
                        <li>Zip Extension: <b><?php echo (extension_loaded('zip')) ? '<span class="status success"><i class="fal fa-check"></i></span>' : '<span class="status error"><i class="fal fa-times"></i></span>';?></b></li>
                    </ul>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
<hr />

<div class="error-log">
    <div class="box" style="overflow:inherit;">
        <!-- .box-content -->
        <div class="box-content">
            <div class="table-responsive">
                <table class="display table table-striped media-table ">
                    <thead>
                    <tr>
                        <th class="manage-column">Thời gian</th>
                        <th class="manage-column">Đường dẫn</th>
                        <th class="manage-column">Log</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($errors as $error) { ?>
                        <tr class="tr_13">
                            <td class="" style="width: 150px"><?php echo date('d-m-Y H:i:s', $error->time);?></td>
                            <td class=""><?php echo $error->path;?></td>
                            <td class=""><?php echo $error->log;?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.box-content -->
    </div>

</div>

<style>
    .system-info-detail ul { margin-bottom: 0;}
    .system-info-detail ul li {
        display: block;
        border-bottom: 1px solid #ccc;
        padding:10px;
    }
    .system-info-detail .status {
        display: inline-block;
        border-radius: 50%;
        width: 25px; height: 25px; line-height: 25px; text-align: center;
        margin-left: 10px;
        color: #fff;
    }
    .system-info-detail .success {
        background-color: var(--green);
    }
    .system-info-detail .error {
        background-color: var(--red);
    }
</style>