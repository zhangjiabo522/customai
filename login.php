<?php
session_start();

// Default credentials
$defaultUser = 'admin';
$defaultPass = 'password';

// Check if credentials file exists
if (file_exists('mm.json')) {
    $creds = json_decode(file_get_contents('mm.json'), true);
    $defaultUser = $creds['username'] ?? $defaultUser;
    $defaultPass = $creds['password'] ?? $defaultPass;
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($username === $defaultUser && $password === $defaultPass) {
        $_SESSION['logged_in'] = true;
        header('Location: admin.php');
        exit();
    } else {
        $error = "无效的凭据";
    }
}
?>
<!DOCTYPE html>
<html lang="zh">
<head>
  <meta charset="UTF-8">
  <title>系统登录</title>
  <style>
    body {
      font-family: 'Courier New', monospace;
      background-color: #121212;
      color: #00ff00;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      background-image: 
        linear-gradient(rgba(58, 134, 255, 0.1) 1px, transparent 1px),
        linear-gradient(90deg, rgba(58, 134, 255, 0.1) 1px, transparent 1px);
      background-size: 20px 20px;
    }
    
    .login-box {
      background: rgba(30, 30, 35, 0.9);
      border: 1px solid #4a4a4a;
      border-radius: 5px;
      padding: 30px;
      width: 300px;
      box-shadow: 0 0 20px rgba(58, 134, 255, 0.3);
    }
    
    h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #3a86ff;
      text-shadow: 0 0 10px rgba(58, 134, 255, 0.5);
    }
    
    input {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      background: rgba(0, 0, 0, 0.5);
      border: 1px solid #4a4a4a;
      color: #00ff00;
      font-family: 'Courier New', monospace;
    }
    
    input:focus {
      outline: none;
      border-color: #3a86ff;
      box-shadow: 0 0 10px rgba(58, 134, 255, 0.5);
    }
    
    button {
      width: 100%;
      padding: 12px;
      margin-top: 20px;
      background: linear-gradient(135deg, #3a86ff, #8338ec);
      border: none;
      color: white;
      font-weight: bold;
      cursor: pointer;
      font-family: 'Courier New', monospace;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    
    .error {
      color: #ff0000;
      font-size: 14px;
      margin-top: 10px;
    }
  </style>
</head>
<body>
  <div class="login-box">
    <h2>系统认证</h2>
    <?php if (isset($error)): ?>
      <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="POST">
      <input type="text" name="username" placeholder="用户名" required>
      <input type="password" name="password" placeholder="密码" required>
      <button type="submit">登录</button>
    </form>
  </div>
</body>
</html>