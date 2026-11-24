# Дрондар әлемі — PHP + MySQL + ЖИ көмекші

## Орнату (XAMPP / OpenServer)
1. Бүкіл `drondar` папкасын `htdocs` ішіне көшіріңіз.
2. phpMyAdmin → Import → `database.sql`.
3. `config.php` ішінде DB баптауларын тексеріңіз.
4. Браузерде `http://localhost/drondar/install.php` ашыңыз (аккаунттар жасалады), содан кейін `install.php` файлын өшіріңіз.
5. Кіру: әзірлеуші `admin@drone.kz / admin123`, оқушы `student@drone.kz / student123`.

## ЖИ көмекші
- `config.php` ішіне `ANTHROPIC_API_KEY` қойсаңыз — Claude қазақша жауап береді (PHP cURL қосулы болуы керек).
- Кілт жоқ болса — жергілікті режим: сұрақ бойынша сабақтар базасынан жауап табады.

## Құрылым
- Қолданушы: index, drones, lessons, lesson, chat, register, login
- Оқушы панелі: student.php (прогресс, тест нәтижесі, ЖИ тарихы)
- Әзірлеуші панелі: admin/index.php (статистика, сабақ/дрон/тест CRUD, оқушылар, ЖИ сұхбаттары)
- Қауіпсіздік: PDO prepared statements, password_hash, CSRF токен, htmlspecialchars
