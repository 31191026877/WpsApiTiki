<?php
$links = $ci->action_links();
$url = Url::current();
$url = str_replace(Url::base(), '', $url);
?>
<div class="user-info">
    <p class="user-name"><?php echo $user->username;?></p>
    <p><?php echo $user->firstname.' '.$user->lastname;?></p>
    <p class="user-email"><span><?php echo (!empty($user->email))?$user->email:'';?></span></p>
</div>
<ul class="js_account_action_list">
	<?php foreach ($links as $link):?>
    <?php $link['url'] = str_replace(Url::base(), '', $link['url']);?>
	<li class="js_account_action_item <?php echo ($link['url'] == $url) ? 'active' : '';?>">
        <a href="<?php echo $link['url'];?>"><?php echo $link['icon'];?> <?php echo $link['label'];?> </a>
        <?php if(isset($link['sub']) && have_posts($link['sub'])) {?>
            <ul class="js_account_action_sub_list">
            <?php foreach ($link['sub'] as $item) { ?>
                <?php $item['url'] = str_replace(Url::base(), '', $item['url']);?>
                <li class="<?php echo ($item['url'] == $url) ? 'active' : '';?>"><a href="<?php echo $item['url'];?>"><?php echo $item['icon'];?> <?php echo $item['label'];?> </a></li>
            <?php } ?>
            </ul>
        <?php } ?>
    </li>
	<?php endforeach ?>
</ul>

<style type="text/css">
	.wrapper {
		background-color: #F7F8FA;
	}
	.user-profile {
		margin: 50px 0; display: flex; flex-wrap: wrap;
	}
	.user-header-title {
		margin-top: 0; padding:0;
		font-size: 18px; font-weight: bold;
		text-transform: uppercase;
		margin-bottom:20px;
	}
	.user-header h1 { margin-top: 0; font-size: 18px; padding:0; }
	.user-action .user-header-title {
		margin-top: 20px; 
	}
	.user-action .user-info {
        display: block;
        margin-bottom: 0px;
        border-radius: 5px 5px 0 0;
        background-color: #fff;
        padding:10px;
        border-bottom: 1px solid #F7F8FA;
	}
    .user-action .user-info p {
       margin-bottom: 0; text-align: center;
    }
    .user-action .user-info p.user-name {
        font-size: 15px; font-weight: bold;
        text-transform: capitalize;
    }
    .user-action .user-info p.user-email {
        font-size: 13px;
    }
	.user-action ul {
		list-style: none; overflow: hidden;
	}
	.user-action>ul>li {
		display: block;
        border-bottom: 0;
        margin-bottom: 0px;
        border-radius: 0px;
        background-color: #fff;
	}
    .user-action>ul>li:last-child {
        border-radius: 0px 0 5px 5px;
    }
	.user-action>ul>li a {
		display: block; padding:10px; font-size: 15px; color:#4a4a4a;
	}
	.user-action>ul>li a i {
		font-size: 20px; padding-right: 20px;
	}
    .user-action>ul>li:hover,
    .user-action>ul>li.active {
        background-color:var(--theme-color);
        box-shadow: 3px 4px 10px #0000003d;
        color: #fff;
    }
	.user-action>ul>li:hover>a,
    .user-action>ul>li.active>a {
		color: #fff; font-weight: bold;
	}
    .user-action>ul>li>ul {
        padding-left: 38px; display: none;
    }
    .user-action>ul>li.active>ul {
        display: block;
    }

    .user-action>ul>li.active ul li.active a{
        color: #cc3300;
    }
	.user-content {
		min-height: 50vh; 
		background-color:#fff; 
		padding:20px;
		margin-bottom:10px;
		box-shadow: 3px 5px 10px #cccccca6;
		border-radius:5px;
	}
	.user-content .form-group { margin: 0 0 15px 0; }
	.user-content .form-group .control-label { text-align: left; font-weight: 700; }
	.user-content .form-control { box-shadow: none; height:40px; }
	.user-content .btn {
		border-radius:8px;
		margin-top:6px;
	}
	
</style>
<script>
    $(function () {
        $('.js_account_action_sub_list li').each(function( key, value ) {
            if($(this).hasClass('active')) {
                $(this).closest('li.js_account_action_item').addClass('active');
            }
        });
    })
</script>