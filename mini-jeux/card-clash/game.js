const playerCardsContainer = document.getElementById("player-cards");
const opponentCardsContainer = document.getElementById("opponent-cards");
const playButton = document.getElementById("play-button");
const playerCardDisplay = document.getElementById("player-card");
const opponentCardDisplay = document.getElementById("opponent-card");
const roundResult = document.getElementById("round-result");
const roundCounter = document.getElementById("round");
const playerScoreDisplay = document.getElementById("player-score");
const opponentScoreDisplay = document.getElementById("opponent-score");

let playerCards = [];
let opponentCards = [];
let playerScore = 0;
let opponentScore = 0;
let round = 1;
let selectedCard = null;

// 📌 Générer 5 cartes aléatoires (valeurs de 1 à 10)
function generateCards() {
    playerCards = Array.from({ length: 5 }, () => Math.floor(Math.random() * 10) + 1);
    opponentCards = Array.from({ length: 5 }, () => Math.floor(Math.random() * 10) + 1);
    renderCards();
}

// 📌 Afficher les cartes des joueurs
function renderCards() {
    playerCardsContainer.innerHTML = "";
    playerCards.forEach((value, index) => {
        const card = document.createElement("div");
        card.classList.add("card");
        card.textContent = value;
        card.addEventListener("click", () => selectCard(index));
        playerCardsContainer.appendChild(card);
    });

    // Masquer les cartes adverses (face cachée)
    opponentCardsContainer.innerHTML = "";
    for (let i = 0; i < opponentCards.length; i++) {
        const card = document.createElement("div");
        card.classList.add("card");
        card.textContent = "?";
        opponentCardsContainer.appendChild(card);
    }
}

// 📌 Sélectionner une carte du joueur
function selectCard(index) {
    selectedCard = playerCards[index];
    playButton.disabled = false;
}

// 📌 Animation des cartes jouées
function animateCards(playerCard, opponentCard) {
    playerCardDisplay.textContent = playerCard;
    opponentCardDisplay.textContent = "?";

    playerCardDisplay.style.transform = "translateX(-50px) scale(1.2)";
    setTimeout(() => {
        opponentCardDisplay.textContent = opponentCard;
        opponentCardDisplay.style.transform = "translateX(50px) scale(1.2)";
    }, 500);

    setTimeout(() => {
        playerCardDisplay.style.transform = "translateX(0) scale(1)";
        opponentCardDisplay.style.transform = "translateX(0) scale(1)";
    }, 1000);
}

// 📌 Effet de changement de fond selon le gagnant
function flashBackground(color) {
    document.body.style.backgroundColor = color;
    setTimeout(() => {
        document.body.style.backgroundColor = "#282c34";
    }, 500);
}

// 📌 Jouer une manche
function playRound() {
    if (selectedCard === null) return;

    let playerCard = selectedCard;
    let opponentCard = opponentCards[0]; // 📌 L'IA joue sa **première carte**

    animateCards(playerCard, opponentCard);

    setTimeout(() => {
        // 📌 Vérification du gagnant
        if (playerCard > opponentCard) {
            playerScore++;
            roundResult.textContent = "✅ Vous avez gagné cette manche !";
            flashBackground("green");

            // 🔥 **Supprimer uniquement la carte gagnée**
            playerCards = playerCards.filter((card, index) => card !== playerCard || index !== playerCards.indexOf(playerCard));
            opponentCards.shift();
        } else if (playerCard < opponentCard) {
            opponentScore++;
            roundResult.textContent = "❌ Vous avez perdu cette manche.";
            flashBackground("red");

            // 🔥 **Supprimer uniquement la carte jouée par le joueur**
            playerCards = playerCards.filter((card, index) => card !== playerCard || index !== playerCards.indexOf(playerCard));
            opponentCards.shift();
        } else {
            roundResult.textContent = "🔄 Égalité ! Les cartes restent en jeu.";
            flashBackground("gray");

            // ❌ **Aucune carte n'est retirée en cas d'égalité**
        }

        // 📌 Mettre à jour les cartes affichées
        renderCards();

        // 📌 Mise à jour du score et du tour
        playerScoreDisplay.textContent = playerScore;
        opponentScoreDisplay.textContent = opponentScore;
        round++;
        roundCounter.textContent = round;

        playButton.disabled = true;
        selectedCard = null;

        // 📌 Vérifier si le jeu est terminé
        if (round > 5 || playerCards.length === 0 || opponentCards.length === 0) {
            setTimeout(showGameOver, 1000);
        }
    }, 1000);
}

// 📌 Afficher le Game Over et enregistrer le score
function showGameOver() {
    let message = playerScore > opponentScore ? "🏆 Victoire !" : "💀 Défaite...";
    if (playerScore === opponentScore) message = "🤝 Match nul.";

    alert(`${message}\nScore final : ${playerScore} - ${opponentScore}`);
    saveScore();
    showReplayButton();
}

// 📌 Enregistrer le score
function saveScore() {
    fetch("../enregistrer-score.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `score=${playerScore}&jeu=Card Clash`
    })
        .then(response => response.json())
        .then(data => {
            console.log("Score enregistré :", data);
        })
        .catch(error => console.error("Erreur enregistrement du score :", error));
}

// 📌 Afficher le bouton "Rejouer"
function showReplayButton() {
    const replayButton = document.createElement("button");
    replayButton.textContent = "🔄 Rejouer";
    replayButton.style.display = "block";
    replayButton.style.margin = "20px auto";
    replayButton.style.padding = "10px 20px";
    replayButton.style.fontSize = "16px";
    replayButton.onclick = restartGame;
    document.body.appendChild(replayButton);
}

// 📌 Redémarrer le jeu
function restartGame() {
    document.location.reload();
}

// 📌 Lancer le jeu
playButton.addEventListener("click", playRound);
generateCards();
