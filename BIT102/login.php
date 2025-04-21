<?php
require 'config2.php';

if (isset($_SESSION['user'])) {
    header("Location:login.php");
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email']
        ];
        header("Location: index.html");
        exit();
    } else {
        $error = "Incorrect email or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - WorldTraveller</title>
    <link rel="icon" type="image/png" href="Logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
      
        :root {
            --primary-color: #3498db;
            --secondary-color: #2980b9;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: var(--light-color);
            color: var(--dark-color);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        
        .background-video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
           
        }
        
        .auth-container {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 450px;
            padding: 2.5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
            transform-style: preserve-3d;
            transition: all 0.3s ease;
        }
        
        .auth-container:hover {
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
        }
        
        .logo {
            width: 80px;
            height: 80px;
            margin-bottom: 1.5rem;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary-color);
        }
        
        .cursive-heading {
            font-family: 'Pacifico', cursive;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-size: 2rem;
        }
        
        .auth-form {
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
        }
        
        .input-group {
            position: relative;
            text-align: left;
        }
        
        .input-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--dark-color);
        }
        
        .input-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        .input-group input:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }
        
        .input-group i {
            position: absolute;
            right: 15px;
            top: 40px;
            color: #7f8c8d;
        }
        
        .error-message {
            color: var(--accent-color);
            font-size: 0.85rem;
            margin-top: 0.3rem;
            text-align: left;
        }
        
        .submit-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 1rem;
        }
        
        .submit-btn:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        .auth-link {
            margin-top: 1.5rem;
            color: var(--dark-color);
        }
        
        .auth-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }
        
        .auth-link a:hover {
            text-decoration: underline;
        }
        
      
        @media (max-width: 500px) {
            .auth-container {
                padding: 1.5rem;
                width: 95%;
            }
            
            .cursive-heading {
                font-size: 1.7rem;
            }
        }
    </style>
</head>
<body>
    <video autoplay muted loop class="background-video">
        <source src="LoginVideo.mp4" type="video/mp4">
    </video>
    
    <div class="auth-container">
        <img src="Logo.png" alt="World Traveller Logo" class="logo">
        <h1 class="cursive-heading">Welcome Back</h1>
        
        <?php if(isset($_SESSION['success'])): ?>
            <div class="success-message" style="color: #2ecc71; margin-bottom: 1rem;">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <?php if($error): ?>
            <div class="error-message" style="margin-bottom: 1rem;"><?= $error ?></div>
        <?php endif; ?>
        
        <form id="login-form" class="auth-form" method="POST">
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
                <i class="fas fa-envelope"></i>
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
                <i class="fas fa-lock"></i>
            </div>

            <button type="submit" class="submit-btn">Login</button>
        </form>
        
        <p class="auth-link">Don't have an account? <a href="signup.php">Sign up now</a></p>
    </div>
</body>
</html>