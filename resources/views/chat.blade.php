<!-- resources/views/chat.blade.php -->
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Ilovasi</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            display: flex;
            font-family: Arial, sans-serif;
            margin: 0;
            height: 100vh;
        }
        #userList {
            width: 30%;
            background-color: #f0f0f0;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
        }
        #messageArea {
            width: 70%;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }
        #messages {
            flex-grow: 1;
            border: 1px solid #ccc;
            padding: 10px;
            overflow-y: auto;
            margin-bottom: 10px;
            max-height: 500px;
        }
        .message-input-form {
            display: flex;
            gap: 10px;
        }
        .message-input-form input[type="text"] {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .message-input-form button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .message-input-form button:hover {
            background-color: #0056b3;
        }
        .user-item {
            padding: 10px;
            margin: 5px 0;
            cursor: pointer;
            border-radius: 4px;
            text-decoration: none;
            color: black;
            display: block;
        }
        .user-item:hover {
            background-color: #e0e0e0;
        }
        .user-item.active {
            background-color: #007bff;
            color: white;
        }
        .message {
            margin: 10px 0;
            padding: 10px;
            border-radius: 4px;
            max-width: 70%;
        }
        .message.sent {
            background-color: #007bff;
            color: white;
            margin-left: auto;
        }
        .message.received {
            background-color: #f0f0f0;
            margin-right: auto;
        }
    </style>
</head>
<body>
<div id="userList">
    <h2>Foydalanuvchilar</h2>
    <div id="users">
        @foreach($users as $user)
            <a href="{{ route('chat.show', $user->id) }}" class="user-item @if(isset($selectedUser) && $selectedUser->id == $user->id) active @endif">
                {{ $user->name }}
            </a>
        @endforeach
    </div>
</div>
<div id="messageArea">
    <h2>Xabarlar @if(isset($selectedUser)) - {{ $selectedUser->name }} bilan @endif</h2>
    <div id="messages">
        @if(isset($messages))
            @foreach($messages as $message)
                <div class="message {{ $message->sender_id == auth()->id() ? 'sent' : 'received' }}">
                    {{ $message->message }}
                </div>
            @endforeach
        @else
            <p>Suhbatlashish uchun foydalanuvchini tanlang</p>
        @endif
    </div>
    @if(isset($selectedUser))
        <form action="{{ route('chat.send') }}" method="POST" class="message-input-form">
            @csrf
            <input type="hidden" name="receiver_id" value="{{ $selectedUser->id }}">
            <input type="text" name="message" placeholder="Xabar yozing" required>
            <button type="submit">Yuborish</button>
        </form>
    @endif
</div>
</body>
</html>
