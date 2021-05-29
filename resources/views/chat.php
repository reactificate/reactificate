<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chat</title>
    <script src="socket.js"></script>
</head>
<body style="margin: 0 20px">
<div style="margin: 10px 0">
    <input type="text" placeholder="Your username" id="username">
</div>
<div id="messages"></div>

<form id="formSendMessage">
    <textarea style="margin-top: 10px" name="message" id="message" cols="30" rows="5"
              placeholder="Write your message..."></textarea>
    <div>
        <button type="submit">Send</button>
    </div>
</form>
<script>
    const websocket = new Reactificate.WSClient('ws://0.0.0.0:9200/ws/chat');
    const elMessages = document.getElementById('messages');
    const inputUsername = document.getElementById('username');
    const textareaMessage = document.getElementById('message');

    websocket.onOpen(() => console.log('Connection established'))
    websocket.onClose(() => console.log('Connection closed'))
    websocket.onError((error) => console.log(error));

    websocket.onMessage(function (payload) {
        let message = JSON.parse(payload.data)
        if ('chat.message' === message.command) {
            let divMessage = document.createElement('div');
            let username = '<b>' + message.data.username + ': </b>';
            divMessage.innerHTML = '<br/>' + username + message.data.message;
            elMessages.append(divMessage)
        }
    });

    document.getElementById('formSendMessage').onsubmit = function (event) {
        event.preventDefault();

        websocket.send('chat.message', {
            username: inputUsername.value,
            message: textareaMessage.value
        }).then(() => {
            textareaMessage.value = '';
        });
    };
</script>
</body>
</html>