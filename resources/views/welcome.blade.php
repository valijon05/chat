<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chat App</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
    }

    body {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background: linear-gradient(135deg, #74b9ff, #a29bfe);
      overflow: hidden;
    }

    .navbar {
      width: 100%;
      padding: 1rem;
      background-color: #2d3436;
      color: #ffffff;
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: fixed;
      top: 0;
      box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.3);
    }

    .navbar .brand {
      font-size: 1.5rem;
      font-weight: bold;
      color: #ffeaa7;
    }

    .navbar .nav-links a {
      color: #dfe6e9;
      text-decoration: none;
      margin-left: 1rem;
      font-weight: bold;
    }

    .chat-container {
      display: flex;
      flex-direction: column;
      width: 80%;
      max-width: 1200px;
      height: 80vh;
      margin-top: 5rem;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.3);
      background: #ffffff;
    }

    .chat-content {
      display: flex;
      height: 100%;
    }

    .user-list {
      width: 25%;
      background: #f5f6fa;
      padding: 1rem;
      border-right: 1px solid #dcdde1;
      overflow-y: auto;
    }

    .user {
      padding: 15px;
      cursor: pointer;
      border-radius: 8px;
      margin-bottom: 10px;
      background: #dfe6e9;
      transition: 0.3s;
    }

    .user:hover {
      background-color: #b2bec3;
    }

    .user h4 {
      color: #2d3436;
      font-size: 1rem;
      font-weight: bold;
    }

    .chat-window {
      width: 75%;
      display: flex;
      flex-direction: column;
      background-color: #dfe6e9;
    }

    .chat-header {
      padding: 1rem;
      background-color: #2d3436;
      color: #ffffff;
      font-weight: bold;
      text-align: center;
    }

    .chat-messages {
      flex: 1;
      padding: 1rem;
      overflow-y: auto;
      background: #ffffff;
    }

    .message {
      margin: 10px 0;
      display: flex;
    }

    .message.sent {
      justify-content: flex-end;
    }

    .message.received {
      justify-content: flex-start;
    }

    .message p {
      padding: 10px 15px;
      border-radius: 15px;
      max-width: 70%;
      font-size: 0.95rem;
      box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);
    }

    .message.sent p {
      background-color: #74b9ff;
      color: #ffffff;
    }

    .message.received p {
      background-color: #dfe6e9;
      color: #2d3436;
    }

    .message-input {
      padding: 1rem;
      display: flex;
      background-color: #2d3436;
    }

    .message-input input[type="text"] {
      flex: 1;
      padding: 10px;
      font-size: 1rem;
      border: none;
      border-radius: 8px;
      margin-right: 10px;
      outline: none;
      background: #ffffff;
      color: #2d3436;
    }

    .message-input button {
      padding: 10px 20px;
      font-size: 1rem;
      color: #ffffff;
      background-color: #74b9ff;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: 0.3s;
    }

    .message-input button:hover {
      background-color: #0984e3;
    }
  </style>
</head>
<body>
<!-- NavBar -->
<div class="navbar">
  <div class="brand">Chat App</div>
  <div class="nav-links">
    <a href="{{ route('login') }}">Login</a>
    <a href="{{ route('register') }}">Register</a>
  </div>
</div>

<!-- Chat Container -->
<div class="chat-container">
  <div class="chat-content">
    <div class="user-list">
      @foreach ($users as $user)
        <div class="user">
          <h4>{{ $user->name }}</h4>
          <p>{{ $user->last_message ?? 'No messages yet' }}</p>
        </div>
      @endforeach
    </div>

    <div class="chat-window">
      <div class="chat-header">
        Chat with {{ $selectedUser->name ?? 'a user' }}
      </div>

      <div class="chat-messages">
        @foreach ($messages as $message)
          <div class="message {{ $message->sender_id == auth()->id() ? 'sent' : 'received' }}">
            <p>{{ $message->content }}</p>
          </div>
        @endforeach
      </div>

      <div class="message-input">
        <form action="/message" method="post">
          @csrf
          <input type="hidden" name="user_id" value="{{ $selectedUser->id }}">
          <input type="text" name="message" placeholder="Type a message..." required>
          <button type="submit">Send</button>
        </form>
      </div>
    </div>
  </div>
</div>
</body>
</html>
