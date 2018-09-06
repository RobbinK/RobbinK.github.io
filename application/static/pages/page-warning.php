<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>ArcadeBooster - Internal Error</title>
    <style type="text/css">
        body {
            background: #efefef;
            color: #000;
            font-family: Tahoma, Verdana, Arial, Sans-Serif;
            font-size: 12px;
            text-align: center;
            line-height: 1.4;
        }

        a:link {
            color: #026CB1;
            text-decoration: none;
        }

        a:visited {
            color: #026CB1;
            text-decoration: none;
        }

        a:hover, a:active {
            color: #000;
            text-decoration: underline;
        }

        #container {
            width: 900px;
            padding: 20px;
            background: #fff;
            border: 1px solid #e4e4e4;
            margin: 70px auto;
            text-align: left;
            -moz-border-radius: 6px;
            -webkit-border-radius: 6px;
            border-radius: 6px;
        }

        h1 {
            margin: 0;
            background: url('<?= static_url() ?>/images/ab_logo.png') no-repeat;
            height: 40px;
            width: 248px;
        }

        #content {
            border: 1px solid #151515;
            background: #fff;
            -moz-border-radius: 3px;
            -webkit-border-radius: 3px;
            border-radius: 3px;
        }

        h2 {
            font-size: 12px;
            padding: 4px;
            background: #FF523F;
            color: #fff;
            margin: 0;
        }

        .invisible {
            display: none;
        }

        #detail {
            padding: 15px;
        }

        #footer {
            font-size: 12px;
            border-top: 1px dotted #DDDDDD;
            padding-top: 10px;
        }

        dt {
            font-weight: bold;
        }
    </style>
</head>
<body>
<div id="container">
    <div id="logo">
        <h1><a href="http://www.arcadebooster.com" title="ArcadeBooster"><span
                    class="invisible">ArcadeBooster</span></a></h1>
    </div>
    <div id="content">
        <h2><?= @$title ?></h2>

        <div id="detail">
            <?= @$detail ?>
        </div>
    </div>
</div>
<?php
if (strpos($title, 'Connection error') !== false && file_exists(root_path().'/install')) :
    ?>
    <script>
        function payline_referrer() {
            payline_connecting_message();
            // Your application has indicated there's an error
            window.setTimeout(function () {
                // Move to a new location or you can do something else
                window.location.href = "<?=root_url().'/install'?>";
            }, 5000);
        }

        function payline_connecting_message() {
 window.setTimeout(function () {
            var dark = document.createElement("div");
            dark.setAttribute('style', 'background: none repeat scroll 0 0 black;height: 100%;width:100%;left: 0;opacity: 0.2;position: absolute;top: 0;');
            var el = document.createElement("span");
            el.setAttribute('style', 'border-radius: 3px; position: fixed; top: 40%; left: 41%;width:150px;direction:rtl; margin: 2px; padding: 20px; background: none repeat scroll 0% 0% white; opacity: 1;');
            el.innerHTML = '<b>Redirecting to Installation</b>';
            document.body.appendChild(dark);
            document.body.appendChild(el);
 }, 3000);
        }

        window.onload = payline_referrer;
    </script>
    <?php
endif;
?>
</body>
</html>