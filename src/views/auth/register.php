<?php require __DIR__ . '/../layout/header.php'; ?>

        <form method="POST" action="register.php" id="signupForm">
           <fieldset >
            <legend>Sign-up</legend>
            
            <label>
                FirstName:
                <input type="text" name="first name" placeholder="Enter your first name" required>
            </label>
            <label>
                LastName:
                <input type="text" name="lastname" placeholder="Enter your last name" required>
            </label>
            <label>
                Email:
                <input type="email" name="Email" placeholder="Enter your email" required>
            </label>
            <label>
                Password:
                <input type="password" name="Password" placeholder="Enter your password here" id="password">
            </label>
            
            <button type="button" onclick="togglepassword()" id="toggleBtn"> Show password </button>
            
            <fieldset>
                <legend>select your gender</legend>
                <div class="radio-group">
                <div><label><input type="radio" name="gender" id="male" value="male">Male</label></div>
                <div><label><input type="radio" name="gender" id="male" value="female">Female</label> </div>
                </div>    
            </fieldset>
            
            <fieldset>
                <legend>select your role </legend>
                <div class="radio-group">
                <div><label><input type="radio" name="role" value="producer" id="producer">Producer</label></div>
                <div><label><input type="radio" name="role" value="consumer" id="consumer">Consumer</label></div>
                </div> 
            </fieldset>
                
            <button type="submit">Create Account</button>
</Fieldset>  
</form>

    <p class="switch">
      Already have an account?
      <a href="login.php">Login →</a>
    </p>

<?php require __DIR__ . '/../layout/footer.php'; ?>
<?php

// Auth view: register.php
//write the backend code here also
//start session
session_start();
$error = $success = "";



function renderFlash($error, $success)
{
    if (!empty($error)) {
        echo '<div class="error">' . htmlspecialchars($error) . '</div>';
    }

    if (!empty($success)) {
        echo '<div class="success">' . htmlspecialchars($success) . '</div>';
    }
}
renderFlash($error, $success);



//check if form submitted
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    //user clcked create account
    //grab from data
    $firstname = trim($_POST['first_name'] ?? '');
    $lastname = trim($_POST['lastname'] ?? '');
    $email = filter_var($_POST['Email'] ?? '', FILTER_SANITIZE_EMAIL);
    $password = $_POST['Password'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $role = $_POST['role'] ?? '';


}

//validate data
$errors = [];
if(empty($firstname) || strlen($firstname) <2) $errors[] = "First name must be 2+ characters.";
if(empty($lastname) || strlen($lastname < 2)) $errors[] = "Last name must be 2+ characters.";
if(!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email address";
if(strlen($password) <6) $errors[] = "Password must be atleast 6 characters.";
if(!in_array($gender, ['male', 'female'])) $errors[] = "Select gender.";
if(!in_array($role, ['producer', 'consumer'])) $errors[] = "Select role.";


//load database
if(empty($errors)){
    try{
        require_once __DIR__ . '/../../config/database.php';
        $pdo = get_db();
        //check if email exists
        $stmt = $pdo ->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        if($stmt->fetch()){
            $error = "Email already registered.";
        }else{
            //hash password
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            //insert user
            $stmt = $pdo ->prepare("INSERT INTO users(firstname, Lastname, email, password_hash, gender, role, created_at) VALUES(?,?,?,?,?,?, NOW()");
            $stmt->execute([$firstname, $lastname, $email, $password_hash, $gender, $role]);
            $success = "Account created successfully. You can now log in. <a href='login.php'>Login </a>";


        }
    }catch(PDOException $e){
        error_log("Reistration error:", $e ->getMessage());
        $error = "Registration failed. Try again." . $e->getMessage();
    }
} else {
    $error = implode(' ', $errors);
}




?>
