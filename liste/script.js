const addButton = document.getElementById('addButton');
const itemInput = document.getElementById('itemInput');
const itemList = document.getElementById('itemList');
const textGroup = document.getElementById('textGroup');
const items = [];

addButton.addEventListener('click', function() {
    const itemText = itemInput.value.trim();
    if (itemText === '') return;

    // Add item to the list
    const listItem = document.createElement('li');
    listItem.textContent = itemText;

    // Add delete button to list item
    const deleteButton = document.createElement('button');
    deleteButton.textContent = '◄';
    deleteButton.addEventListener('click', function() {
        itemList.removeChild(listItem);
        const itemIndex = items.findIndex(item => item.text === itemText);
        if (itemIndex !== -1) {
            textGroup.removeChild(items[itemIndex].element);
            items.splice(itemIndex, 1);
        }
    });

    listItem.appendChild(deleteButton);
    itemList.appendChild(listItem);

    // Add 3D text to A-Frame scene
    addToScene(itemText);

    // Clear the input
    itemInput.value = '';
});

function addToScene(text) {
    const textEl = document.createElement('a-entity');
    textEl.setAttribute('text', `value: ${text}; color: white; align: center; width: 4`);
    textEl.setAttribute('position', `0 ${-items.length * 1.5} 0`);
    textGroup.appendChild(textEl);
    items.push({ text, element: textEl });
}




//////////////////////////////////////////////////////////////////////////////////////////////

const canvas = document.getElementById('canvas');
const ctx = canvas.getContext('2d');
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

const balls = [];
const ballCount = 50;
const ballRadius = 10;
const speed = 3;

function getRandomColor() {
    return `rgb(${Math.random() * 255}, ${Math.random() * 255}, ${Math.random() * 255})`;
}

function createBall() {
    return {
        x: Math.random() * (canvas.width - 2 * ballRadius) + ballRadius,
        y: Math.random() * (canvas.height - 2 * ballRadius) + ballRadius,
        dx: (Math.random() - 0.5) * speed * 2,
        dy: (Math.random() - 0.5) * speed * 2,
        color: getRandomColor(),
    };
}

function drawBall(ball) {
    ctx.beginPath();
    ctx.arc(ball.x, ball.y, ballRadius, 0, Math.PI * 2);
    ctx.fillStyle = ball.color;
    ctx.fill();
    ctx.closePath();
}

function updatePosition(ball) {
    ball.x += ball.dx;
    ball.y += ball.dy;

    // Отражение от краёв
    if (ball.x - ballRadius <= 0 || ball.x + ballRadius >= canvas.width) ball.dx *= -1;
    if (ball.y - ballRadius <= 0 || ball.y + ballRadius >= canvas.height) ball.dy *= -1;
}

function checkCollisions(ball1, ball2) {
    const dx = ball2.x - ball1.x;
    const dy = ball2.y - ball1.y;
    const distance = Math.sqrt(dx * dx + dy * dy);

    if (distance < 2 * ballRadius) {
        ball1.color = getRandomColor();
        ball2.color = getRandomColor();
    }
}

function animate() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    balls.forEach((ball, i) => {
        drawBall(ball);
        updatePosition(ball);

        // Проверяем пересечения
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

// Запускаем анимацию
animate();
