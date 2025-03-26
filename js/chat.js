document.addEventListener("DOMContentLoaded", function () {
    let sender = "<?php echo $_SESSION['user']['username']; ?>"; // Récupère l'utilisateur connecté
    let lastMessageCount = 0; // Variable pour suivre le nombre de messages

    function loadMessages() {
        fetch("../../php/chat/get-messages.php")
            .then(response => response.json())
            .then(messages => {
                let chatBox = document.getElementById("chat-box");

                if (!Array.isArray(messages)) {
                    console.error("⚠ Données reçues invalides", messages);
                    return;
                }

                // Vérifier si le nombre de messages a changé avant de tout recharger
                if (messages.length === lastMessageCount) {
                    return; // Si le nombre de messages est le même, ne rien faire (évite le clignotement)
                }

                chatBox.innerHTML = ""; // Réinitialiser uniquement si les messages ont changé
                lastMessageCount = messages.length; // Mettre à jour le nombre de messages stockés

                messages.forEach(msg => {
                    let messageElement = document.createElement("div");
                    messageElement.classList.add("message");

                    // Vérifier si c'est l'utilisateur actuel qui envoie le message
                    if (msg.sender === sender) {
                        messageElement.classList.add("sent"); // Appliquer une classe CSS pour le différencier
                    } else {
                        messageElement.classList.add("received");
                    }

                    messageElement.innerHTML = `<strong>${msg.sender}:</strong> ${msg.message}`;
                    chatBox.appendChild(messageElement);
                });

                chatBox.scrollTop = chatBox.scrollHeight; // Défilement automatique vers le bas
            })
            .catch(error => console.error("⚠ Erreur lors du chargement des messages :", error));
    }


// Recharger toutes les 3 secondes
    setInterval(loadMessages, 5000);

    /** 🔹 Fonction pour charger les utilisateurs en ligne */
    function loadOnlineUsers() {
        fetch("../../get-online-users.php")
            .then(response => response.json())
            .then(users => {
                let usersList = document.getElementById("users-list");
                usersList.innerHTML = "";

                if (!Array.isArray(users)) {
                    console.error("⚠ Données reçues invalides", users);
                    return;
                }

                users.forEach(user => {
                    let listItem = document.createElement("li");
                    listItem.textContent = user;
                    usersList.appendChild(listItem);
                });
            })
            .catch(error => console.error("⚠ Erreur chargement utilisateurs en ligne:", error));
    }

    setInterval(loadOnlineUsers, 5000); // Rafraîchir toutes les 5 secondes
    loadOnlineUsers(); // Chargement immédiat


    function sendMessage() {
        let messageInput = document.getElementById("message-input");
        let receiver = document.getElementById("receiver").value; // Récupère le destinataire
        let message = messageInput.value.trim();

        if (message === "") return;

        fetch("../../php/chat/send-message.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ sender: sender, receiver: receiver, message: message })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messageInput.value = "";
                    loadMessages();
                } else {
                    console.error("⚠ Erreur lors de l'envoi :", data.error);
                }
            })
            .catch(error => console.error("⚠ Erreur réseau :", error));
    }

    document.getElementById("send-button").addEventListener("click", sendMessage);
    document.getElementById("message-input").addEventListener("keypress", function (event) {
        if (event.key === "Enter") {
            sendMessage();
        }
    });

    loadMessages();
});
