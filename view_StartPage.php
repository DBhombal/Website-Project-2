<!-- 
    Login Page
    Send to controller
-->

<!DOCTYPE HTML>
<html>
    <head>
        <title>Login to the forum</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <style>
            #layoutMain
            {
                position: relative;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background-color:LightGray;
            }

            #loginBlock
            {
                position: absolute;
                width: 50%;
                left: 25vw;
                background-color:SkyBlue;
            }
        </style>
    </head>

    <body>
        <!-- Page Layout -->
        <div id="layoutMain">
            <center><h1>Comp 3540 - Final Project<br> Muhammad Danish Bhombal<br>T00662679</h1></center>
            <div id="loginBlock">
                <h2>Login or join the forum</h2>
                <br>
                <button id='btnLogin' style='display:inline-block; width:100%; height:40px'>Login</button>
                <br>
                <br>
                <button id='btnSignUp' style='display:inline-block; width:100%; height:40px'>Sign Up</button>
            </div>
        </div>

        <script>

            //Handles the positioning of the login block - Keeps it centered
            let block = document.getElementById('loginBlock');
            block.style.top = (block.parentElement.offsetHeight - block.offsetHeight) / 2 + "px";
            
            window.addEventListener('resize', function() {
                let block = document.getElementById('loginBlock');
                block.style.top = (block.parentElement.offsetHeight - block.offsetHeight) / 2 + "px";
            });

        </script>

        <!-- Modal Windows -->
        <!-- Login Window -->
        <div class='modal fade' id='modal-login'>
            <div class='modal-dialog'>
                <div class='modal-content'>
                    <form action="controller.php" method='post' id="formLogIn">
                        <div class='modal-header'>
                            <h2>Login</h2>
                        </div>
                        <div class='modal-body'>
                            <input type="hidden" name="page" value="StartPage">
                            <input type="hidden" name="command" value="LogIn">

                            <label class="control-label" for="username">*Username:</label>
                            <input type="text" name="username" placeholder="Enter Username" required>
                            <?php if(!empty($error_msg)) echo $error_msg ?>
                            <br>

                            <label class="control-label" for="password">*Password:&nbsp;</label>
                            <input type="password" name="password" placeholder="Enter password" required>
                            <br>
                        </div>
                        <div class='modal-footer'>
                            <div class="input-group">
                                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-outline-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sign Up Window -->
        <div class='modal fade' id='modal-signup'>
            <div class='modal-dialog'>
                <div class='modal-content'>
                    <form action="controller.php" method='post' id="formSignUp">
                        <div class='modal-header'>
                            <h2>Sign Up</h2>
                        </div>
                        <div class='modal-body'>
                            <input type="hidden" name="page" value="StartPage">
                            <input type="hidden" name="command" value="SignUp">

                            <label class="control-label" for="username">*Username:</label>
                            <input type="text" name="username" placeholder="Enter Username" required>
                            <?php if(!empty($error_msg)) echo $error_msg ?>
                            <br>

                            <label class="control-label" for="password">*Password:&nbsp;</label>
                            <input type="password" name="password" placeholder="Enter password" required>
                            <br>

                            <label class="control-label" for="email"> *Email: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                            <input type='email' name='email' placeholder="Enter Email" required>
                        </div>
                        <div class='modal-footer'>
                            <div class='input-group'>
                                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-outline-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
<script>
    //Brings up the login Modal Window
    function showLoginModal() {
        $('#modal-login').modal('show');
    }

    //Brings up the signup Modal Window
    function showSignUpModal() {
        $('#modal-signup').modal('show');
    }
    
    //Hides all Modal Windows
    function noModal()
    {
        $('#modal-login').modal('hide');
        $('#modal-signup').modal('hide');
    }

    $('#btnLogin').click(showLoginModal);
    $('#btnSignUp').click(showSignUpModal);

    <?php
        if($display_modal_window == "login")
            echo "showLoginModal();";
        else if($display_modal_window == "signup")
            echo "showSignUpModal();";
        else if($display_modal_window == 'none')
            echo "noModal();";
        else
            ;
    ?>
</script>