<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    
    $sql = "SELECT * FROM users WHERE email = ? AND role = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $role);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_email'] = $user['email'];
            
            // Redirect based on role
            if ($user['role'] == 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: dashboard.php");
            }
            exit();
        } else {
            $error = "Invalid password";
        }
    } else {
        $error = "No user found with these credentials";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ANAW</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-blue: #1a73e8;
            --secondary-blue: #4285f4;
            --light-blue: #e8f0fe;
            --dark-blue: #0d47a1;
            --text-dark: #202124;
            --text-light: #5f6368;
            --white: #ffffff;
            --light-gray: #f8f9fa;
            --border-color: #dadce0;
            --gradient-primary: linear-gradient(135deg, #1a73e8 0%, #0d47a1 100%);
            --gradient-secondary: linear-gradient(135deg, #4285f4 0%, #1a73e8 100%);
            --shadow-light: 0 4px 12px rgba(0, 0, 0, 0.08);
            --shadow-medium: 0 8px 24px rgba(0, 0, 0, 0.12);
            --border-radius: 12px;
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: var(--light-gray);
            color: var(--text-dark);
            line-height: 1.6;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: var(--gradient-primary);
        }
        
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Header Styles */
        header {
            background-color: var(--white);
            box-shadow: var(--shadow-light);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
        }
        
        .logo {
            display: flex;
            align-items: center;
        }
        
        .logo-img {
            height: 40px;
            width: 40px;
            margin-right: 10px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .logo-text {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary-blue);
            letter-spacing: -0.5px;
        }
        
        .logo-text span {
            color: var(--dark-blue);
        }
        
        /* Login Form Styles */
        .login-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 0;
        }
        
        .login-card {
            background-color: var(--white);
            border-radius: var(--border-radius);
            padding: 40px 30px;
            box-shadow: var(--shadow-medium);
            width: 100%;
            max-width: 420px;
            position: relative;
            overflow: hidden;
            transition: var(--transition);
        }
        
        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.15);
        }
        
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--gradient-primary);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-header h2 {
            color: var(--primary-blue);
            margin-bottom: 10px;
            font-size: 1.8rem;
        }
        
        .login-header p {
            color: var(--text-light);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-dark);
        }
        
        .form-control {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
            transition: var(--transition);
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.2);
        }
        
        .btn {
            padding: 14px 16px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            width: 100%;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-primary {
            background: var(--gradient-primary);
            color: var(--white);
            box-shadow: var(--shadow-light);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }
        
        .btn-outline {
            background-color: transparent;
            border: 1px solid var(--primary-blue);
            color: var(--primary-blue);
        }
        
        .btn-outline:hover {
            background-color: var(--light-blue);
            transform: translateY(-2px);
        }
        
        .error {
            color: #d93025;
            text-align: center;
            margin-bottom: 15px;
            padding: 12px;
            background-color: #fce8e6;
            border-radius: 8px;
            border-left: 4px solid #d93025;
        }
        
        .form-footer {
            text-align: center;
            margin-top: 25px;
            color: var(--text-light);
        }
        
        .form-footer a {
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 500;
        }
        
        .form-footer a:hover {
            text-decoration: underline;
        }
        
        /* Footer */
        footer {
            background-color: var(--dark-blue);
            color: var(--white);
            padding: 25px 0 15px;
            margin-top: auto;
        }
        
        .copyright {
            text-align: center;
            padding-top: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.9rem;
            color: var(--light-blue);
        }
        
        /* Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                text-align: center;
            }
            
            .logo {
                margin-bottom: 10px;
            }
            
            .login-card {
                padding: 30px 20px;
                margin: 0 15px;
            }
            
            .login-header h2 {
                font-size: 1.6rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <img src="images/logo.png" alt="ANAW Logo" class="logo-img">
                    <div class="logo-text">ANA<span>W</span></div>
                </div>
                <nav>
                    <a href="index.php" class="btn btn-outline">Back to Home</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Login Form -->
    <div class="login-container">
        <div class="login-card fade-in">
            <div class="login-header">
                <h2>Member Login</h2>
                <p>Access your ANAW account</p>
            </div>
            
            <?php if(isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="role">Login As</label>
                    <select name="role" id="role" class="form-control" required>
                        <option value="">Select Role</option>
                        <option value="member">Member</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>
            
            <div class="form-footer">
                <p>Don't have an account? <a href="index.php">Contact administrator on the contact tab</a></p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="copyright">
                <p>&copy; 2023 ACT OF NETWORKING AND WELFARE (ANAW). All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Add subtle animation to form elements
        document.addEventListener('DOMContentLoaded', function() {
            const formControls = document.querySelectorAll('.form-control');
            formControls.forEach((control, index) => {
                control.style.opacity = '0';
                control.style.transform = 'translateY(10px)';
                control.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                
                setTimeout(() => {
                    control.style.opacity = '1';
                    control.style.transform = 'translateY(0)';
                }, 200 + (index * 100));
            });
            
            // Add focus effect to form controls
            formControls.forEach(control => {
                control.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.02)';
                });
                
                control.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });
            });
        });
    </script>
</body>
</html>