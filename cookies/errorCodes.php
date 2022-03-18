<?php
	
	//this is a way to centralize all error messages (you can do it in multiple languages adding one more parameter to the function)
	function getErrorMessage($errorCode){
		
		$errorCode = trim($errorCode);
		
		//check if error code is not numeric
		if ( !is_numeric($errorCode) ){
			//well, someone is trying to use this function without regard by the rules. Return a false.
			return(false);
		}
		
		//pick the right error message
		switch( $errorCode ) {
			
			case 1: //user tried to access a no-go zone!
					  return ("<div style=color:red> You need to authenticate before going there. Thank you!</div>");
					  break;
					  
			case 2: //user logout with success
					  return ("<div style=color:green> Logout successful. Thank you! </div>");
					  break;
					  
		   case 3: //user update with error
					  return ("<div style=color:red> Operation not allowed. Please try again later! </div>");
					  break;
					  
		   case 4: //user update successful
					  return ("<div style=color:green> User data updated. Thank you! </div>");
					  break;

					  
		   default: //error code not found. Return unknown error message.
		   			return("Unknown internal error! Please try again later.");
		   			break;
		}
	}
?>