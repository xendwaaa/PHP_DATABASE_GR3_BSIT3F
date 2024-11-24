<?php
session_start();
include "cfg/dbconnect.php";

$email = $pwd = "";
$email_err = $pwd_err = "";
$err_msg = "";
$error = false;

if (isset($_POST['submit'])){
    $email = trim($_POST['email']);
    $pwd = trim($_POST['pwd']);

    if (isset($_POST['remember']))
        $remember = $_POST['remember'];

    if ($email == ""){
        $email_err = "Please enter your email.";
        $error = true;
    }

    if ($pwd == ""){
        $pwd_err = "Please enter your password.";
        $error = true;
    }

    if (!$error){ 
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1){
            $row = $result->fetch_assoc();
            $stored_pwd = $row['password'];

            if (password_verify($pwd, $stored_pwd)){

                if (isset($_POST['remember'])){
                    setcookie("remember_email", $email, time()+365*24*60*60);
                    setcookie("remember", $remember, time()+365*24*60*60);
                }
                else{
                    setcookie("remember_email", $email, time()-365*24*60*60);
                    setcookie("remember", $remember, time()-365*24*60*60);
                }

                $_SESSION['name'] = $row['name'];
                header("Location: index.php");
                exit();
            }
            else {
                $err_msg = "Incorrect password.";
            }
        } else {
            $err_msg = "Email is not registered.";
        }
    }
}
    
include "topmenu.php";
?>

<div class="container">
    <h1>LOGIN</h1>
    <form action="" method="post">

        <?php
        $display_email = isset($_COOKIE['remember_email']) ? $_COOKIE ['remember_email'] : $email;

        $checked = isset($_COOKIE['remember']) ? "checked" : (!empty($remember) ? "checked" : "") 
        ?>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input 
                type="email"
                class="form-control" 
                name="email" 
                id="email"
                placeholder="Enter Email"
                value="<?php echo htmlspecialchars($display_email); ?>" />
            <div class="text-danger"><?php echo $email_err; ?></div>
        </div>

        <div class="mb-3">
            <label for="pwd" class="form-label">Password</label>
            <input 
                type="password"
                class="form-control" 
                name="pwd" 
                id="pwd"
                placeholder="Password"
                value="<?php echo htmlspecialchars($pwd); ?>" />
            <div class="text-danger"><?php echo $pwd_err; ?></div>
            <?php if (!empty($err_msg)): ?>
                <div class="text-danger mt-2"><?php echo $err_msg; ?></div>
            <?php endif; ?>
        </div>

        <div class="form-check">
            <input
                class="form-check-input"   
                 type="checkbox" 
                 id="remember" 
                 name="remember"
                 value="checkedValue"
                 aria_label="Text for screen reader" <?= $checked?>
                  />
            Remember Me 
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
        </div>

        <p>Not Registered? Register <a href="register.php">Here</a></p>
    </form>
</div>
</body>
</html>
