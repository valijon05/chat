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
      font-family: 'Poppins', Arial, sans-serif;
    }

    body {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background: linear-gradient(135deg, #000000, #6c757d);
      color: #ffffff;
      flex-direction: column;
    }

    .navbar {
      width: 100%;
      background: linear-gradient(135deg, #0056b3, #007bff);
      padding: 15px 20px;
      color: #ffffff;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .navbar .brand {
      font-size: 1.8rem;
      font-weight: bold;
    }

    .navbar .nav-links {
      display: flex;
      gap: 20px;
    }

    .navbar .nav-links a {
      color: #ffffff;
      text-decoration: none;
      font-size: 1.1rem;
      padding: 8px 12px;
      border-radius: 6px;
      transition: all 0.3s ease-in-out;
    }

    .navbar .nav-links a:hover {
      background: rgba(255, 255, 255, 0.2);
      transform: scale(1.05);
    }

    .chat-container {
      display: flex;
      width: 90%;
      height: 80vh;
      background: #ffffff;
      box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
      border-radius: 12px;
      overflow: hidden;
    }

    .user-list {
      width: 30%;
      background: #f9f9f9;
      border-right: 2px solid #ddd;
      overflow-y: auto;
    }

    .user {
      padding: 15px;
      cursor: pointer;
      border-bottom: 1px solid #e0e0e0;
      transition: all 0.3s ease-in-out;
    }

    .user:hover {
      background: #f0f8ff;
    }

    .user.active {
      background: #e6f7ff;
      border-left: 4px solid #ff001e;
    }

    .user h4 {
      font-size: 1rem;
      font-weight: bold;
      color: #333;
    }

    .user p {
      font-size: 0.9rem;
      color: #666;
    }

    .chat-window {
      width: 70%;
      display: flex;
      flex-direction: column;
      background: #ffffff;
    }

    .chat-header {
      padding: 20px;
      background: linear-gradient(135deg, #050505, #0056b3);
      color: #ffffff;
      font-size: 1.2rem;
      font-weight: bold;
      text-align: center;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .chat-messages {
      flex: 1;
      padding: 20px;
      overflow-y: auto;
      background: #f8f9fa;
    }

    .message {
      margin-bottom: 20px;
      display: flex;
      flex-direction: column;
    }

    .message.sent {
      align-items: flex-end;
    }

    .message p {
      max-width: 70%;
      padding: 12px 18px;
      border-radius: 20px;
      font-size: 0.95rem;
      line-height: 1.5;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .message.sent p {
      background: linear-gradient(135deg, #007bff, #0056b3);
      color: #ffffff;
      border-bottom-right-radius: 6px;
    }

    .message.received p {
      background: #e9ecef;
      color: #333;
      border-bottom-left-radius: 6px;
    }

    .message-input {
      padding: 15px;
      display: flex;
      border-top: 2px solid #ddd;
      background: #f8f9fa;
    }

    .message-input input[type="text"] {
      flex: 1;
      padding: 12px;
      font-size: 1rem;
      border: 2px solid #ddd;
      border-radius: 8px;
      outline: none;
      transition: all 0.3s ease-in-out;
    }

    .message-input input[type="text"]:focus {
      border-color: #007bff;
      box-shadow: 0 0 8px rgba(0, 123, 255, 0.3);
    }

    .message-input button {
      padding: 12px 20px;
      margin-left: 12px;
      font-size: 1rem;
      color: #ffffff;
      background: linear-gradient(135deg, #000000, #0056b3);
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s ease-in-out;
    }

    .message-input button:hover {
      background: linear-gradient(135deg, #0056b3, #003f7d);
      transform: scale(1.05);
    }

  </style>
</head>
<body>

<div class="chat-container">

  <div class="user-list">
    @foreach ($users as $user)
      <a href="/chat/{{ $user->id }}" class="user-link" id="user-{{ $user->id }}">
        <div class="user {{ isset($selectedUser) && $selectedUser->id == $user->id ? 'active' : '' }}">
          <h4>{{ $user->name }}</h4>
          <p>{{ $user->last_message ?? 'No messages yet' }}</p>
        </div>
      </a>
    @endforeach
  </div>


  <div class="chat-window">
    <div class="chat-header" id="chat-header">
      Chat with {{ $selectedUser->name ?? 'a user' }}
    </div>
    <div class="chat-messages" id="chat-messages">
      @foreach ($messages as $message)
        <div class="message {{ $message->sender_id == auth()->id() ? 'sent' : 'received' }}">
          <p>{{ $message->content }}</p>
        </div>
      @endforeach
    </div>


    <form action="/message" method="post" id="message-form">
      @csrf
      <input type="hidden" name="user_id" value="{{ $selectedUser->id }}">
      <div class="message-input">
        <input type="text" id="message" name="message" placeholder="Type a message..." required>
        <button type="submit">Send</button>
      </div>
    </form>
  </div>
</div>

<script>
  $(function() {
    let lastMessageId = 0;

    $('#message-form').submit(function(e) {
      e.preventDefault();

      const message = $('#message').val().trim();
      const userId = '{{ $selectedUser->id }}';

      if (!message) return alert('Iltimos, xabar kiriting.');

      $.post('/message', {
        message,
        user_id: userId,
        _token: '{{ csrf_token() }}'
      }).done(response => {
        $('#message').val('');
        updateMessages(response.messages);
      }).fail(() => console.error('Xabar yuborishda xato'));
    });

    function updateMessages(messages) {
      const container = $('#chat-messages').empty();
      messages.forEach(message => {
        const isSent = message.sender_id == {{ auth()->id() }};
        container.append(`<div class="message ${isSent ? 'sent' : 'received'}">
                                  <p>${message.content}</p>
                                </div>`);

        if (message.id > lastMessageId) {
          lastMessageId = message.id;
          showNotification(message.content);
          playSound();
        }
      });
    }

    function fetchMessages() {
      const userId = '{{ $selectedUser->id }}';
      $.get(`/messages/${userId}`).done(response => updateMessages(response.messages)).fail(() => console.error('Xabarlar olishda xato'));
    }

    setInterval(fetchMessages, 5000);

    function showNotification(message) {
      if (Notification.permission === 'granted') {
        new Notification('Yangi xabar', { body: message });
      } else if (Notification.permission !== 'denied') {
        Notification.requestPermission().then(permission => {
          if (permission === 'granted') {
            new Notification('Yangi xabar', { body: message });
          }
        });
      }
    }

    function playSound() {
      const sound = new Audio('{{ asset("sounds/notification.mp3") }}');
      sound.play();
    }
  });
</script>


</body>
</html>