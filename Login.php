<?php
include "Connect.php";

$username = $password ="";
$username_err = $password_err = "";

if($_SERVER["REQUEST_METHOD"]=="POST"){
    if(empty(trim($_POST["username"]))){
        $username_err ="Please enter username";
    }else{
        $username=trim($_POST["username"]);
    }
    
    if(empty(trim($_POST["password"]))){
       
        $password_err ="Please enter password";
    }else{
        $password=trim($_POST["password"]);
    }
    
    if (empty($username_err) && empty($password_err)) {
      
      // Prepare a select statement
      $sql = "SELECT userID, username, password FROM users WHERE username = ?";

      // Execute the prepared statement
      if ($stmt = mysqli_prepare($conn, $sql)) {
        // Tiếp tục xử lý
    } else {
        // In ra lỗi để xem chi tiết
        echo "Error preparing query: " . mysqli_error($conn);
    }
    
      if ($stmt) 
      {
          // Bind variables to the prepared statement as parameters
          mysqli_stmt_bind_param($stmt, "s", $param_username);

          // Set parameters
          $param_username = $username;
          // Execute the prepared statement
          if (mysqli_stmt_execute($stmt)) {
           // Store result
              mysqli_stmt_store_result($stmt);

              // Check if username exists, then verify password
              if (mysqli_stmt_num_rows($stmt) == 1) {
                  //Bind result variables
                  mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);

                  // Fetch the results
                  if (mysqli_stmt_fetch($stmt)) {
                      // Verify the password with the hashed password from the database
                      if ($password == $hashed_password) {
                          // Redirect user to welcome page
                          header("location: main.php");
                          exit();
                      } else {
                          // Display an error message if password is not valid
                          $password_err = "The password you entered was not valid.";
                      }
                  }
              } else {
                  // Display an error message if username doesn't exist
                  $username_err = "No account found with that username.";
              }
          } else {
              echo "Oops! Something went wrong. Please try again later.";
          }

          // Close statement
          mysqli_stmt_close($stmt);
      }
  }
}
// Close connection
mysqli_close($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
<style>
  /* CSS for Login Form */
body {
  font-family: 'Arial', sans-serif;
  background-color: #f4f4f9;
  margin: 0;
  padding: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}

.container {
  background: #ffffff;
  padding: 30px 40px;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  width: 100%;
  max-width: 400px;
}

h2 {
  font-size: 24px;
  color: #333;
  text-align: center;
  margin-bottom: 20px;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  font-weight: bold;
  margin-bottom: 5px;
  color: #555;
}

.form-control {
  width: 100%;
  padding: 10px;
  border-radius: 5px;
  border: 1px solid #ccc;
  outline: none;
  font-size: 14px;
  transition: border-color 0.3s ease-in-out;
}

.form-control:focus {
  border-color: #5cb85c;
}

.text-danger {
  font-size: 12px;
  color: #d9534f;
  margin-top: 5px;
}

.btn-success {
  background-color: #5cb85c;
  border: none;
  color: #fff;
  padding: 10px 15px;
  font-size: 14px;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease-in-out;
  width: 100%;
}

.btn-success:hover {
  background-color: #4cae4c;
}

p {
  text-align: center;
  margin-top: 15px;
  font-size: 14px;
}

p a {
  color: #5cb85c;
  text-decoration: none;
}

p a:hover {
  text-decoration: underline;
}
</style>

<div class="container">
  <h2 style="display: flex; justify-content: center;">Login</h2>
  
  <form action="", method="post">

  <div class="form-group">
      <label for="name">Name:</label>
      <input type="name" class="form-control" id="name" name="username" required>
      <!-- in loi khi ko nhap vao username-->
      <span class="text-danger"><?php echo $username_err; ?></span>
    </div>

    <!-- <div class="form-group">
      <label for="mail">Mail:</label>
      <input type="email" class="form-control" id="email" name="email">
    </div> -->
    
    <div class="form-group">
      <label for="password">Password:</label>
      <input type="password" class="form-control" id="password" name="password" required>

      <span class="text-danger"><?php echo $password_err; ?></span>
    </div>
<!-- 
    <div class="form-group">
      <label for="confirm">Confirm Password:</label>
      <input type="password" class="form-control" id="password" name="password">
    </div>

    <div class="form-group">
      <label for="address">Address:</label>
      <input type="text" class="form-control" id="address" name="address">
    </div>

    <div class="checkbox">
      <label><input type="checkbox" name="remember"> Remember me</label>
    </div> -->

    <button type="submit" class="btn btn-success">Submit</button>
    <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
  </form>
</div>

</body>
</html>