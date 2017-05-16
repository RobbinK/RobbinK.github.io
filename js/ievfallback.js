// IE Validation Fallback
function IE()
{
	if (navigator.appName == 'Microsoft Internet Explorer')
	{
		function validateNewTicketForm()
		{
			// New Ticket Form
			var email=document.forms["loginForm"]["email"].value;
			var atpos=email.indexOf("@");
			var dotpos=email.lastIndexOf(".");
			
			if (email==null || email=="" || atpos<1 || dotpos<atpos+2 || dotpos+2>=email.length)
			{
				document.getElementById('error').innerHTML = "Write your Email";
			    return false;
			}
			
			var fname=document.forms["loginForm"]["password"].value;
			if (fname==null || fname=="")
			{
				document.getElementById('error').innerHTML = "Write your Password";
			    return false;
			}

		}
		return validateNewTicketForm();
	 }
	 
}


function IE1()
{
	if (navigator.appName == 'Microsoft Internet Explorer')
	{
		function validateNewTicketForm()
		{
			// New Ticket Form
			var email=document.forms["newTicket"]["email"].value;
			var atpos=email.indexOf("@");
			var dotpos=email.lastIndexOf(".");
			
			if (email==null || email=="" || atpos<1 || dotpos<atpos+2 || dotpos+2>=email.length)
			{
				document.getElementById('error').innerHTML = "Write your Email";
			    return false;
			}
			
			var fname=document.forms["newTicket"]["name"].value;
			if (fname==null || fname=="")
			{
				document.getElementById('error').innerHTML = "Write your First Name";
			    return false;
			}
			
			var lname=document.forms["newTicket"]["lname"].value;
			if (lname==null || lname=="")
			{
				document.getElementById('error').innerHTML = "Write your Last Name";
			    return false;
			}
			
			var message=document.forms["newTicket"]["message"].value;
			if (message==null || message=="")
			{
				document.getElementById('error').innerHTML = "Write your message";
			    return false;
			}
			
			var capcha=document.forms["newTicket"]["captcha_code"].value;
			if (capcha==null || capcha=="")
			{
				document.getElementById('error').innerHTML = "Enter Capcha Code";
			    return false;
			}

		}
		return validateNewTicketForm();
	 }
	 
}

function IE2()
{
	if (navigator.appName == 'Microsoft Internet Explorer')
	{
		function validateAdministratorForm()
		{
			// Administrator Form
			var email=document.forms["administrator"]["email"].value;
			var atpos=email.indexOf("@");
			var dotpos=email.lastIndexOf(".");
			
			if (email==null || email=="" || atpos<1 || dotpos<atpos+2 || dotpos+2>=email.length)
			{
				document.getElementById('error2').innerHTML = "Write your Email";
			    return false;
			}
			
			var fname=document.forms["administrator"]["name"].value;
			if (fname==null || fname=="")
			{
				document.getElementById('error2').innerHTML = "Write your First Name";
			    return false;
			}
			
			var lname=document.forms["administrator"]["lname"].value;
			if (lname==null || lname=="")
			{
				document.getElementById('error2').innerHTML = "Write your Last Name";
			    return false;
			}
			
			var password=document.forms["administrator"]["password"].value;
			if (password==null || password=="")
			{
				document.getElementById('error2').innerHTML = "Enter your Password";
			    return false;
			}
			
		}
		return validateAdministratorForm();
	 }
	 
}

function IE3()
{
	if (navigator.appName == 'Microsoft Internet Explorer')
	{
		function validateUserProfileForm()
		{
			// User Profile Form
			var email=document.forms["userProfile"]["email"].value;
			var atpos=email.indexOf("@");
			var dotpos=email.lastIndexOf(".");
			
			if (email==null || email=="" || atpos<1 || dotpos<atpos+2 || dotpos+2>=email.length)
			{
				document.getElementById('error3').innerHTML = "Write your Email";
			    return false;
			}
			
			var fname=document.forms["userProfile"]["name"].value;
			if (fname==null || fname=="")
			{
				document.getElementById('error3').innerHTML = "Write your First Name";
			    return false;
			}
			
			var lname=document.forms["userProfile"]["lname"].value;
			if (lname==null || lname=="")
			{
				document.getElementById('error3').innerHTML = "Write your Last Name";
			    return false;
			}
			
		}
		return validateUserProfileForm();
	 }
	 
}

function IE4()
{
	if (navigator.appName == 'Microsoft Internet Explorer')
	{
		function validateAdminitratorProfileForm()
		{
			// Administrator Profile Form
			var email=document.forms["adminitratorProfile"]["email"].value;
			var atpos=email.indexOf("@");
			var dotpos=email.lastIndexOf(".");
			
			if (email==null || email=="" || atpos<1 || dotpos<atpos+2 || dotpos+2>=email.length)
			{
				document.getElementById('error4').innerHTML = "Write your Email";
			    return false;
			}
			
			var fname=document.forms["adminitratorProfile"]["name"].value;
			if (fname==null || fname=="")
			{
				document.getElementById('error4').innerHTML = "Write your First Name";
			    return false;
			}
			
			var lname=document.forms["adminitratorProfile"]["lname"].value;
			if (lname==null || lname=="")
			{
				document.getElementById('error4').innerHTML = "Write your Last Name";
			    return false;
			}
			
		}
		return validateAdminitratorProfileForm();
	 }
	 
}

function checkAnswer()
{
	if (navigator.appName == 'Microsoft Internet Explorer')
	{
		function validateAdminitratorProfileForm()
		{
			var message = document.forms["answerForm"]["message"].value;
			if (message==null || message=="")
			{
				document.getElementById('answerError').innerHTML = "Please write your message";
			    return false;
			}
			else
			{
				return true;
			}	
		}
		return validateAdminitratorProfileForm();
	 }
}