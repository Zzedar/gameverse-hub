const wordInput = document.getElementById("player-word"); // correspond au champ dans ton HTML
const playButton = document.getElementById("submit-word"); // correspond au bouton "Valider"
const playerWordDisplay = document.getElementById("player-word");
const aiWordDisplay = document.getElementById("ai-word");
const roundResult = document.getElementById("round-result");
const playerScoreDisplay = document.getElementById("player-score");
const aiScoreDisplay = document.getElementById("ai-score");

const lettersContainer = document.getElementById("letters");

// Exemple : générer 7 lettres aléatoires
function generateLetters() {
    const alphabet = "abcdefghijklmnopqrstuvwxyz";
    const letters = [];
    for (let i = 0; i < 7; i++) {
        const randomLetter = alphabet[Math.floor(Math.random() * alphabet.length)];
        letters.push(randomLetter);
    }
    lettersContainer.textContent = letters.join(" ");
}

// Appeler la fonction au démarrage
generateLetters();


let playerScore = 0;
let aiScore = 0;
let round = 1;

// 📌 Liste de mots valides (exemple, peut être remplacée par un dictionnaire externe)
const validWords = ["chat", "chien", "maison", "table", "soleil", "étoile", "livre", "ordinateur", "internet", "musique", "bateau", "avion", "voiture", "fenêtre", "porte", "clavier"];

// 📌 Générer un mot aléatoire pour l'IA
function generateAiWord() {
    return validWords[Math.floor(Math.random() * validWords.length)];
}

// 📌 Vérifier si le mot du joueur est valide
function isValidWord(word) {
    return validWords.includes(word.toLowerCase()); // Vérification en minuscule
}

// 📌 Jouer une manche
function playRound() {
    let playerWord = wordInput.value.trim().toLowerCase();
    let aiWord = generateAiWord();

    if (!isValidWord(playerWord)) {
        roundResult.textContent = "❌ Mot invalide ! Essayez un vrai mot.";
        roundResult.style.color = "red";
        return;
    }


    playerWordDisplay.textContent = playerWord;
    aiWordDisplay.textContent = aiWord;

    // 📌 Déterminer le gagnant en fonction de la longueur des mots
    if (playerWord.length > aiWord.length) {
        playerScore++;
        roundResult.textContent = `✅ Vous avez gagné cette manche (${playerWord.length} lettres contre ${aiWord.length}) !`;
        roundResult.style.color = "green";
    } else if (playerWord.length < aiWord.length) {
        aiScore++;
        roundResult.textContent = `❌ L'IA gagne cette manche (${aiWord.length} lettres contre ${playerWord.length})...`;
        roundResult.style.color = "red";
    } else {
        roundResult.textContent = "🔄 Égalité !";
        roundResult.style.color = "gray";
    }

    // 📌 Mettre à jour le score
    playerScoreDisplay.textContent = playerScore;
    aiScoreDisplay.textContent = aiScore;
    round++;

    wordInput.value = "";

    // 📌 Vérifier si le jeu est terminé
    if (round > 5) {
        setTimeout(showGameOver, 1000);
    }
}

// 📌 Afficher la fin du jeu et enregistrer le score
function showGameOver() {
    let message = playerScore > aiScore ? "🏆 Victoire !" : "💀 Défaite...";
    if (playerScore === aiScore) message = "🤝 Match nul.";

    alert(`${message}\nScore final : ${playerScore} - ${aiScore}`);
    saveScore();
    showReplayButton();
}

// 📌 Enregistrer le score
function saveScore() {
    fetch("../enregistrer-score.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `score=${playerScore}&jeu=Word Battle`
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

// 📌 Jouer une manche avec Entrée
wordInput.addEventListener("keydown", function (event) {
    if (event.key === "Enter") {
        event.preventDefault();
        playRound();
    }
});

// 📌 Lancer le jeu
playButton.addEventListener("click", function () {
    playRound();
});
