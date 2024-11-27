const balls = [];
const ballCount = 50;
const speed = 5;

function getRandomColor() {
    return `rgb(${Math.random() * 255}, ${Math.random() * 255}, ${Math.random() * 255})`;
}

function createBall() {
    const ball = document.createElement('div');
    ball.classList.add('ball');
    ball.style.backgroundColor = getRandomColor();
    ball.style.left = `${Math.random() * window.innerWidth}px`;
    ball.style.top = `${Math.random() * window.innerHeight}px`;
    ball.dx = (Math.random() - 0.5) * speed * 2;
    ball.dy = (Math.random() - 0.5) * speed * 2;
    document.body.appendChild(ball);
    return ball;
}

function updatePosition(ball) {
    let x = parseFloat(ball.style.left);
    let y = parseFloat(ball.style.top);

    if (x <= 0 || x + 20 >= window.innerWidth) ball.dx *= -1;
    if (y <= 0 || y + 20 >= window.innerHeight) ball.dy *= -1;

    ball.style.left = `${x + ball.dx}px`;
    ball.style.top = `${y + ball.dy}px`;
}

function checkCollisions(ball1, ball2) {
    const x1 = parseFloat(ball1.style.left) + 10;
    const y1 = parseFloat(ball1.style.top) + 10;
    const x2 = parseFloat(ball2.style.left) + 10;
    const y2 = parseFloat(ball2.style.top) + 10;

    const dx = x2 - x1;
    const dy = y2 - y1;
    const distance = Math.sqrt(dx * dx + dy * dy);

    if (distance < 20) {
        ball1.style.backgroundColor = getRandomColor();
        ball2.style.backgroundColor = getRandomColor();
    }
}

function animate() {
    balls.forEach((ball, i) => {
        updatePosition(ball);
        for (let j = i + 1; j < balls.length; j++) {
            checkCollisions(ball, balls[j]);
        }
    });

    requestAnimationFrame(animate);
}

// Создаём шарики
for (let i = 0; i < ballCount; i++) {
    balls.push(createBall());
}

// Анимация
animate();
