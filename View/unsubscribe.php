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
                <legend><h1 class="headings">Unsubscribe the comics</h1></legend>
                <label for="email">Enter Email<span style="color: red;">*</span></label>
                <input type="email"  id="userEmail" value="<?php echo $_REQUEST['email'] ?? 'Please reopen the page from Email' ?>" disabled > 
                <button type="submit" class="btn" onclick="return validUnsubEmail()"> Unsubscribe </button>
                <div><p id="showError">*</p></div>
            </fieldset>
        </div>
         </div>
</body>

</html>