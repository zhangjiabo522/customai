<?php
// Check if user is logged in
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}

// Read current call count
$cs = 0;
if (file_exists('ai.json')) {
    $csData = json_decode(file_get_contents('ai.json'), true);
    $cs = $csData['cs'] ?? 0;
}
?>
<!DOCTYPE html>
<html lang="zh">
<head>
  <meta charset="UTF-8">
  <title>AI 中枢控制面板</title>
  <style>
    :root {
      --primary-color: #3a86ff;
      --secondary-color: #8338ec;
      --dark-color: #212529;
      --light-color: #f8f9fa;
      --panel-bg: rgba(30, 30, 35, 0.9);
      --border-tech: 1px solid #4a4a4a;
    }
    
    body {
      font-family: 'Courier New', monospace;
      background-color: var(--dark-color);
      color: #00ff00;
      padding: 20px;
      max-width: 700px;
      margin: auto;
      background-image: 
        linear-gradient(rgba(58, 134, 255, 0.1) 1px, transparent 1px),
        linear-gradient(90deg, rgba(58, 134, 255, 0.1) 1px, transparent 1px);
      background-size: 20px 20px;
    }
    
    .panel {
      background: var(--panel-bg);
      border: var(--border-tech);
      border-radius: 5px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 0 15px rgba(58, 134, 255, 0.3);
    }
    
    h2 {
      color: var(--primary-color);
      text-align: center;
      margin-bottom: 25px;
      font-weight: 700;
      text-shadow: 0 0 10px rgba(58, 134, 255, 0.5);
    }
    
    label {
      display: block;
      margin-bottom: 15px;
      font-size: 14px;
    }
    
    input, textarea {
      width: 100%;
      padding: 12px;
      margin: 8px 0;
      background: rgba(0, 0, 0, 0.5);
      border: var(--border-tech);
      color: #00ff00;
      font-family: 'Courier New', monospace;
      transition: all 0.3s;
    }
    
    input:focus, textarea:focus {
      outline: none;
      border-color: var(--primary-color);
      box-shadow: 0 0 10px rgba(58, 134, 255, 0.5);
    }
    
    button {
      padding: 12px 25px;
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border: none;
      color: white;
      font-weight: bold;
      cursor: pointer;
      border-radius: 3px;
      font-family: 'Courier New', monospace;
      text-transform: uppercase;
      letter-spacing: 1px;
      transition: all 0.3s;
      display: block;
      width: 100%;
      margin-top: 20px;
    }
    
    button:hover {
      box-shadow: 0 0 15px rgba(58, 134, 255, 0.7);
      transform: translateY(-2px);
    }
    
    #response {
      margin-top: 20px;
      white-space: pre-wrap;
      background: rgba(0, 0, 0, 0.7);
      padding: 15px;
      border: var(--border-tech);
      border-radius: 3px;
      font-size: 13px;
    }
    
    .status-panel {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
    }
    
    .status-item {
      flex: 1;
      text-align: center;
      padding: 10px;
      background: rgba(0, 0, 0, 0.5);
      margin: 0 5px;
      border: var(--border-tech);
      border-radius: 3px;
    }
    
    .status-value {
      font-size: 24px;
      color: var(--primary-color);
      font-weight: bold;
    }
    
    .pulse {
      animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
      0% { opacity: 1; }
      50% { opacity: 0.5; }
      100% { opacity: 1; }
    }
    
    .logout-btn {
      position: absolute;
      top: 20px;
      right: 20px;
      padding: 5px 10px;
      font-size: 12px;
      width: auto;
      background: rgba(255, 0, 0, 0.3);
    }
    
    .logout-btn:hover {
      background: rgba(255, 0, 0, 0.7);
    }
  </style>
</head>
<body>
  <a href="logout.php" class="logout-btn">注销登录</a>
  
  <div class="panel">
    <h2>AI 中枢控制面板</h2>
    
    <div class="status-panel">
      <div class="status-item">
        <div>调用次数</div>
        <div class="status-value pulse"><?php echo $cs; ?></div>
      </div>
    </div>
    
    <form id="configForm">
      <div class="panel">
        <label>组织名称（gzs）：<input type="text" name="gzs" required></label>
        <label>AI 名称（ainame）：<input type="text" name="ainame" required></label>
        <label>开发者名称（name）：<input type="text" name="name" required></label>
        <label>模型名（model）非必要 请勿修改：<input type="text" name="model" value="Qwen/QwQ-32B" required></label>
        <label>显示模型名（model_display）自定义可以修改：<input type="text" name="model_display" value="JiBoAI/V3-32B-NSFW-β"></label>
        <label>显示源（object）：<input type="text" name="object" value="zjb522.sbs"></label>
        <label>API Key（流动硅基key）<a href="https://cloud.siliconflow.cn/i/IjutLywD">注册流动硅基，此链接注册送15¥</a>
：<input type="text" name="apikey" required></label>
        
        <button type="submit">保存配置</button>
      </div>
    </form>
    
    <div id="response" class="panel"></div>
  </div>

  <script>
    document.getElementById('configForm').addEventListener('submit', async function (e) {
      e.preventDefault();
      const formData = new FormData(this);
      const data = Object.fromEntries(formData.entries());
      
      try {
        const response = await fetch('save_config.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
          document.getElementById('response').textContent = '配置保存成功！\n' + JSON.stringify(result, null, 2);
          document.getElementById('response').style.color = '#00ff00';
          
          // Update call count display
          if (result.cs !== undefined) {
            document.querySelector('.status-value').textContent = result.cs;
          }
        } else {
          document.getElementById('response').textContent = '错误：' + result.message;
          document.getElementById('response').style.color = '#ff0000';
        }
      } catch (error) {
        document.getElementById('response').textContent = '网络错误: ' + error.message;
        document.getElementById('response').style.color = '#ff0000';
      }
    });

    // Load existing config
    async function loadConfig() {
      try {
        const response = await fetch('get_config.php');
        const config = await response.json();
        
        if (config) {
          Object.keys(config).forEach(key => {
            const input = document.querySelector(`[name="${key}"]`);
            if (input) {
              input.value = config[key];
            }
          });
        }
      } catch (error) {
        console.error('加载配置失败:', error);
      }
    }
    
    // Load config when page loads
    window.addEventListener('DOMContentLoaded', loadConfig);
  </script>
</body>
</html>