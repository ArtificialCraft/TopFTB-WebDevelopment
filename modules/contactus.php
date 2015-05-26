<?php

if(isset($_POST['name'])){
	$to = "anmolmago@hotmail.com";
	$subject = "Submitted from contactus.";
	$thanks = mail($to,$subject,wordwrap($_POST['message'], 70, "\r\n"),"From: ".$_POST['email']);
}

?>
<div id="contactus">
    <form class="topform" name="contact" action="" method="post">
    	<div class="formshow">
            <div>
           		<p>Your Name:</p> 
                <input name="name" type="text" placeholder="John Doe" size="19" required="required"/>
            </div>
            <div>
           		<p>Your Email:</p> 
                <input name="email" type="text" placeholder="johnd@example.com" size="19" required="required"/>
            </div>
            <div class="big">
            	<p>Message: </p> 
                <div>
                	<textarea name="message" maxlength="500" placeholder="I love you! <3" required="required"></textarea>
            	</div>
            </div>
            <div class="submit">
            	<input type="submit" value="SUBMIT"/>
            </div>
        </div>
    </form>
</div>