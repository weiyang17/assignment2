<?php
require 'config2.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$errors = [];
$preservedValues = [
    'name' => '',
    'email' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $preservedValues['name'] = trim($_POST['name'] ?? '');
    $preservedValues['email'] = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirmPassword = trim($_POST['confirmPassword'] ?? '');

    // Validation checks
    if (empty($preservedValues['name'])) {
        $errors['name'] = 'Name is required';
    }

    if (empty($preservedValues['email'])) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($preservedValues['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format';
    }

    if (empty($password)) {
        $errors['password'] = 'Password is required';
    } elseif (!preg_match('/^\d{6}$/', $password)) {
        $errors['password'] = 'Password must be exactly 6 digits';
    }

    if ($password !== $confirmPassword) {
        $errors['confirmPassword'] = 'Passwords do not match';
    }

    if (empty($errors)) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$preservedValues['name'], $preservedValues['email'], $hashedPassword]);
            
            $_SESSION['registration_success'] = true;
            header("Location: login.php");
            exit();
        } catch(PDOException $e) {
            $errors['general'] = "Registration failed: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - WorldTraveller</title>
    <link rel="icon" type="image/png" href="Logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            color: #333;
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
            border: 3px solid #3498db;
        }
        
        .cursive-heading {
            font-family: 'Pacifico', cursive;
            color: #3498db;
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
            color: #333;
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
            border-color: #3498db;
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
            color: #e74c3c;
            font-size: 0.85rem;
            margin-top: 0.3rem;
            text-align: left;
        }
        
        .submit-btn {
            background-color: #3498db;
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
            background-color: #2980b9;
            transform: translateY(-2px);
        }
        
        .auth-link {
            margin-top: 1.5rem;
            color: #333;
        }
        
        .auth-link a {
            color: #3498db;
            text-decoration: none;
            font-weight: 600;
        }
        
        .auth-link a:hover {
            text-decoration: underline;
        }
        
        /* Success popup styles */
        .success-popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            text-align: center;
            max-width: 400px;
            width: 90%;
            display: none;
        }
        
        .success-popup.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }
        
        .success-popup i {
            font-size: 3rem;
            color: #2ecc71;
            margin-bottom: 1rem;
        }
        
        .success-popup h2 {
            color: #333;
            margin-bottom: 1rem;
        }
        
        .success-popup p {
            margin-bottom: 1.5rem;
            color: #7f8c8d;
        }
        
        .success-popup button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .success-popup button:hover {
            background-color: #2980b9;
        }
        
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
        }
        
        .overlay.active {
            display: block;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translate(-50%, -40%); }
            to { opacity: 1; transform: translate(-50%, -50%); }
        }
        
        /* Responsive design */
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
        <source src="SignupVideo.mp4" type="video/mp4">
    </video>
    
    <div class="auth-container">
        <img src="Logo.png" alt="World Traveller Logo" class="logo">
        <h1 class="cursive-heading">Join WorldTraveller</h1>
        
        <?php if(isset($errors['general'])): ?>
            <div class="error-message" style="margin-bottom: 1rem;"><?= htmlspecialchars($errors['general']) ?></div>
        <?php endif; ?>

        <form id="signup-form" class="auth-form" method="POST" novalidate>
            <div class="input-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" 
                       value="<?= htmlspecialchars($preservedValues['name']) ?>"
                       placeholder="Enter your name"
                       required>
                <i class="fas fa-user"></i>
                <?php if(isset($errors['name'])): ?>
                    <p class="error-message"><?= htmlspecialchars($errors['name']) ?></p>
                <?php endif; ?>
            </div>

            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" 
                       value="<?= htmlspecialchars($preservedValues['email']) ?>"
                       placeholder="Enter your email"
                       required>
                <i class="fas fa-envelope"></i>
                <?php if(isset($errors['email'])): ?>
                    <p class="error-message"><?= htmlspecialchars($errors['email']) ?></p>
                <?php endif; ?>
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password"
                       placeholder="6-digit password"
                       required
                       pattern="\d{6}"
                       title="Must be 6 digits">
                <i class="fas fa-lock"></i>
                <?php if(isset($errors['password'])): ?>
                    <p class="error-message"><?= htmlspecialchars($errors['password']) ?></p>
                <?php endif; ?>
            </div>

            <div class="input-group">
                <label for="confirmPassword">Confirm Password</label>
                <input type="password" id="confirmPassword" name="confirmPassword"
                       placeholder="Re-enter your password"
                       required>
                <i class="fas fa-lock"></i>
                <?php if(isset($errors['confirmPassword'])): ?>
                    <p class="error-message"><?= htmlspecialchars($errors['confirmPassword']) ?></p>
                <?php endif; ?>
            </div>

            <button type="submit" class="submit-btn">Sign Up Now</button>
        </form>

        <p class="auth-link">Already have an account? <a href="login.php">Login here</a></p>
    </div>
    
    <!-- Success popup that will show on login page -->
    
    <script>
        // Function to handle form submission
        document.getElementById('signup-form').addEventListener('submit', function(e) {
            // Client-side validation can be added here if needed
            return true;
        });
    </script>
</body>
</html>