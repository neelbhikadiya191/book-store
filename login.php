

<html>
<link rel="stylesheet" href="style.css">

<body>
    <header>
        <div>
            <a href="index.php"><img src="image/logo.png"></a>
        </div>
    </header>
    <blockquote>
        <div class="container">
            <center>
                <h1>Login</h1>
            
            
            <form action="checklogin.php" method="post">
                Username:<br><input type="text" name="username" />
                <br><br>
                Password:<br><input type="password" name="pwd" />
                <br><br>
                <input class="button" type="submit" value="Login" />
                <input class="button" type="button" name="cancel" value="Cancel"
                    onClick="window.location='index.php';" />
            </form>
            </center>  
             
        </div>
        <blockquote>
            <?php
            if (isset($_GET['errcode'])) {
                if ($_GET['errcode'] == 1) {
                    echo '<span style="color: red;">Invalid username or password.</span>';
                } elseif ($_GET['errcode'] == 2) {
                    echo '<span style="color: red;">Please login.</span>';
                }
            }

            ?>
        </blockquote>
    </blockquote>
    <footer>
        <div style="text-align: center; font-size: 12px; margin: 20px 0px">
        
		Neel Bhikadiya = 8885049 <br>
Harshil Lunagariya = 8968745
        </div>
    </footer>
</body>

</html>