### 开源不易 请尊重开源
### CustomAI 配置管理系统 / CustomAI Configuration System

## 一个基于 PHP 的轻量级 AI 配置管理平台，支持多模型配置、用户登录验证和配置保存功能。

## A lightweight PHP-based AI configuration management platform with support for multiple model setups, user login authentication, and config saving features.


---


---

### 默认管理员账号 / Default Admin Credentials

## 用户名 / Username: admin

## 密码 / Password: JiBoAI@2025

# 文件结构 / File Structure

## 核心文件 / Core Files
- `index.php`  
  首页入口页面 / Main entry page  
  *用户访问的默认页面 / Default page for user access*

- `login.php`  
  登录页面 / Login page  
  *管理员认证入口 / Administrator authentication portal*

- `admin.php`  
  管理员操作面板 / Admin panel  
  *系统配置和管理界面 / System configuration and management interface*

## 配置文件 / Configuration Files
- `ai.json`  
  AI模型配置 / AI models configuration  
  *存储所有可用AI模型的调用次数 / Stores parameters for all available AI models*

- `mm.json`  
  密码配置 / Model mapping  
  *定义模型名称与实际服务的映射关系 / Defines mapping between model names and actual services*

- `config.json`  
  当前使用配置 / Current configuration  
  *系统运行时加载的激活配置 / Active configuration loaded during runtime*

## API接口 / API Endpoints
- `api.php`  
  主接口处理器 / Main API handler  
  *处理所有AI功能请求 / Processes all AI function requests*

- `get_config.php`  
  获取配置接口 / Get configuration API  
  *读取当前系统设置 / Retrieves current system settings*

- `save_config.php`  
  保存配置接口 / Save configuration API  
  *更新系统配置参数 / Updates system configuration parameters*

## 目录说明 / Directory Notes
所有文件位于`customAI/`根目录下，无子文件夹结构。  
All files are located in the `customAI/` root directory with no subfolder structure.

---

### 使用方法 / How to Use

## 1. 将该项目部署到支持 PHP 的服务器（如 Apache）
# Deploy this project to a PHP-supported web server (e.g., Apache).


## 2. 访问 index.php 页面
# Visit the index.php page via your browser.


## 3. 使用默认管理员账号登录
# Log in with the default admin credentials.


## 4. 修改并保存 AI 模型配置
# Modify and save the AI configuration as needed.




---

### 注意事项 / Notes

## 所有配置存储在 JSON 文件中，便于手动编辑
## All configuration files are stored in JSON format for easy manual editing.

## 确保服务器对这些 JSON 文件有读写权限
## Make sure the server has read/write access to the JSON files.



---

### 许可证 / License

## MIT License（MIT 许可证）
### 开源不易 请尊重开源


---

如果你需要我为此生成一个完整项目首页或进一步美化后台界面，也可以告诉我！

