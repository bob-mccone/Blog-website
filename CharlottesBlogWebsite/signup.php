<?php
    define("TITLE", "Sign up | Charlotte's Blog");
    include 'includes/header.php';
?>
            <li class="last"><a href="login.php">Login</a></li>
            <li class="last"><input type="text" placeholder="Search..."></li>
        </ul>
    </div><!-- nav -->
    <div id="content">
        <div id="register-form">
            <h1>Please register to continue</h1>
            <form action="register.php" method="post" autocomplete="off">
                <label for="username"><b>Username</b></label>
                <input type="text" name="username" placeholder="Enter your username" id="username" required>
                <label for="password"><b>Password</b></label>
                <input type="password" name="password" placeholder="Enter your password" id="password" required>
                <label for="confirm_password"><b>Confirm Password</b></label>
                <input type="password" name="confirm_password" placeholder="Confirm Password" id="confirm_password" required>
                <label for="email"><b>Email</b></label>
                <input type="email" name="email" placeholder="Enter your email" id="email" required>
                
                <input type="submit" value="Register">
            </form>
        </div><!-- register_form -->
        <div class="msg"></div>
    </div><!-- content -->
    <script>
        document.querySelector("#register\-form form").onsubmit = function(event) {
            event.preventDefault();
            var form_data = new FormData(document.querySelector("#register\-form form"));
            var xhr = new XMLHttpRequest();
            xhr.open("POST", document.querySelector("#register\-form form").action, true);
            xhr.onload = function () {
                if (this.responseText.toLowerCase().indexOf("success") !== -1) {
                    window.location.href = "profile.php";
                } else {
                    document.querySelector(".msg").innerHTML = this.responseText;
                }
            };
            xhr.send(form_data);
        };
        // var form = document.querySelector('#register\-form form');
        // form.onSubmit = function(event) {
        //     event.preventDefault();
        //     var form_data = new FormData(form);
        //     var xhr = new XMLHttpRequest();
        //     xhr.open('POST', form.action, true);
        //     xhr.onload = function () {
        //         document.querySelector('.msg').innerHTML = this.responseText;
        //     };
        //     xhr.send(form_data);
        // };
    </script>

<?php
    include('includes/footer.php');
?>