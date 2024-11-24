<?php
include "cfg/dbconnect.php";

$name = $email = $pwd = $conf_pwd = $role = $photo = "";
$name_err = $email_err = $pwd_err = $conf_pwd_err = $role_err = $photo_err = "";
$error = false;

if (isset($_POST['submit'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pwd = trim($_POST['pwd']);
    $conf_pwd = trim($_POST['conf_pwd']);
    $role = isset($_POST['role']) ? trim($_POST['role']) : ''; 

    
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $photo = $_FILES['photo'];

        
        $allowed_extensions = ['jpg', 'jpeg', 'png'];
        $file_ext = pathinfo($photo['name'], PATHINFO_EXTENSION);

        if (!in_array(strtolower($file_ext), $allowed_extensions)) {
            $photo_err = "Only JPG, JPEG, and PNG files are allowed.";
            $error = true;
        } elseif ($photo['size'] > 2 * 1024 * 1024) {
            $photo_err = "File size should not exceed 2MB.";
            $error = true;
        }
    } else {
        $photo_err = "Please upload a valid photo.";
        $error = true;
    }

    
    if ($name == "") {
        $name_err = "Please enter your Name.";
        $error = true;
    }

    
    if ($email == "") {
        $email_err = "Please enter your Email.";
        $error = true;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format.";
        $error = true;
    }

    
    if ($pwd == "") {
        $pwd_err = "Please enter your Password.";
        $error = true;
    } elseif (strlen($pwd) < 6) {
        $pwd_err = "Password must be at least 6 characters.";
        $error = true;
    }

    
    if ($conf_pwd == "") {
        $conf_pwd_err = "Please confirm your Password.";
        $error = true;
    } elseif ($pwd !== $conf_pwd) {
        $conf_pwd_err = "Passwords do not match.";
        $error = true;
    }

    
    if ($role == "") {
        $role_err = "Please select a Role.";
        $error = true;
    }

    
    if (!$error) {
        
        $hashed_pwd = password_hash($pwd, PASSWORD_BCRYPT);

        
        $upload_dir = __DIR__ . "/uploads/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true); 
        }
        $photo_path = $upload_dir . basename($photo['name']);

        if (move_uploaded_file($photo['tmp_name'], $photo_path)) {
            
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, image) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $name, $email, $hashed_pwd, $role, $photo_path);

            if ($stmt->execute()) {
                echo "<script>alert('Registration successful!'); window.location.href='login.php';</script>";
            } else {
                echo "<script>alert('Error: Unable to register.');</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('Error: Unable to upload photo.');</script>";
        }
    }
}
?>

<?php include "topmenu.php"; ?>

<div class="container">
    <h1>REGISTRATION</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input 
                type="text"
                class="form-control" 
                name="name" 
                id="name"
                placeholder="Enter Name"/>
                <div class="text-danger"><?php echo $name_err; ?></div>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input 
                type="text"
                class="form-control" 
                name="email" 
                id="email"
                placeholder="Enter Email"/>
                <div class="text-danger"><?php echo $email_err; ?></div>
        </div>

        <div class="mb-3">
            <label for="pwd" class="form-label">Password</label>
            <input 
                type="password"
                class="form-control" 
                name="pwd" 
                id="pwd"
                placeholder="Password"/>
                <div class="text-danger"><?php echo $pwd_err; ?></div>
        </div>

        <div class="mb-3">
            <label for="conf_pwd" class="form-label">Confirm Password</label>
            <input 
                type="password"
                class="form-control" 
                name="conf_pwd" 
                id="conf_pwd"
                placeholder="Confirm Password"/>
                <div class="text-danger"><?php echo $conf_pwd_err; ?></div>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Select Role</label>
            <select class="form-select" name="role" id="role">
                <option value="" disabled selected>Select your role</option>
                <option value="leader">Leader</option>
                <option value="vice_leader">Vice Leader</option>
                <option value="member">Member</option>
            </select>
            <div class="text-danger"><?php echo $role_err; ?></div> 
        </div>

        <div class="mb-3">
            <label for="photo" class="form-label">Upload Photo (2x2)</label>
            <input 
                type="file" 
                class="form-control" 
                name="photo" 
                id="photo" 
                accept="image/*"/>
            <div class="text-danger"><?php echo $photo_err; ?></div>
        </div>

       
        <div class="mb-3">
            <input type="checkbox" id="showPassword" onclick="togglePasswordVisibility()"> 
            <label for="showPassword">Show Password & Confirm Password</label>
        </div>

        <div class="text-center">
            <button type="submit" name="submit" class="btn btn-primary">REGISTER</button>
        </div>
        <p>Already Registered? Login <a href="login.php">Here</a></p>
    </form>
</div>

<script>
    
    function togglePasswordVisibility() {
        var passwordField = document.getElementById('pwd');
        var confirmPasswordField = document.getElementById('conf_pwd');

        
        if (passwordField.type === "password" && confirmPasswordField.type === "password") {
            passwordField.type = "text";
            confirmPasswordField.type = "text";
        } else {
            passwordField.type = "password";
            confirmPasswordField.type = "password";
        }
    }
</script>

</body>
</html>
