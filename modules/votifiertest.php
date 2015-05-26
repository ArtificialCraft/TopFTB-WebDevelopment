<?php
	if(isset($_POST['public_key'])){
		$vote = new Vote($_POST['votifier_ip'], $_POST['votifier_port'], $_POST['mc_username'], $_POST['public_key']);
		if($vote->sendPacket()){
			echo "SUCCCCCCESSSSSSSSSS";
		}else{
			echo "FAILIURRRRRRRRRREEEEEEEEEEEEEEE";
		}
	}
?>
<script language="JavaScript" type="text/javascript">


function checkform ( form )
{
 
  	if(form.votifier_ip.value == "") {
	    alert( "Please enter the votifier IP." );
	    form.votifier_ip.focus();
    	return false ;
	}
  	if(form.votifier_port.value == "") {
	    alert( "Please enter the votifier port." );
	    form.votifier_port.focus();
    	return false ;
	}
	if(form.public_key.value == "") {
	    alert( "Please enter your public key for votifier." );
	    form.public_key.focus();
    	return false ;
	}

	if(form.mc_username.value == "") {
	    alert( "Please enter your Minecraft username." );
	    form.mc_username.focus();
    	return false ;
	}
	
	if (form.mc_username.value != "") 
    {
		var userRegEx = /^[a-zA-Z0-9\_]+$/;
		if (form.mc_username.value.search(userRegEx) == -1) 
		{
		    alert( "Use only letters, numbers and underscore for your minecraft username." );
	    	form.mc_username.focus();
		    return false ;
		}
	}
	
  
  if (form.security_code.value == "") {
    alert('Enter the security code.');
	form.security_code.focus();
    return false;
  }

  return true ;
}
</script>
<form id="form" action="" method="post" name="form" onsubmit="return checkform(this);">
    <table>
        <tbody><tr>
          <td width="100px">Votifier IP</td>
          <td align="left"><input type="text" name="votifier_ip" size="30" value="playac.ca"> <i> Usually is same ip as the server.</i>
          </td>
        </tr>
    
        <tr>
          <td>Votifier Port<br></td>
          <td align="left"><input type="text" name="votifier_port" size="10" value="8192"><i> Usually the port is 8192</i>
          </td>
        </tr>
    
        <tr>
          <td>Public Key</td>
          <td align="left"><textarea style="text-align:justify; width:500px" cols="80" rows="5" name="public_key">MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAqevfEDGJLOMDDl2aWIHwq57TBCCWabIu7p6NSnwlUW7iO0qGnJWScpmXio5HL9xINWfuyJg0IK0c13Mv1KYUxIwi7o0b1NmFQ2bQgK+jMFjkyVxQ6rn4PHnddUbztv7SFKZvGtXCKHVhgsatGLeuR1mHpPhV4fvlHlDLCwMhKSDytGBoSpx4SeLrOIuPUyBw9PytQjnP6osnz3u1Yp+R1ILanfnjxjK6D42iKRkxQfi2FtLMIM0ceDlYWAtLZpQsKLKZ3bnRiH2ndcvvB0m9VfyAM9aagefunA+gOzOWre9gUgKHSUg8Fds1sCxNSQfsePLTcagtE77euA+3cefouwIDAQAB</textarea>
          </td>
        </tr>
    
      <tr>
          <td>Username</td>
          <td align="left"><input type="text" name="mc_username" size="50" value="AlienArtificial" placeholder="Minecraft username"><i> Your Minecraft username.</i>
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>
              <button class="btn btn-large btn-inverse" type="submit" name="submit">Send Test</button><br>
               
          </td>
        </tr>
        </tbody>
    </table>
</form>