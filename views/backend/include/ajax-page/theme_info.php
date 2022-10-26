<div class="theme-info">
    <div class="col-md-6 img">
        <img src="<?= $info->screenshot;?>" />
    </div>
    <div class="col-md-6">
        <h2 class="name"><?= $info->name;?></h2>
        <p class="version">Phiên bản : <?= $info->version;?></p>
        <p>Tác giả : <?= $info->author;?></p>
        <div class="description"><?= $info->description;?></div>
        
    </div>
</div>

<div class="clearfix"></div>


<style>
    .theme-info { font-size:15px;}
    .theme-info .img { overflow: hidden; }
    .theme-info .img img { height:auto;width:100%;}
    .theme-info .name, .theme-info .version  { display:inline-block; }
    .theme-info .name { font-weight:100;}
    .theme-info .version  { font-size:12px; }
    .theme-info .description  { margin:20px 0; }
</style>