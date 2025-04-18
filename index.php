<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AI聊天室</title>
  <link href="https://www.layuicdn.com/layui-v2.6.8/css/layui.css" rel="stylesheet">
  <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
  <style>
    body {
      background-color: #f2f2f2;
      font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    }
    .chat-container {
      max-width: 800px;
      margin: 20px auto;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }
    .chat-header {
      background-color: #393D49;
      color: white;
      padding: 15px;
      font-size: 18px;
    }
    .chat-body {
      height: 500px;
      overflow-y: auto;
      padding: 15px;
      background-color: white;
    }
    .message {
      margin-bottom: 15px;
      display: flex;
      flex-direction: column;
    }
    .message-user {
      align-items: flex-end;
    }
    .message-ai {
      align-items: flex-start;
    }
    .message-content {
      max-width: 70%;
      padding: 10px 15px;
      border-radius: 5px;
      margin-top: 5px;
      word-break: break-word;
    }
    .user-message {
      background-color: #1E9FFF;
      color: white;
    }
    .ai-message {
      background-color: #f1f1f1;
      color: #333;
    }
    .message-time {
      font-size: 12px;
      color: #999;
    }
    .chat-footer .layui-textarea {
      height: 4px !important;
      padding: 3px 1px !important;
      resize: none;
    }
    .typing-indicator {
      display: inline-block;
      padding: 5px 10px;
      background-color: #f1f1f1;
      border-radius: 15px;
      font-size: 12px;
      color: #666;
      margin-bottom: 10px;
    }
    .dot {
      display: inline-block;
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background-color: #666;
      margin: 0 2px;
      animation: bounce 1.4s infinite ease-in-out;
    }
    .dot:nth-child(2) { animation-delay: 0.2s; }
    .dot:nth-child(3) { animation-delay: 0.4s; }
    @keyframes bounce {
      0%, 80%, 100% { transform: scale(0); }
      40% { transform: scale(1); }
    }
  </style>
</head>
<body>
<div id="app">
  <div class="chat-container">
    <div class="chat-header">
      <i class="layui-icon layui-icon-chat"></i> AI聊天室
    </div>
    <div class="chat-body" ref="chatBody">
      <div v-for="(msg, index) in messages" :key="index" :class="['message', msg.role === 'user' ? 'message-user' : 'message-ai']">
        <div class="message-time">{{ formatTime(msg.time) }}</div>
        <div :class="['message-content', msg.role === 'user' ? 'user-message' : 'ai-message']">
          {{ msg.content }}
        </div>
      </div>
      <div v-if="isTyping" class="message message-ai">
        <div class="message-time">{{ formatTime(new Date()) }}</div>
        <div class="typing-indicator">
          AI正在思考 <span class="dot"></span><span class="dot"></span><span class="dot"></span>
        </div>
      </div>
    </div>
    <div class="chat-footer">
      <div class="layui-form">
        <div class="layui-form-item">
          <div class="layui-input-block" style="margin-left: 0;">
            <textarea v-model="inputMessage" @keyup.enter="sendMessage" class="layui-textarea" placeholder="输入消息..." rows="3"></textarea>
          </div>
        </div>
        <div class="layui-form-item">
          <div class="layui-input-block" style="margin-left: 0; text-align: right;">
            <button @click="sendMessage" class="layui-btn layui-btn-normal">发送</button>
            <button @click="clearChat" class="layui-btn layui-btn-primary">清空</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://www.layuicdn.com/layui-v2.6.8/layui.js"></script>
<script>
const { createApp, ref, onMounted, nextTick } = Vue;

createApp({
  setup() {
    const messages = ref([]);
    const inputMessage = ref('');
    const isTyping = ref(false);
    const chatBody = ref(null);

    const formatTime = (date) => {
      if (!(date instanceof Date)) date = new Date(date);
      return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    };

    const sendMessage = async () => {
      const message = inputMessage.value.trim();
      if (!message) return;

      messages.value.push({ role: 'user', content: message, time: new Date() });
      inputMessage.value = '';
      scrollToBottom();
      isTyping.value = true;
      scrollToBottom();

      try {
        const aiResponse = await getAIResponse(message);
        messages.value.push({ role: 'assistant', content: aiResponse, time: new Date() });
      } catch {
        messages.value.push({ role: 'assistant', content: 'AI请求失败，请稍后重试。', time: new Date() });
      } finally {
        isTyping.value = false;
        scrollToBottom();
      }
    };

    const getAIResponse = async (message) => {
      const res = await fetch(`api.php?t=${encodeURIComponent(message)}`);
      const data = await res.json();
      return data.content || 'AI未返回内容';
    };

    const clearChat = () => {
      messages.value = [];
    };

    const scrollToBottom = () => {
      nextTick(() => {
        if (chatBody.value) {
          chatBody.value.scrollTop = chatBody.value.scrollHeight;
        }
      });
    };

    onMounted(() => {
      layui.use(['layer', 'form'], () => {});
      messages.value.push({ role: 'assistant', content: '你好！我是AI助手，有什么可以帮您的吗？', time: new Date() });
    });

    return {
      messages,
      inputMessage,
      isTyping,
      chatBody,
      formatTime,
      sendMessage,
      clearChat
    };
  }
}).mount('#app');
</script>
</body>
</html>