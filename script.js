const canvas = document.querySelector('canvas');
let width = (canvas.width = window.innerWidth);
let height = (canvas.height = window.innerHeight);
const ctx = canvas.getContext('2d');

function random(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

// ctx.fillStyle = 'rgb(0, 0, 0)';
// ctx.fillRect(0, 0, width, height);

class Ball {
    constructor(x, y, velX, velY, color, size) {
        this.x = x;                           // "this" sert a définir les propriétés de chaque balle individuellement
        this.y = y;
        this.velX = velX;   
        this.velY = velY;
        this.color = color;
        this.size = size;
    }

    draw() {
        ctx.beginPath();
        ctx.fillStyle = this.color;
        ctx.arc(this.x, this.y, this.size, 0, 2 * Math.PI);
        ctx.fill();
    }

    update() {
        // Rebond droite/guache
        if ((this.x + this.size) >= width) {               // Si position - taille balle est inférieure ou égale à 0 alors la vitesse est inversée
            this.velX = -(this.velX);
        }

        if ((this.x - this.size) <= 0) {
            this.velX = -(this.velX);
        }

        // Rebond haut/bas
        if ((this.y + this.size) >= height) {
            this.velY = -(this.velY);
        }

        if ((this.y - this.size) <= 0) {   
            this.velY = -(this.velY);
        }

        //Actuallise la position de la balle(+= affecte la valeur de la variable à elle-même plus la valeur de l'opérateur)
        this.x += this.velX;
        this.y += this.velY;
    }
    // detecte et change couleur si collision
    collisionDetect() {
        for (const ball of balls) {
            if (this !== ball) {
                const dx = this.x - ball.x;
                const dy = this.y - ball.y;
                const distance = Math.sqrt(dx * dx + dy * dy);

                if (distance < this.size + ball.size) {
                    ball.color = this.color = `rgb(${random(0, 255)}, ${random(0, 255)}, ${random(0, 255)})`;
                }
            }
        }
    }
}

const balls = [];

// Créer les balles
while (balls.length < 150) { // hehe
    const size = random(10, 30); 
    const ball = new Ball(
        // La position de départ de la balle - au moins une taille de balle
        // et au plus la largeur du canvas moins la taille de la balle
        random(0 + size, width - size),
        random(0 + size, height - size),
        random(-7, 7),
        random(-7, 7),
        // Couleur aléatoire
        `rgb(${random(0, 255)}, ${random(0, 255)}, ${random(0, 255)})`,
        size
    );
    balls.push(ball);
}
// Handle window resizing
window.addEventListener('resize', () => {
    width = canvas.width = window.innerWidth;
    height = canvas.height = window.innerHeight;
});

// boucle
function loop() {
    ctx.fillStyle = 'rgb(210, 122, 200)';
    ctx.fillRect(0, 0, width, height); 
 
    for (const ball of balls) {
        ball.draw();
        ball.update();
        ball.collisionDetect();
    }

    requestAnimationFrame(loop);
}

//  弹弹球 BOUNCING BALLS 弹弹球
loop();