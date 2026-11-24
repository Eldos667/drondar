<?php
// Бір рет іске қосыңыз: әзірлеуші және оқушы аккаунттарын жасайды. Кейін осы файлды өшіріңіз!
require 'config.php';
$st = $pdo->prepare('INSERT IGNORE INTO users (name,email,password,role) VALUES (?,?,?,?)');
$st->execute(['Әзірлеуші', 'admin@drone.kz', password_hash('admin123', PASSWORD_DEFAULT), 'admin']);
$st->execute(['Оқушы Демо', 'student@drone.kz', password_hash('student123', PASSWORD_DEFAULT), 'student']);
echo 'Дайын. admin@drone.kz / admin123 және student@drone.kz / student123. install.php файлын өшіріңіз!';
