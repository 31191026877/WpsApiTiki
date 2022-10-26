<?php 
    $domain =  base_url();
    $domain = str_replace('http://','',$domain);
    $domain = str_replace('https://','',$domain);
    $domain = str_replace('www.','',$domain);
    $domain = trim($domain, '/');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo Option::get('cms_close_title');?></title>
</head>
<body>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100%2C100italic%2C300%2C300italic%2C400%2Citalic%2C500%2C500italic%2C700%2C700italic%2C900%2C900italic|Roboto+Slab:100%2C300%2C400%2C700&subset=greek-ext%2Cgreek%2Ccyrillic-ext%2Clatin-ext%2Clatin%2Cvietnamese%2Ccyrillic">
    <link rel="stylesheet" href="views/backend/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="scripts/font-awesome/css/all.min.css">
    <script type="text/javascript" src="views/backend/assets/js/jquery.js" charset="utf-8"></script>

    <div class="container">
        <div class="row">
            <div class="col-md-12 notice-box">
                <h1><?php echo $domain;?></h1>
                <p class="notice-expire"><?php echo Option::get('cms_close_title');?></p>
                <div class="notice-contact">
                    <span><?php echo Option::get('cms_close_content');?></span>
                </div>
                <div class="text-center"><img src="https://www.vietnamconsulate-shihanoukville.org/wp-content/uploads/2018/11/ba%CC%89o-tri%CC%80-website.png" alt=""></div>
            </div>
        </div>
    </div>

    <style>
        body {
            background-color:#F0F5FF;
        }
        .notice-box {
            margin-top: 50px;
            text-align: center;
            margin-bottom: 25px;
        }
        .notice-box h1 {
            font-size: 44px;
            color: #6a79a8;
        }
        .notice-expire {
            text-transform: uppercase;
            font-size: 36px;
            color: #3c3c3c;
            font-weight: bold;
        }
        .notice-box span {
            background-color: #def3f8;
            color: #6a79a8;
            padding: 15px 0px;
            font-size: 17px;
            border-radius: 35px;
            display: block;
            width: 65%;
            margin: 0 auto;
            font-weight: bold;
        }
        .notice-box img {
            max-width:100%;
            height:300px;
        }
    </style>
    <script type="text/javascript" src="views/backend/assets/js/bootstrap.min.js" charset="utf-8"></script>
</body>
</html>