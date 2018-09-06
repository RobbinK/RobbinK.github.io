<body style="margin: 0;padding: 0;">
<div id="wrapper" style="margin: 0;padding: 0;float: left;width: 100%;color: #666666;font-family: 'Montserrat',sans-serif;font-size: 14px;">
<div id="header" style="margin: 0;padding: 0;float: left;width: 100%;height: 90px;background-color:#2a6490;text-align:center">
<h1 style="color:#fff">Reset Password</h1>
</div>
<div id="main" style="margin: 0;padding: 40px 0;float: left;width: 100%;background-color: #f5f5f5;text-align: center;">
<h1 style="margin: 0;padding: 0;font-size: 24px;font-weight: 400;float: left;clear: both;width: 100%;color: #2d8fa7;letter-spacing: 1px;text-transform: uppercase;text-align: center;">Recover password</h1>
<p class="before_call" style="margin:35px 0 25px 0;padding: 0;color: #666666;"><span style="color: #666666;">Hello {$name}, please visit the following link to reset your password.</span></p>
<a href="{$url}/help/password/recover/{$key}" target="_blank" class="btn calltoaction" style="outline: none;font-family: 'Montserrat', sans-serif;font-weight: 400;text-decoration: none;padding: 13px 30px;background-color: #EE2B7B;color: #ffffff;font-size: 14px;text-transform: uppercase;letter-spacing: 2px;border-radius: 25px;display:inline-block">Recover Password</a>
<p class="after_call" style="margin:30px 0;padding: 0;color: #666666;"><span style="color: #666666;">You can also click this link</span><br><a href="{$url}/help/password/recover/{$key}" target="_blank" class="mainlink" style="outline: none;font-family: 'Montserrat', sans-serif;font-weight: 400;text-decoration: none;color: #EE2B7B;">{$url}/help/password/recover/{$key}</a><br/><br/><span style="color: #666666;">Please be sure to copy the entire link into your browser. The link will expire after 1 day for security reasons.</span><br/><br/><span style="color: #666666;">If you did not request this forgotten password email, no action is needed, your password will not be reset as long as the link above is not visited.</span></p>
</div>
</div>
</body>