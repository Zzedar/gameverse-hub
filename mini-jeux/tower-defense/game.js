const canvas = document.getElementById("gameCanvas");
const ctx = canvas.getContext("2d");

canvas.width = 800;
canvas.height = 500;

// Images
const enemyImg = new Image();
enemyImg.src = "assets/enemy.png";

const towerImg = new Image();
towerImg.src = "assets/tower.png";

const projectileImg = new Image();
projectileImg.src = "assets/projectile.png";

const heartImg = new Image();
heartImg.src = "assets/heart.png"; // Icône de cœur pour les vies

// Variables globales
let towers = [];
let enemies = [];
let projectiles = [];
let score = 0;
let lives = 3; // 💖 Nombre de vies
let gameOver = false;

// Classe Tour
class Tower {
    constructor(x, y) {
        this.x = x;
        this.y = y;
        this.range = 100;
    }

    draw() {
        ctx.drawImage(towerImg, this.x, this.y, 50, 50);
    }
}

// Classe Ennemi
class Enemy {
    constructor(x, y, speed) {
        this.x = x;
        this.y = y;
        this.speed = speed;
        this.health = 3;
    }

    move() {
        this.x += this.speed;

        // Si l'ennemi atteint la fin du chemin -> PERTE DE VIE + effet visuel
        if (this.x > canvas.width) {
            lives--; // ❌ Perdre une vie
            flashScreen(); // 🔥 Effet visuel de dommage
            let index = enemies.indexOf(this);
            if (index > -1) enemies.splice(index, 1); // Retirer l'ennemi
        }
    }

    draw() {
        ctx.drawImage(enemyImg, this.x, this.y, 40, 40);
    }
}

// Classe Projectile
class Projectile {
    constructor(x, y, target) {
        this.x = x;
        this.y = y;
        this.target = target;
        this.speed = 3;
    }

    move() {
        let dx = this.target.x - this.x;
        let dy = this.target.y - this.y;
        let distance = Math.sqrt(dx * dx + dy * dy);

        if (distance > 2) {
            this.x += (dx / distance) * this.speed;
            this.y += (dy / distance) * this.speed;
        } else {
            this.target.health -= 1;
            if (this.target.health <= 0) {
                let index = enemies.indexOf(this.target);
                if (index > -1) enemies.splice(index, 1);
                score += 10;
            }
            projectiles.splice(projectiles.indexOf(this), 1);
        }
    }

    draw() {
        ctx.drawImage(projectileImg, this.x, this.y, 10, 10);
    }
}

// Effet visuel lorsqu'on perd une vie
function flashScreen() {
    document.body.style.backgroundColor = "red";
    setTimeout(() => {
        document.body.style.backgroundColor = "black";
    }, 200);
}

// Génération des ennemis
setInterval(() => {
    if (!gameOver) {
        enemies.push(new Enemy(0, Math.random() * canvas.height, 1));
    }
}, 2000);

// Placement des tours
canvas.addEventListener("click", (event) => {
    if (!gameOver) {
        towers.push(new Tower(event.clientX - canvas.offsetLeft, event.clientY - canvas.offsetTop));
    }
});

// Tir automatique
setInterval(() => {
    if (!gameOver) {
        towers.forEach(tower => {
            let nearestEnemy = null;
            let minDistance = Infinity;

            enemies.forEach(enemy => {
                let distance = Math.hypot(tower.x - enemy.x, tower.y - enemy.y);
                if (distance < minDistance && distance <= tower.range) {
                    minDistance = distance;
                    nearestEnemy = enemy;
                }
            });

            if (nearestEnemy) {
                projectiles.push(new Projectile(tower.x + 25, tower.y + 25, nearestEnemy));
            }
        });
    }
}, 1000);

// Vérifier si le jeu est terminé
function checkGameOver() {
    if (lives <= 0) {
        gameOver = true;
        saveScore();
        showGameOverScreen();
    }
}

// Enregistrement du score
function saveScore() {
    fetch("../enregistrer-score.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `score=${score}&jeu=Tower Defense Arena`
    })
        .then(response => response.json())
        .then(data => {
            console.log("Score enregistré :", data);
        })
        .catch(error => console.error("Erreur enregistrement du score :", error));
}

// Afficher l'écran de Game Over avec un bouton "Rejouer"
function showGameOverScreen() {
    let gameOverDiv = document.createElement("div");
    gameOverDiv.id = "gameOverScreen";
    gameOverDiv.innerHTML = `
        <h1>💀 GAME OVER 💀</h1>
        <p>Score: ${score}</p>
        <button onclick="restartGame()">🔄 Rejouer</button>
    `;
    document.body.appendChild(gameOverDiv);
}

// Redémarrer le jeu
function restartGame() {
    document.getElementById("gameOverScreen").remove();
    towers = [];
    enemies = [];
    projectiles = [];
    score = 0;
    lives = 3;
    gameOver = false;
    gameLoop();
}

// Animation du jeu
function gameLoop() {
    if (!gameOver) {
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        towers.forEach(tower => tower.draw());
        enemies.forEach(enemy => {
            enemy.move();
            enemy.draw();
        });
        projectiles.forEach(projectile => {
            projectile.move();
            projectile.draw();
        });

        // Afficher score et vies
        ctx.fillStyle = "white";
        ctx.font = "20px Arial";
        ctx.fillText("Score: " + score, 10, 20);

        // 🔥 Afficher les vies avec des cœurs
        for (let i = 0; i < lives; i++) {
            ctx.drawImage(heartImg, 10 + i * 30, 30, 25, 25);
        }

        checkGameOver();
        requestAnimationFrame(gameLoop);
    }
}

gameLoop();
