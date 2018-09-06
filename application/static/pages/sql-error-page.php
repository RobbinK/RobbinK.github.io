<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>ArcadeBooster - Internal Error</title>
        <style type="text/css">
            body { background: #efefef; color: #000; font-family: Tahoma,Verdana,Arial,Sans-Serif; font-size: 12px; text-align: center; line-height: 1.4; }
            a:link { color: #026CB1; text-decoration: none;	}
            a:visited {	color: #026CB1;	text-decoration: none; }
            a:hover, a:active {	color: #000; text-decoration: underline; }
            #container { width: 600px; padding: 20px; background: #fff;	border: 1px solid #e4e4e4; margin: 70px auto; text-align: left; -moz-border-radius: 6px; -webkit-border-radius: 6px; border-radius: 6px; }
            h1 { margin: 0; background: url('<?= static_url() ?>/images/ab_logo.png') no-repeat; height: 40px; width: 248px; }
            #content { border:1px solid #151515; background: #fff; -moz-border-radius: 3px; -webkit-border-radius: 3px; border-radius: 3px; }
            h2 { font-size: 12px; padding: 4px; background: #FFA43F; color: #fff; margin: 0; }
            .invisible { display: none; }
            #error { padding: 6px; }
            #footer { font-size: 12px; border-top: 1px dotted #DDDDDD; padding-top: 10px; }
            dt { font-weight: bold; }
        </style>
    </head>
    <body>
        <div id="container">
            <div id="logo"> 
                <h1><a href="http://www.arcadebooster.com" title="ArcadeBooster"><span class="invisible">ArcadeBooster</span></a></h1>
            </div> 
            <div id="content">
                <h2>ArcadeBooster SQL Error</h2>

                <div id="error">
                    <p>ArcadeBooster has experienced an internal SQL error and cannot continue.</p><dl>
                        <dt>SQL Error:</dt>
                        <dd><?= @$sqlError ?></dd>
                        <dt>Query:</dt>
                        <dd style="color:#D96464"><?= @$sql ?></dd>
                    </dl>

                    <p id="footer">Please contact the <a href="http://www.arcadebooster.com/contactus.html">ArcadeBooster Group</a> for technical support.</p>
                </div>
            </div>
        </div>
    </body>
</html>