    // Toggle the visibility of the chat window
    function toggleChat() {
        const chatWindow = document.getElementById('chatWindow');
        chatWindow.style.display = chatWindow.style.display === 'none' ? 'block' : 'none';
    }

    // Send message to the chatbot
    function sendMessage() {
        const userInput = document.getElementById('userInput').value;
        if (userInput.trim() === '') {
            alert('Please enter a message.');
            return;
        }

        const chatBody = document.getElementById('chatBody');
        chatBody.innerHTML += '<p class="user"><strong>You:</strong> ' + userInput + '</p>';

        // Sending the user's input to PHP
        fetch('chatbot.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'message=' + encodeURIComponent(userInput)
        })
        .then(response => response.json())
        .then(data => {
            if (data.reply) {
                chatBody.innerHTML += '<p class="bot"><strong>Bot:</strong> ' + data.reply + '</p>';
            } else {
                chatBody.innerHTML += '<p class="bot"><strong>Bot:</strong> Sorry, something went wrong.</p>';
            }
            chatBody.scrollTop = chatBody.scrollHeight;  // Auto-scroll to the bottom
        })
        .catch(error => {
            console.error('Error:', error);
            chatBody.innerHTML += '<p class="bot"><strong>Bot:</strong> Error occurred.</p>';
            chatBody.scrollTop = chatBody.scrollHeight;  // Auto-scroll to the bottom
        });

        // Clear input field
        document.getElementById('userInput').value = '';
    }