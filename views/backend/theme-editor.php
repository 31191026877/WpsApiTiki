<link href="<?= $this->template->get_assets();?>cute-file-browser/assets/css/styles.css" rel="stylesheet"/>

<div class="filemanager">
    <?php Admin::partial('include/action_bar');?>
    <div class="col-md-3 col-editor-left">
        <input type="search" placeholder="Find a file.." />
        <ul class="data"></ul>

        <div class="nothingfound">
            <div class="nofiles"></div>
            <span>No files here.</span>
        </div>
    </div>
    <div class="col-md-9">
        <div id="result-editor">
            <div role="tabpanel">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                </div>
            </div>
        </div>
    </div>

</div>

<style>
.filemanager .data.animated, .filemanager .data {display:block!important;}
.col-editor-left {
    max-height:1000px;
    overflow:auto;
}
.CodeMirror { height: 600px!important; }
</style>