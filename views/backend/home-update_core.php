<?php
if(empty($_SESSION['cms_version'])) {
	$_SESSION['cms_version'] 		= SKDService::cms()->version();
	$_SESSION['cms_version_time'] 	= time();
}
;?>
<div class="col-md-6 offset-md-3">
    <h2 style="text-align: center; margin-bottom: 10px;">SYSTEM UPDATER</h2>
    <p style="text-align: center; margin-bottom: 20px; color: #999">Kiểm tra lần cuối <?php echo date('d-m-Y H:i', $_SESSION['cms_version_time'] );?> </p>
    <div class="box">
        <!-- .box-content -->
        <div class="box-content">
			<div class="p-3">
				<?php if (version_compare($_SESSION['cms_version'], Cms::version()) === 1 ) { ?>
					<?php echo notice('warning', '<b>Quan Trọng:</b> trước khi cập nhật, hãy <b style="color:red">sao lưu dữ liệu và các tập tin</b> của bạn.');?>
					<h4 style="margin-bottom: 30px;">Phiên bản <?php echo $_SESSION['cms_version'];?> đã có sẳn.</h4>
                    <div class="release scrollbar"><?php echo $release;?></div>
                    <div class="text-center" id="js_update_core_progress">
                        <div id="progress-bar">
                            <div id="percent">100%</div>
                            <div class="progress" id="progress"></div>
                        </div>
                        <div id="process-log" class="text-left"></div>
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-blue btn-icon js_btn_update_core" style="margin: 50px 0; padding:10px 30px;"><i class="fal fa-cloud-upload"></i> Tải xuống & Cập nhật</button>
                    </div>
				<?php } else { ?>
					<?php echo notice('success', 'CMS của bạn hiện đang là phiên bản mới nhất', true, 'Tuyệt vời');?>
                    <div class="release scrollbar"><?php echo $release;?></div>
                    <div class="text-center">
                        <button type="button" class="btn btn-blue btn-icon js_btn_check_version" style="margin: 50px 0; padding:10px 30px;">
                            <i class="fa-thin fa-arrows-rotate"></i> Kiểm tra lại bản cập nhật
                        </button>
                    </div>
				<?php }; ?>
			</div>
        </div>
        <!-- /.box-content -->
    </div>
</div>

<style>
    @import url("https://fonts.googleapis.com/css2?family=Encode+Sans+Expanded:wght@500&display=swap");
    .release {
        padding:15px 10px 10px 10px;
        background-color: #f0f4ff;
        margin-bottom: 10px;
        border-left: 3px solid var(--blue);
        border-radius: 4px;
        max-height: 400px;
        overflow: auto;
    }
    #js_update_core_progress {
        //display: none;
    }
    #progress-bar {
        display: inline-block;
        width: 100%;
        height: 25px;
        margin-bottom: 10px;
        background: #ddd;
        position: relative;
    }
    #progress-bar #percent {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
    }
    #progress-bar #progress {
        width: 0;
        height: 100%;
        background: #3da849;
    }
    #process-log p {
        font-family: "Encode Sans Expanded", sans-serif; font-size: 12px;
    }
</style>

<script>
    $(function () {

        let btn;

        let progressBox = $('#js_update_core_progress');

        let percent = document.getElementById('percent');

        let progress = document.getElementById('progress');

        let progressLog = $('#process-log');

        let pcounter = 1;

        let max = 10;

        let Interval;

        function frame() {
            if(pcounter == max) {
                clearInterval(Interval);
            } else {
                pcounter +=1;
                percent.innerHTML = pcounter+'%';
                progress.style.width = pcounter +'%';
            }
        }

        let CmsUpdateCoreHandle = function() {
            $( document )
                .on('click', '.js_btn_update_core', this.run)
                .on('click', '.js_btn_check_version', this.checkVersion)

        }
        CmsUpdateCoreHandle.prototype.run = function(e) {
            btn = $(this);
            btn.hide();
            progressBox.show();
            progressLog.html('');
            max      = 0;
            pcounter = 1;
            CmsUpdateCoreHandle.prototype.closeWebsite();
        };
        CmsUpdateCoreHandle.prototype.fail = function(message) {
            btn.show();
            progressLog.append('<p style="color:red">'+message+'</p>');
        };
        CmsUpdateCoreHandle.prototype.closeWebsite = function( e ) {
            progressLog.html('<p>Chuyển website thành trạng thái bảo trì</p>');
            let jqxhr   = $.post( ajax, { 'action' : 'Ajax_Admin_Update_Core::closeWebsite' }, function(data) {}, 'json');
            jqxhr.done(function(response) {
                if(response.status == 'success') {
                    max      = 10;
                    Interval = setInterval(frame, 50);
                    setTimeout( function() {
                        CmsUpdateCoreHandle.prototype.download(response.cms_status);
                    }, 10*50);
                }
                else {
                    CmsUpdateCoreHandle.prototype.fail('Chuyển website thành trạng thái bảo trì thất bại');
                }
            });
            jqxhr.fail(function (response) { CmsUpdateCoreHandle.prototype.fail('Chuyển website thành trạng thái bảo trì thất bại'); });
        };
        CmsUpdateCoreHandle.prototype.download = function(status) {
            progressLog.append('<p>Download file cập nhật (vui lòng không refresh trang).</p>');
            let jqxhr   = $.post( ajax, { 'action' : 'Ajax_Admin_Update_Core::download', 'status' : status }, function(data) {}, 'json');
            jqxhr.done(function(response) {
                if(response.status == 'success') {
                    progressLog.append('<p>Download file cập nhật hoàn thành..</p>');
                    max      = 30;
                    Interval = setInterval(frame, 50);
                    setTimeout( function() {
                        CmsUpdateCoreHandle.prototype.extract(status);
                    }, 20*50);
                }
                else {
                    CmsUpdateCoreHandle.prototype.fail('Download file cập nhật thất bại');
                }
            });
            jqxhr.fail(function (response) { CmsUpdateCoreHandle.prototype.fail('Download file cập nhật thất bại'); });
        };
        CmsUpdateCoreHandle.prototype.extract = function(status) {
            progressLog.append('<p>Giải nén file cập nhật</p>');
            let jqxhr   = $.post( ajax, { 'action' : 'Ajax_Admin_Update_Core::extract', 'status' : status }, function(data) {}, 'json');
            jqxhr.done(function(response) {
                if(response.status == 'success') {
                    progressLog.append('<p>Giải nén file cập nhật hoàn tất.</p>');
                    max      = 60;
                    Interval = setInterval(frame, 50);
                    setTimeout( function() {
                        CmsUpdateCoreHandle.prototype.insert(status);
                    }, 30*50);
                }
                else {
                    CmsUpdateCoreHandle.prototype.fail('Giả nén file cập nhật thất bại');
                }
            });
            jqxhr.fail(function (response) { CmsUpdateCoreHandle.prototype.fail('Giả nén file cập nhật thất bại'); });
        };
        CmsUpdateCoreHandle.prototype.insert = function(status) {
            progressLog.append('<p>Cài đặt bản cập nhật</p>');
            let jqxhr   = $.post( ajax, { 'action' : 'Ajax_Admin_Update_Core::insert', 'status' : status }, function(data) {}, 'json');
            jqxhr.done(function(response) {
                if(response.status == 'success') {
                    progressLog.append('<p>Cập nhật thành công.</p>');
                    progressLog.append('<p>Cập nhật lại trạng thái website.</p>');
                    progressLog.append('<p>Refresh trang để hoàn tất quá trình cập nhật.</p>');
                    progressLog.append('<p>Trang sẽ tự động refresh lại sau <b id="js_time_refresh">5</b>s</p>');
                    max      = 100;
                    Interval = setInterval(frame, 50);
                    let time = 5, IntervalTime;
                    IntervalTime = setInterval(function () {
                        if(time == 0) {
                            clearInterval(IntervalTime);
                            window.location.reload(1);
                        }
                        $('#js_time_refresh').text(time);
                        time--;
                    }, 1000);
                }
                else {
                    CmsUpdateCoreHandle.prototype.fail('Cài đặt thất bại');
                }
            });
            jqxhr.fail(function (response) { CmsUpdateCoreHandle.prototype.fail('Cài đặt thất bại'); });
        };
        CmsUpdateCoreHandle.prototype.checkVersion = function() {
            btn = $(this);
            btn.hide();
            let jqxhr   = $.post( ajax, { 'action' : 'Ajax_Admin_Update_Core::checkVersion', 'status' : status }, function(data) {}, 'json');
            jqxhr.done(function(response) {
                if(response.status == 'success') {
                    btn.show();
                    if(response.check == true) {
                        window.location.reload();
                    }
                    else {
                        show_message('Phiên bản hiện tại là phiên bản mới nhất', 'warning');
                    }
                }
            });
        };

        new CmsUpdateCoreHandle();
    })
</script>

