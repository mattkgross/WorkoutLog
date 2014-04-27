Tests
=============

###User Creation

* Single user creation test: 
  * All fields left blank -- Sign Up button pressed. UI Warning Message thrown(first name). 
  * First Name Field only -- Sign Up button pressed. UI Warning Message thrown(last name). 
  * First/Last/Username -- Sign Up button pressed. UI Warning message thrown(email). 
  * First/Last/Username/Valid Email -- Sign Up button pressed. UI Warning message thrown(password). 
  * First/Last/Username/Valid Email/Password -- Sign Up button pressed. UI Warning message (confirm password)
  * First/Last/Username/Valid Email/Password/ConfirmPassword(wrong) -- Sign Up button pressed. UI Warning message (confirm password) 
  * First/Last/Username/Valid Email/Password/ConfirmPassword -- Sign Up button pressed. Success. 
  * Confirmed user exists in users table. 

###User Login
* All fields blank -- Log in button presseed. UI Warning Message thrown(incorrect username/password)
* Valid Username/Wrong Password -- Log in button pressed. UI warning message thrown(incorrect username/password)
* Valid Username/Valid Password -- Log in button pressed. Succesful login. 


###Group Creation

* Group Name input filled out -- Create button pressed. UI Warning message thrown(enrollment key) 
* Group Name input/group key -- Create button pressed. UI warning message thrown(enrollment keys dont' match)
* Group Name/ValidKey/ValidCheckKey -- Create button pressed. Succesful group creation. 
* Confirmed group exists in groups table. 
