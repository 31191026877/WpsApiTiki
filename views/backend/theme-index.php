<?php Admin::partial('include/action_bar');?>

<div class="col-md-12">
    <div class="box">
        <div class="header">
            <h2>GIAO DIỆN</h2>
        </div>
        <div class="box-content list-tl-item">
            <?php if(have_posts($list_template)) :?>
                <?php foreach ($list_template as $key => $val) {?>
                <div class="col-md-4">
                <div class="tl-item <?= $val->name == $system->theme_current ? 'active' : '';?>">
                    <div class="img">
                        <img src="<?= $val->screenshot;?>" />
						<div class="overflow">
							<button class="theme-info view-more" value="<?= $val->name;?>">Thông Tin Giao Diện</button>
						</div>
                    </div>
                    <div class="title">
						<h3><?= $val->label;?></h3>
						<?php if($val->name == $system->theme_current) :?>
						<div class="get-link get-link-full"><button class="theme-info btn btn-blue" value="<?= $val->name;?>">Thông Tin</button></div>
						<?php else: ?>
						<div class="get-link"><button class="theme-active btn btn-blue" value="<?= $val->name;?>">Kích hoạt</button></div>
						<div class="get-link"><button class="btn btn-red">Xóa</button></div>
						<?php endif; ?>
					</div>
                </div>
                </div>
                <?php } ?>
            <?php else : ?>
            <p> Chưa có giao diện nào !</p>
            <?php endif ?>
        </div>
    </div>
</div>
<style type="text/css" media="screen">
	.list-tl-item { overflow: hidden; background:transparent;}
	.list-tl-item .tl-item { border:2px solid #ccc;margin-bottom: 20px;}
	.list-tl-item .tl-item .img { background-color:#fff;position:relative; width:100%; height:200px; overflow: hidden;}
	.list-tl-item .tl-item .img img{ height:auto;width:100%;transition: all ease-in-out 3.2s;
        position: absolute;
        top: 0;
        left: 0; 
    }

	.list-tl-item .tl-item .overflow {
	    position: absolute;
	    top: 0!important;
	    left: 0!important;
	    width: 100%;
	    height: 100%;
	    text-align: center;
	    vertical-align: middle;
	    background-color: rgba(0,0,0,.5);
	    opacity: 0;
	    transition: all 0.5s;
	}
	.list-tl-item .tl-item .overflow .view-more {
		display: inline-block;
		padding: 10px 20px;
		color: #fff;
		font-size: 18px;
		font-weight: bold;
		background-color: rgba(0, 0, 0, 0.72);
		margin-top: 25%;
		border: 0;
	}

	.list-tl-item .tl-item .title {border-top:2px solid #ccc;background-color: #fff;text-align: center;overflow: hidden;font-size:15px;}
	.list-tl-item .tl-item .title h3 { padding:13px 5px 5px; float:left; width:50%;margin: 0;color:#000; font-size:18px; }
	.list-tl-item .tl-item .title .get-link { padding:0px;float:left; width:25%;font-size: 15px;text-align: center;background-color: #fff }
	.list-tl-item .tl-item .title .get-link button { padding: 8px 0px; width: 100%;}
	.list-tl-item .tl-item .title .get-link-full { width:50%;}

	.list-tl-item .tl-item:hover, 
	.list-tl-item .tl-item.active { border:2px solid #2B3643;}
	.list-tl-item .tl-item:hover .img img, 
	.list-tl-item .tl-item.active .img img { 
    }
	.list-tl-item .tl-item:hover .overflow, 
	.list-tl-item .tl-item.active .overflow { opacity: 1;} 
	.list-tl-item .tl-item:hover .title {border-top:2px solid #2B3643; }
	.list-tl-item .tl-item.active .title {border-top:2px solid #2B3643;background-color: #2B3643;} 
	.list-tl-item .tl-item.active .title h3 {color:#fff;}
</style>

<!-- Modal -->
<div class="modal fade" id="modal-theme-info" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Thông Tin Theme</h4>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>