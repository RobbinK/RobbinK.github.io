<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" /> 
        <title>ArcadeBooster Updating system</title> 
        <link href="<?= static_url() ?>/images/favicon.png" rel="shortcut icon" type="image/x-icon" />
        <style>
            *{ font-family: Arial;}
            body{
                margin: 0;
                padding: 0;
                background-color:  White; 
                font: 14px arial;
                background-image: url('<?= static_url() ?>/images/bg_e.png');
            }   
            #wrapper{  
            } 
            #container {
                margin: 73px auto;
                width: 800px;
            }
            #nav{ 
                width: 100%;
                top: 0px;
                position: absolute;
                left: 0px;
                background: #363531; 
                padding: 9px 0;
            }
            #nav img{ margin-left: 10px;}
            fieldset {
                font: 12px arial;
                line-height: 22px;
                border-radius: 7px;
                border: solid 1px #CACACA;
                min-height: 70px;
                margin-bottom: 20px;
                background: #FFF;
                padding-top: 13px;
            } 

            legend{
                background: #FFD356;
                border-radius: 10px;
            }

            .notify{ 
                font-size: 12px;
                border-radius: 3px;
                background: #F2F2F2;
                display: block;
                padding: 10px;
                margin-bottom: 2px;
                font-weight: bold;
            }
            .success{
                color: #008000; 
            }
            .error{
                color: #D51E00;
            }
            .info{
                color:#407FCA; 
            }
        </style>
    </head>
    <body>
        <div id="wrapper">
            <div id="nav">
                <a href="http://www.arcadebooster.com" target="_blank"><img src="<?= static_url() ?>/images/ab_logo.png" style="border: none"></a>
                <span style="font-style: italic;font-size: 10px;color: #EBEBEB;"> ArcadeBooster Updating system</span> 
            </div>
            <div id="container">  
                <fieldset> <legend>  &nbsp;&nbsp;&nbsp;  System Messages  &nbsp;&nbsp;&nbsp;  </legend>
                    <?php if ($messages) echo join("\n",$messages); ?> 
                </fieldset>
            </div> 
        </div>
    </body>
</html>