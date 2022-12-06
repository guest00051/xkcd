window.onload = function () {
    document.getElementById('showError').style.display = 'none';
}


function validateEmail() {    
    let email = document.getElementById('userEmail').value.trim();
    let pat = /^[\w]+@[\w]+.[\w]{2,4}$/;

    if (!pat.test(email)) {
        document.getElementById('showError').style.display = 'inline';
        document.getElementById('showError').innerHTML = "<span style='color: red;'>*</span>Please enter valid email.";
        return false;
    }
    else {
        sendOTP(email);
        return true;
    }
}

function sendOTP(email) {
    let xml = new XMLHttpRequest();
    xml.open('POST', 'Controller/sendMail.php', true);
    xml.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xml.onreadystatechange = function () {        
    let otpLink = 'View/verify.php?email='+email;
        if (this.readyState == 4 && this.status == 200) {            
            if (this.responseText == '1') {     //User already subscribed
                document.getElementById('showError').style.display = 'inline';
                document.getElementById('showError').innerHTML = "<span style='color: red;'>*</span>" + "Your account is subscribed for comics.";
            }
            else if (this.responseText == '2') { //user subscribed but not verified
                document.getElementById('showError').style.display = 'inline';
                document.getElementById('showError').innerHTML = "<span style='color: red;'>*</span><a href='"+otpLink+"' style='color:white'>Click here to verify OTP</a>";
               
            }
            else if (this.responseText == '3') {  //account is deactivate
                document.getElementById('showError').style.display = 'inline';
                document.getElementById('showError').innerHTML = "<span style='color: red;'>*</span>" + "Your account is deactivate";
            }
            
            else if (this.responseText == 'User created successfully') {  //new user created
                document.getElementById('showError').style.display = 'inline';
                document.getElementById('showError').innerHTML = "<span style='color: red;'>*</span>" + this.responseText + "<a href='"+otpLink+"'  style='color:white'>Click here to verify OTP</a>";
            }
            else {  //some error response 
                document.getElementById('showError').style.display = 'inline';
                document.getElementById('showError').innerHTML = "<span style='color: red;'>*</span>" +this.responseText;
            }

        }
        else {
            document.getElementById('showError').style.display = 'inline';
            document.getElementById('showError').innerHTML = "<span style='color: red;'>*</span> failed to request";
        }
    }

    xml.send('Email=' + email);
}

function validateOTP() {
   let otp = document.getElementById('userOtp').value.trim();
   let email = document.getElementById('userOtpEmail').value.trim();
   if(isNaN(otp)){
    document.getElementById('showError').style.display = 'inline';
    document.getElementById('showError').innerHTML = "<span style='color: red;'>*</span>Please enter valid OTP [Numbers Only]";
    return false;
   }
   else if(otp.length != 6){
    document.getElementById('showError').style.display = 'inline';
    document.getElementById('showError').innerHTML = "<span style='color: red;'>*</span>Please enter valid OTP.";
    return false;
   }
   else {
    document.getElementById('showError').style.display = 'none';
    verifyOTP(email,otp);
    return true;
   }
}

function verifyOTP(email,otp) {
    let xml = new XMLHttpRequest();
    xml.open('POST','../Controller/verifyOtp.php',true);
    xml.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xml.onreadystatechange = function () {
        document.getElementById('showError').style.display = 'inline';
        document.getElementById('showError').innerHTML = "<span style='color: red;'>*</span>" + this.responseText;
    }
    
    xml.send(JSON.stringify({'Email' :email , 'OTP' : otp}));
}

function validUnsubEmail() {
    let email = document.getElementById('userEmail').value.trim();
    let pat = /^[\w]+@[\w]+.[\w]{2,4}$/;

    if (!pat.test(email)) {
        document.getElementById('showError').style.display = 'inline';
        document.getElementById('showError').innerHTML = "<span style='color: red;'>*</span>Please reopen the page from email ";
        return false;
    }
    else {
        unSubComic(email);
        return true;
    }  
 }

 function unSubComic(email) {
     let xml = new XMLHttpRequest();
     xml.open('POST','../Controller/unSubscribe.php',true);
     xml.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
     xml.onreadystatechange = function() {
        document.getElementById('showError').style.display = 'inline';
        document.getElementById('showError').innerHTML = "<span style='color: red;'>*</span>" + this.responseText;
     }
     xml.send('email='+email);

}