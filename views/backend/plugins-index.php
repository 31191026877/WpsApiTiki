<?php Admin::partial('include/action_bar');?>
<div class="plugins">
    <div class="sidebar">
        <div class="header-bar"> <h2>Đã cài</h2> </div>
        <div class="plugins-list scrollbar" id="plugin_list">
        </div>
    </div>
    <div class="content plugin-service">
        <div class="header-bar">
            <form action="" method="post" role="form">
                <div class="form-group col-md-6">
                    <input id="js_plugin_search" type="text" class="form-control" name="" placeholder="Tìm kiếm...">
                </div>
                <div class="form-group col-md-6 text-right"></div>
            </form>
        </div>
        <div class="plugin-service-list scrollbar" style="position: relative">
            <?php Admin::loading();?>

            <div id="plugin_service_list" class="row m-1"></div>

            <div id="plugin_service_license" style="display:none;">
                <div class="box" style="margin:10px; padding:20px;">
                    <div class="box-content">
                        <form action="" method="post" class="form-horizontal" role="form" id="plugin_service_license_form">
                            <div class="col-md-12">
                                <label for="api_user" class="control-label" style="margin-bottom: 10px;">API USERNAME</label>
                                <div class="group">
                                    <input type="text" name="api_user" value="<?php echo option::get('api_user');?>" class="form-control ">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label for="api_secret_key" class="control-label" style="margin-bottom: 10px;">SECRET KEY</label>
                                <div class="group">
                                    <input type="text" name="api_secret_key" value="<?php echo option::get('api_secret_key');?>" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="group text-right" style="padding-top:10px;">
                                    <button class="btn btn-icon btn-green" type="submit"><?php echo Admin::icon('save');?>Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .page-content .page-body { padding-top: 0;}
    .page-content .ui-layout { overflow: hidden; max-width: 2200px;}
    .page-content .action-bar { display: none; }
</style>

<script id="js_plugin_item_template" type="text/x-custom-template">
    <div class="plugin-item" data-name="${name}">
        <div class="plugin-thumb">
            <img src="${thumb}" />
        </div>
        <div class="plugin-infomation">
            <div class="plugin-title">
                <h3>${label} <span>${label_active}</span></h3>
                <label>${author}</label>
                <label>v.${version}</label>
                ${version_new}
            </div>
            <div class="plugin-action">${action}</div>
        </div>
    </div>
</script>

<script id="js_plugin_service_item_template" type="text/x-custom-template">
    <div class="col-xs-12 col-sm-6 col-md-3 plugin-item-box">
        <div class="plugin-item" data-name="${folder}">
            <div class="img">
                <img src="${image}" />
                <div class="description">${excerpt}</div>
            </div>
            <div class="title">
                <h3 style="margin-bottom: 10px; font-size: 12px;height:22px;">${title}</h3>
            </div>
            <div class="plugin-action" style="float:none;text-align:right;width:100%;">
                ${action}
            </div>
            <div class="info">
                <div class="author"><b>Version:</b> ${version}</div>
            </div>
        </div>
    </div>
</script>