<!DOCTYPE html>
<html lang="en">
<head>
<title>Error</title>
<style type="text/css">

::selection{ background-color: #E13300; color: white; }
::moz-selection{ background-color: #E13300; color: white; }
::webkit-selection{ background-color: #E13300; color: white; }

body {
	background-color: #fff;
	margin: 40px;
	font: 13px/20px normal Helvetica, Arial, sans-serif;
	color: #4F5155;
}

a {
	color: #003399;
	background-color: transparent;
	font-weight: normal;
}

h1 {
	color: #444;
	background-color: transparent;
	border-bottom: 1px solid #D0D0D0;
	font-size: 19px;
	font-weight: normal;
	margin: 0 0 14px 0;
	padding: 14px 15px 10px 15px;
}

code {
	font-family: Consolas, Monaco, Courier New, Courier, monospace;
	font-size: 12px;
	background-color: #f9f9f9;
	border: 1px solid #D0D0D0;
	color: #002166;
	display: block;
	margin: 14px 0 14px 0;
	padding: 12px 10px 12px 10px;
}

#container {
	margin: 10px;
	padding:10px;
	border: 1px solid #D0D0D0;
	-webkit-box-shadow: 0 0 8px #D0D0D0;
}

p {
	margin: 0;
}
.header {
	overflow: hidden;
}
.box {
	border:1px solid #ccc;
	background-color: #000;
	color:#fff;
	padding:10px;
	overflow: hidden;
}

.box img  { max-width: 100%; }
</style>
</head>
<body>
	<div id="container">
		<!-- <div class="header">
			<div style=""><h1>Công Ty Truyền Thông Và Quảng Cáo Siêu Kinh Doanh</h1></div>
		</div> -->
		<div class="box">
			<div style="float:left; width:100px;">
				<img src="https://cdn4.iconfinder.com/data/icons/flat-seo-icons/48/web-crawler-512.png">
			</div>
			<div style="float:left; width:calc(100% - 110px); padding-left:10px;">
				<h3><?php echo $heading; ?></h3>
				<?php echo $message; ?>
			</div>
			
		</div>
	</div>
</body>
</html>