<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <style>
            body
            {
                background-color: #f7f7f7;
            }
            *{
                font-family: tahoma;
                font-size: 11px;
            }
            fieldset
            {
                background-color:  white;
            }
        </style>
    </head>
    <body>
        <fieldset style="width: 400px;margin: 100px auto 0px auto; text-align: center;padding: 20px;">
            <strong>404.</strong> That's an error.
            <br/><br/>
            The requested URL  <b><?= $_SERVER['REQUEST_URI'] ?></b> was not found on this server.
        </fieldset>
    </body>
</html>
