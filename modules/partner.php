<?php 

if(isset($_POST['name'])){
	$to = "anmolmago@hotmail.com";
	$subject = "Partnership request from ".$_POST['name']."!";
	$message = "Company".$_POST['name'].'\r\n'.$_POST['desc'].'\r\n\r\n\r\nMessage:\r\n'.$_POST['message'];
	$thanks = mail($to,$subject,wordwrap($message, 70, "\r\n"),"From: ".$_POST['email']);
}

?>
<div id="partner">
	<form class="topform" name="partner" action="" method="post">
    	<div class="formshow">
            <div>
           		<p>Company:</p> 
                <input name="name" type="text" placeholder="Acme" size="21"/>
            </div>
            <div>
           		<p>Your Email:</p> 
                <input name="email" type="text" placeholder="johnd@example.com" size="19"/>
            </div>
            <div class="big">
           		<p>Brief description of Company:</p>
                <div><textarea name="desc" placeholder="What product or service does your company provide?"></textarea></div>
            </div>
            <div class="big">
            	<p>Message: </p> 
                <div>
                	<textarea name="message" maxlength="1000" placeholder="Why do you want a partnership? Please include what you are willing to offer (donations, your services, etc.) and what you expect from us (advertisements, sponsorships, etc.)!"></textarea>
            	</div>
            </div>
            <div class="submit">
            	<input type="submit" value="SUBMIT"/>
            </div>
        </div>
    </form>
</div>