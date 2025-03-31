const canvas = document.getElementById("gameCanvas");
const ctx = canvas.getContext("2d");

// Chargement des images
const playerSprite = new Image();
playerSprite.src = "assets/player_spritesheet.png"; // Spritesheet du joueur

const obstacleImg = new Image();
obstacleImg.src = "assets/obstacle.png";

const backgroundImg = new Image();
backgroundImg.src = "assets/background.png";

// Chargement des sons
const jumpSound = new Audio("assets/jump.mp3");
const collisionSound = new Audio("assets/collision.mp3");
const scoreSound = new Audio("assets/score.mp3");

// Joueur avec animation
let player = {
    x: 50,
    y: canvas.height - 70,
    width: 40,
    height: 40,
    offsetX: 0, // 📌 Décalage pour mieux coller au sprite
    offsetY: 0, // 📌 Décalage vertical
    velocityY: 0,
    gravity: 1,
    jumpPower: -15,
    grounded: false,

    // Animation
    frameIndex: 0,
    frameCount: 7,
    frameWidth: 64,
    frameHeight: 64,
    frameDelay: 6,
    frameCounter: 0,
    jumpFrameIndex: 5
};

// Obstacles
let obstacles = [];
let obstacleSpeed = 5;
let spawnRate = 90;
let frameCount = 0;
let score = 0;
let gameOver = false;

// Défilement du fond
let bgX = 0;
const bgSpeed = 2;

// Gestion des touches
document.addEventListener("keydown", function(event) {
    if ((event.key === " " || event.key === "ArrowUp") && player.grounded) {
        player.velocityY = player.jumpPower;
        player.grounded = false;
        jumpSound.play();
    }
});

// 👇 Support mobile : touche l'écran pour sauter
canvas.addEventListener("touchstart", function () {
    if (player.grounded && !gameOver) {
        player.velocityY = player.jumpPower;
        player.grounded = false;
        jumpSound.play();
    }
});

// Fonction pour sauvegarder le score
function saveScore() {
    console.log("Envoi du score :", score); // ✅ Vérification dans la console

    fetch("../enregistrer-score.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `score=${score}&jeu=Pixel Runner`
    })
        .then(response => response.json())
        .then(data => {
            console.log("Réponse du serveur :", data);
            alert("💀 Game Over ! Score : " + score);
            document.location.reload(); // Recharge la page après l’envoi du score
        })
        .catch(error => console.error("Erreur enregistrement du score :", error));
}

// Fonction `gameOverAction` appelée lors d'une collision
function gameOverAction() {
    if (!gameOver) {
        gameOver = true;
        collisionSound.play();
        saveScore(); // 🔥 Appel de la fonction pour sauvegarder le score
    }
}

// Mise à jour du jeu
function update() {
    if (gameOver) return;

    // Gravité
    player.velocityY += player.gravity;
    player.y += player.velocityY;

    // Collision avec le sol
    if (player.y >= canvas.height - player.height) {
        player.y = canvas.height - player.height;
        player.velocityY = 0;
        player.grounded = true;
    } else {
        player.grounded = false;
    }

    // Défilement du fond
    bgX -= bgSpeed;
    if (bgX <= -canvas.width) {
        bgX = 0;
    }

    // Animation du joueur
    player.frameCounter++;
    if (player.frameCounter >= player.frameDelay) {
        if (player.grounded) {
            player.frameIndex = (player.frameIndex + 1) % player.frameCount;
        } else {
            player.frameIndex = player.jumpFrameIndex;
        }
        player.frameCounter = 0;
    }

    // Génération des obstacles
    frameCount++;
    if (frameCount % spawnRate === 0) {
        let height = 50;
        obstacles.push({ x: canvas.width, y: canvas.height - height, width: 50, height: height });
    }

    // Déplacement des obstacles
    for (let i = 0; i < obstacles.length; i++) {
        obstacles[i].x -= obstacleSpeed;
        if (obstacles[i].x + obstacles[i].width < 0) {
            obstacles.splice(i, 1);
            score++;
            scoreSound.play();
        }
    }

    // Détection des collisions
    for (let obstacle of obstacles) {
        if (
            player.x < obstacle.x + obstacle.width &&
            player.x + player.width > obstacle.x &&
            player.y < obstacle.y + obstacle.height &&
            player.y + player.height > obstacle.y
        ) {
            gameOverAction(); // 🔥 Déclenchement du Game Over et enregistrement du score
            return; // Sort de la boucle pour éviter plusieurs enregistrements
        }
    }

    draw();
    requestAnimationFrame(update);
}

// Fonction de dessin
function draw() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // Dessiner le fond
    ctx.drawImage(backgroundImg, bgX, 0, canvas.width, canvas.height);
    ctx.drawImage(backgroundImg, bgX + canvas.width, 0, canvas.width, canvas.height);

    // Dessiner le joueur avec animation
    ctx.drawImage(
        playerSprite,
        player.frameIndex * player.frameWidth, 0,
        player.frameWidth, player.frameHeight,
        player.x, player.y,
        player.width, player.height
    );

    // Dessiner les obstacles
    for (let obstacle of obstacles) {
        ctx.drawImage(obstacleImg, obstacle.x, obstacle.y, obstacle.width, obstacle.height);
    }

    // Afficher le score
    ctx.fillStyle = "white";
    ctx.font = "20px Arial";
    ctx.fillText("Score: " + score, 20, 30);
}

// Démarrer le jeu
update();
