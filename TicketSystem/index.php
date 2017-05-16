<?php
include 'admin/userLogin.php';
include 'html_config.php';
if ($isLogin)
{
	$_key = $_COOKIE['login']; 
	$userinfo = $Login->getUserInfo($_key);
	$userinfo = json_decode($userinfo);
	if($userinfo->role == "user")
	{
		include ('header.php');
		include ('menu.php');
		include ('content.php');
		include ('footer.php');
	}
	else 
	{
		echo $head;
		echo $startBody;
		echo '   <div class="navbar navbar-inverse" style="position: static;">
              <div class="navbar-inner">
                <div class="container">
                  <a class="btn btn-navbar" data-toggle="collapse" data-target=".subnav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </a>
                  <a class="brand" href=".">'.SITE_TITLE.'</a>
                  <div class="nav-collapse subnav-collapse">
                    <ul class="nav">
                      <li class="divider-vertical"></li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Send A New Ticket <b class="caret"></b></a>
                       <ul class="dropdown-menu paddingLR meDrop">
                          <li class="myForm pull-left">
                          	<p></p>
                            <form class="form-inline navbar-search" action="newTicket.php" method="post" name="newTicket" onsubmit="return IE1();">
                           
                                            <div class="control-group paddingLR">
                                                <label class="control-label" for="">Email</label>
                                                    <div>
                                                        <input class="meIn" placeholder="Email" name="email" type="email" required="required">
                                                    </div>
                                            </div>
                                      
                                            <div class="control-group paddingLR">
                                                <label class="control-label" for="">Name</label>
                                                    <div>
                                                        <input class="meIn" placeholder="Name" name="name" type="text" required="required">
                                                    </div>
                                            </div>
                                        
                                            <div class="control-group paddingLR">
                                                <label class="control-label" for="">Lastname</label>
                                                    <div class="">
                                                         <input class="meIn" placeholder="Lastname" name="lname" type="text" required="required">
                                                    </div>
                                             </div>
                                       		<div class="meFloat">
                                            <div class="control-group paddingLR">
                                                <label class="control-label" for="">Department</label>
                                                    <div class="">
                                                         <select name="department" class="meIn meWidth">';
	$get_option = file_get_contents("admin/moderatorRoles.txt");
	$exp_get_option = explode("\n", $get_option);
	foreach ($exp_get_option as $value)
	{
		echo '<option>'.trim($value).'</option>';
	}
	
	echo '                                                             
                                                         </select>
                                                    </div>   
                                            </div>
                                       
                                             <div class="control-group paddingLR">
                                                 <label class="control-label" for="inputPassword">Priority</label>
                                                     <div class="">
                                                         <select name="priorety" class="meIn meWidth">
                                                             <option>Low</option>
                                                             <option>Normal</option>
                                                             <option>High</option>
                                                             <option>Very high</option>
                                                         </select>
                                                     </div>
                                            </div>
                                        
                                            <div class="control-group paddingLR">
                                                <label class="control-label" for="inputPassword">Subject</label>
                                                     <div class="">
                                                          <input class="meIn" placeholder="Subject" name="subject"  type="text" required="required">
                                                     </div>
                                            </div>
                                            </div>
                                     
                                            <div class="control-group paddingLR">
                                                <label class="control-label" for="">Message</label>
                                                     <div class="">
                                                          <textarea class="myTextarea input-block-level meIn" rows="5" name="message" placeholder="Message" required="required"></textarea>
                                                     </div>
                                            </div>
                                            
                                      
                                            <div class="control-group">
                                                <div class="paddingLR">
                                                     <button type="submit" name="sendNewTicket" class="btn">Send</button>
                                                </div>
                                            </div>
                                            
                                            <div class="meFloat2" style="padding-top: 20px;">
                                            <div class="control-group paddingLR"><!-- CAPTCHA -->
                                      		<label class="control-label" for="">CAPTCHA</label>
                                            <div class="">
                                             <img id="captcha" src="securimage/securimage_show.php" alt="CAPTCHA Image"  /><br/><br/>
											 <input class="input-block-level meIn"  type="text" required="required" name="captcha_code" size="10" maxlength="6" />
											 <a href="#" onclick="return new_captcha();" style="padding-left: 0px;">[ Different Image ]</a>
                                            </div>
                                             </div>
                                              <span class="pull-right" id="error" style="color: #F33; padding-top:10px;"></span>
                                             </div>
                            </form>
                            <p></p>	
                          </li>
                        </ul>
                      </li>
                    </ul>
                    <div class="pull-right">
                     <form class="navbar-search" action="login.php" method="post" name="loginForm" onsubmit="return IE();">
                         <input class="span3 input-block-level search-query" placeholder="Email" name="email" type="email" required="required" onfocus="if(this.value == \'Email\') { this.value = \'\'; }" onblur="if (this.value == \'\') { this.value=\'Email\'; }">
                         <input class="span3 input-block-level search-query" placeholder="Password" name="password" type="password" required="required" onfocus="if(this.value == \'Password\') { this.value = \'\'; }" onblur="if (this.value == \'\') { this.value=\'Password\'; }" >
                         <button class="btn btn-success btn-large" style="margin-top:-1px;" type="submit" name="login" class="btn">login</button>
                     </form>
                    </div>
                  </div><!-- /.nav-collapse -->
                </div>
              </div><!-- /navbar-inner -->
            </div><!-- /navbar -->
            
     <article class="container" style="margin-top: -18px;">
        <section class="well well-small mePadd">
        <h2 class="meFont"><span class="icon-check meMarg"></span> '.DESCRIPTION_TITLE.'</h2>
		      <p style="padding-left: 10px; padding-right:10px;">'.DESCRIPTION.'</p>
        </section><!--Description Ends --> 
     </article>   ';
	echo '<hr style="margin-top:0px;">
		<footer class="pager" style="bottom:0px;">
		        <p>'.FOOTER.'</p>
		</footer>';
		echo $endBody;
	}
	
}
else 
{
	echo $head;
	echo $startBody;
	echo '   <div class="navbar navbar-inverse" style="position: static;">
              <div class="navbar-inner">
                <div class="container">
                  <a class="btn btn-navbar" data-toggle="collapse" data-target=".subnav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </a>
                  <a class="brand" href=".">'.SITE_TITLE.'</a>
                  <div class="nav-collapse subnav-collapse">
                    <ul class="nav">
                      <li class="divider-vertical"></li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Send A New Ticket <b class="caret"></b></a>
                        <ul class="dropdown-menu paddingLR meDrop">
                          <li class="myForm pull-left">
                          	<p></p>
                            <form class="form-inline navbar-search" action="newTicket.php" method="post" name="newTicket" onsubmit="return IE1();">
                           
                                            <div class="control-group paddingLR">
                                                <label class="control-label" for="">Email</label>
                                                    <div>
                                                        <input class="meIn" placeholder="Email" name="email" type="email" required="required">
                                                    </div>
                                            </div>
                                      
                                            <div class="control-group paddingLR">
                                                <label class="control-label" for="">Name</label>
                                                    <div>
                                                        <input class="meIn" placeholder="Name" name="name" type="text" required="required">
                                                    </div>
                                            </div>
                                        
                                            <div class="control-group paddingLR">
                                                <label class="control-label" for="">Lastname</label>
                                                    <div class="">
                                                         <input class="meIn" placeholder="Lastname" name="lname" type="text" required="required">
                                                    </div>
                                             </div>
                                       		<div class="meFloat">
                                            <div class="control-group paddingLR">
                                                <label class="control-label" for="">Department</label>
                                                    <div class="">
                                                         <select name="department" class="meIn meWidth">';
	$get_option = file_get_contents("admin/moderatorRoles.txt");
	$exp_get_option = explode("\n", $get_option);
	foreach ($exp_get_option as $value)
	{
		echo '<option>'.trim($value).'</option>';
	}
	
	echo '                                                             
                                                         </select>
                                                    </div>   
                                            </div>
                                       
                                             <div class="control-group paddingLR">
                                                 <label class="control-label" for="inputPassword">Priority</label>
                                                     <div class="">
                                                         <select name="priorety" class="meIn meWidth">
                                                             <option>Low</option>
                                                             <option>Normal</option>
                                                             <option>High</option>
                                                             <option>Very high</option>
                                                         </select>
                                                     </div>
                                            </div>
                                        
                                            <div class="control-group paddingLR">
                                                <label class="control-label" for="inputPassword">Subject</label>
                                                     <div class="">
                                                          <input class="meIn" placeholder="Subject" name="subject"  type="text" required="required">
                                                     </div>
                                            </div>
                                            </div>
                                     
                                            <div class="control-group paddingLR">
                                                <label class="control-label" for="">Message</label>
                                                     <div class="">
                                                          <textarea class="myTextarea input-block-level meIn" rows="5" name="message" placeholder="Message" required="required"></textarea>
                                                     </div>
                                            </div>
                                            
                                      
                                            <div class="control-group">
                                                <div class="paddingLR">
                                                     <button type="submit" name="sendNewTicket" class="btn">Send</button>
                                                </div>
                                            </div>
                                            
                                            <div class="meFloat2" style="padding-top: 20px;">
                                            <div class="control-group paddingLR"><!-- CAPTCHA -->
                                      		<label class="control-label" for="">CAPTCHA</label>
                                            <div class="">
                                             <img id="captcha" src="securimage/securimage_show.php" alt="CAPTCHA Image"  /><br/><br/>
											 <input class="input-block-level meIn"  type="text" required="required" name="captcha_code" size="10" maxlength="6" />
											 <a href="#" onclick="return new_captcha();" style="padding-left: 0px;">[ Different Image ]</a>
                                            </div>
                                             </div>
                                              <span class="pull-right" id="error" style="color: #F33; padding-top:10px;"></span>
                                             </div>
                            </form>
                            <p></p>	
                          </li>
                        </ul>
                      </li>
                    </ul>
                    <div class="pull-right">
                     <form class="navbar-search" action="login.php" method="post" name="loginForm">
                         <input class="span3 input-block-level search-query" placeholder="Email" name="email" type="email" required="required" onfocus="if(this.value == \'Email\') { this.value = \'\'; }" onblur="if (this.value == \'\') { this.value=\'Email\'; }" value="Email">
                         <input class="span3 input-block-level search-query" placeholder="Password" name="password" type="password" required="required" onfocus="if(this.value == \'Password\') { this.value = \'\'; }" onblur="if (this.value == \'\') { this.value=\'Password\'; }" value="Password">
                         <button class="btn btn-success btn-large" style="margin-top:-1px;" type="submit" name="login" class="btn">login</button>
                    </form>
                    </div>
                  </div><!-- /.nav-collapse -->
                </div>
              </div><!-- /navbar-inner -->
            </div><!-- /navbar -->
            
     <article class="container" style="margin-top: -18px;">
        <section class="well well-small mePadd">
        <h2 class="meFont"><span class="icon-check meMarg"></span> '.DESCRIPTION_TITLE.'</h2>
		      <p style="padding-left: 10px; padding-right:10px;">'.DESCRIPTION.'</p>
        </section><!--Description Ends --> 
     </article>   ';
	echo '<hr style="margin-top:0px;">
		<footer class="pager" style="bottom:0px;">
		        <p>'.FOOTER.'</p>
		</footer>';
	echo $endBody;
}
?>