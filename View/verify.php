<!DOCTYPE html>
<html lang="en">

<head>
    <title>Email XKCD Challenge</title>
    <script src="./js/script.js"></script>
    <link rel="stylesheet" href="./css/stylesheet.css">

</head>

<body>
    
   <div class="comicForm">
        <div class="form">
              <fieldset>
                <legend><h1 class="headings">Verify email</h1></legend>
                <label for="otp">Enter OTP<span style="color: red;">*</span></label>
                <input type="text" placeholder="Enter OTP" id="userOtp" pattern="^[0-9]*$" required>
                <input type="hidden" value="<?php echo $_REQUEST['email']?>" id="userOtpEmail">  
                <button type="submit" class="btn" onclick="return validateOTP()"> Subscribe </button>
                <div><p id="showError">*</p></div>
            </fieldset>
            </fdiv>
        </div>
</body>

</html>