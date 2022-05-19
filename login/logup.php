<?php
// Bao gồm tệp cấu hình
require_once "config.php";
 
// Xác định các biến và khởi tạo với các giá trị trống
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
// Xử lý dữ liệu biểu mẫu khi biểu mẫu được gửi
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Xác thực tên người dùng
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else{
        // Chuẩn bị một tuyên bố lựa chọn
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Liên kết các biến với câu lệnh đã chuẩn bị dưới dạng tham số
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Đặt thông số
            $param_username = trim($_POST["username"]);
            
            // Cố gắng thực hiện tuyên bố đã chuẩn bị
            if(mysqli_stmt_execute($stmt)){
                /* kết quả lưu trữ */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Đóng tuyên bố
            mysqli_stmt_close($stmt);
        }
    }
    
    // Xác thực mật khẩu
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Xác thực xác nhận mật khẩu
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Kiểm tra lỗi đầu vào trước khi chèn vào cơ sở dữ liệu
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Liên kết các biến với câu lệnh đã chuẩn bị dưới dạng tham số
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            // Đặt thông số
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Tạo mật khẩu băm
            
            // Cố gắng thực hiện tuyên bố đã chuẩn bị
            if(mysqli_stmt_execute($stmt)){
                // Chuyển hướng đến trang đăng nhập
                header("location: login.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Đóng tuyên bố
            mysqli_stmt_close($stmt);
        }
    }
    
    // đóng liên kết
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles_register.css">
</head>
<body>
<!-- header -->
<div class="header">
    <!-- chuyen mau trnag cho navbar -->
    <nav class="navbar navbar-light bg-light p-3 mb-2 bg-white text-dark">
     <div class="container-fluid ">

      <a class="navbar-brand phvtg " href="">
        <img src="/BTL/img/question-circle.svg" alt=""alt="" width="15" height="15" class="d-inline-block ce">     
        phản hồi và trợ giúp
      </a>
     </div>
    </nav>
</div>
<!-- header -->
    <div class="content">
        <h2 class="text-center">Tạo Tài Khoản</h2>
        <p class="title-login2">
          <h6 class="text-center">
            Nhập Thông Tin Tài Khoản Mà Bạn Muốn Tạo.
          </h6>
        </p>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group1">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group2">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group3">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group4">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
            <p>Already have an account? <a href="restaurant/login/login.php">Login here</a>.</p>
        </form>
    </div>    
<!-- footer -->
<footer class="text-center text-white footer" style="background-color: rgb(179, 48, 48) ;">
    <div class="text-secondary p-3" style="background-color: black;"> 
    </div>
  </footer>
<!-- Footer -->
</body>
</html>