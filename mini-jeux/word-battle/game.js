const wordInput = document.getElementById("word-input");// correspond au champ dans ton HTML
const playButton = document.getElementById("submit-word"); // correspond au bouton "Valider"
const playerWordDisplay = document.getElementById("player-word");
const aiWordDisplay = document.getElementById("ai-word");
const roundResult = document.getElementById("round-result");
const playerScoreDisplay = document.getElementById("player-score");
const aiScoreDisplay = document.getElementById("ai-score");

const lettersContainer = document.getElementById("letters");

const alphabet = "abcdefghijklmnopqrstuvwxyz";
const frequentLetters = "aeiourtnslcm"; // lettres les plus courantes en FR

// Exemple : générer 7 lettres aléatoires
function generateLetters() {
    const letters = [];
    for (let i = 0; i < 7; i++) {
        const useCommon = Math.random() < 0.7; // 70% de lettres fréquentes
        const letter = useCommon
            ? frequentLetters[Math.floor(Math.random() * frequentLetters.length)]
            : alphabet[Math.floor(Math.random() * alphabet.length)];
        letters.push(letter);
    }
    lettersContainer.textContent = letters.join(" ");
}

// Appeler la fonction au démarrage
generateLetters();

// ⏱️ Timer
let timeLeft = 30;
const timeDisplay = document.getElementById("time");

function updateTimer() {
    if (timeLeft > 0) {
        timeLeft--;
        timeDisplay.textContent = timeLeft;
    } else {
        clearInterval(timerInterval);
        showGameOver(); // Fin du jeu quand le temps est écoulé
    }
}

const timerInterval = setInterval(updateTimer, 1000);

let playerScore = 0;
let aiScore = 0;
let round = 1;

// 📌 Liste de mots valides (exemple, peut être remplacée par un dictionnaire externe)
let validWords = [];

// 📌 Générer un mot aléatoire pour l'IA
function generateAiWord() {
    const availableLetters = lettersContainer.textContent.replace(/\s+/g, "").toLowerCase().split("");

    // Filtrer les mots valides qui peuvent être construits avec ces lettres
    const possibleWords = validWords.filter(word => {
        const tempLetters = [...availableLetters];
        for (let letter of word) {
            const index = tempLetters.indexOf(letter);
            if (index === -1) return false;
            tempLetters.splice(index, 1);
        }
        return true;
    });

    // Si aucun mot possible, renvoyer un mot bidon ou vide
    if (possibleWords.length === 0) return "";

    // Sinon, choisir un mot au hasard parmi ceux possibles
    return possibleWords[Math.floor(Math.random() * possibleWords.length)];
}


// 📌 Vérifier si le mot du joueur est valide
function isValidWord(word) {
    const availableLetters = lettersContainer.textContent.replace(/\s+/g, "").toLowerCase().split(""); // lettres dispo
    const usedLetters = word.toLowerCase().split("");

    // Vérifie que chaque lettre du mot est présente dans les lettres disponibles (en tenant compte du nombre)
    for (let letter of usedLetters) {
        const index = availableLetters.indexOf(letter);
        if (index === -1) return false; // Lettre absente
        availableLetters.splice(index, 1); // Retire la lettre utilisée
    }

    return validWords.includes(word.toLowerCase());
}

// Charger les mots depuis le fichier JSON
fetch("mots-fr.json")
    .then(response => response.json())
    .then(data => {
        validWords = data;
        console.log("✅ Liste de mots chargée :", validWords.length + " mots");
    })
    .catch(error => console.error("❌ Erreur chargement mots :", error));


// 📌 Jouer une manche
function playRound() {
    let playerWord = wordInput.value.trim().toLowerCase();
    let aiWord = generateAiWord();

    if (validWords.length === 0) {
        roundResult.textContent = "⏳ Chargement des mots... Attendez une seconde.";
        roundResult.style.color = "orange";
        return;
    }


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
