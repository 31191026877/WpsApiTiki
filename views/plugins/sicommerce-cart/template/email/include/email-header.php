<?php
/**
 * Email Header
 * @version 1.8
 */
$ci =& get_instance();
?>
<!DOCTYPE html>
<html lang="<?php echo $ci->language['current'];?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
		<title><?php echo Option::get('general_label'); ?></title>
	</head>
	<body marginwidth="0" topmargin="0" marginheight="0" offset="0">
		<div id="template-email">
			<div style="width: 100%; background-color:#F3F3F3; padding:10px;font-family: Arial, Helvetica, sans-serif; line-height: 25px; font-size: 13px;">
				<div style="width: 600px; margin: 0 auto;">
                    <div style="overflow: hidden;background-color: #fff; padding:30px 10px; width: 100%;">
                        <div style="text-align: center;">
                            <img src="<?php echo Url::base().Template::imgLink(Option::get('logo_header'));?>">
                        </div>
                    </div>