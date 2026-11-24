CREATE DATABASE IF NOT EXISTS drondar CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE drondar;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','student') NOT NULL DEFAULT 'student',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS lessons (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200) NOT NULL,
  category VARCHAR(80) NOT NULL DEFAULT 'Жалпы',
  content TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS drones (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  type VARCHAR(60) NOT NULL,
  flight_min INT NOT NULL DEFAULT 0,
  range_km DECIMAL(6,1) NOT NULL DEFAULT 0,
  description TEXT
);

CREATE TABLE IF NOT EXISTS quizzes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  lesson_id INT NOT NULL,
  question VARCHAR(300) NOT NULL,
  a VARCHAR(150) NOT NULL, b VARCHAR(150) NOT NULL,
  c VARCHAR(150) NOT NULL, d VARCHAR(150) NOT NULL,
  correct CHAR(1) NOT NULL,
  FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS results (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  quiz_id INT NOT NULL,
  is_correct TINYINT(1) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS progress (
  user_id INT NOT NULL,
  lesson_id INT NOT NULL,
  PRIMARY KEY (user_id, lesson_id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS chat_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  question TEXT NOT NULL,
  answer TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

INSERT INTO lessons (title, category, content) VALUES
('Дрон дегеніміз не?', 'Негіздер', 'Дрон — адам бортта отырмай қашықтан немесе автоматты түрде басқарылатын ұшу аппараты. Ол ұшқышсыз ұшу аппараты (ҰҰА, UAV) деп те аталады. Дрондар фото және бейнеге түсіру, ауыл шаруашылығы, құтқару жұмыстары, жеткізу және картография салаларында қолданылады.'),
('Дрон құрылымы', 'Негіздер', 'Дронның негізгі бөліктері: рама, моторлар, пропеллерлер, ұшу контроллері, ESC (жылдамдық реттегіш), аккумулятор (LiPo), GPS модулі, камера және қашықтан басқару пульті. Ұшу контроллері гироскоп пен акселерометр деректері арқылы дронды тұрақты ұстап тұрады.'),
('Дрон түрлері', 'Негіздер', 'Мультироторлы (квадрокоптер, гексакоптер), қанатты (самолет типті), гибридті VTOL және FPV жарыс дрондары. Квадрокоптер 4 моторлы, ауада қалықтай алады. Қанатты дрондар ұзақ және алыс ұшады, бірақ қалықтай алмайды.'),
('Қауіпсіз ұшу ережелері', 'Қауіпсіздік', 'Дронды көрінетін қашықтықта ұшырыңыз, аэропорт пен әскери объектілер маңында ұшпаңыз, адамдар үстінен ұшпаңыз, аккумулятор зарядын тексеріңіз, ұшар алдында ауа райын қараңыз. Қазақстанда ұшу ережелерін Азаматтық авиация комитеті реттейді, ауырлығы белгілі шектен асатын дрондарды тіркеу қажет болуы мүмкін.'),
('Дрондардың ауыл шаруашылығында қолданылуы', 'Қолданыс', 'Дрондар егістікті мониторингілеуге, NDVI камерасымен өсімдік күйін бағалауға, тыңайтқыш пен улы химикат бүркуге, мал санауға қолданылады. Бұл шығынды азайтады және өнімділікті арттырады.'),
('Дрондарда жасанды интеллект', 'ЖИ', 'ЖИ дронға кедергіні автоматты айналып өтуге, объектіні тануға (адам, көлік, өрт), нысанды қадағалауға және маршрутты өздігінен жоспарлауға мүмкіндік береді. Компьютерлік көру (computer vision) және нейрондық желілер (мысалы, YOLO) жиі қолданылады.');

INSERT INTO drones (name, type, flight_min, range_km, description) VALUES
('DJI Mini 4 Pro', 'Квадрокоптер', 34, 20.0, 'Жеңіл (249 г) 4K камералы дрон, бастаушыларға ыңғайлы.'),
('DJI Mavic 3', 'Квадрокоптер', 46, 30.0, 'Кәсіби фото және бейне түсіруге арналған дрон.'),
('DJI Agras T40', 'Ауылшаруашылық', 18, 7.0, 'Егістікке бүркуге арналған ауыр дрон.'),
('Parrot Disco', 'Қанатты', 45, 2.0, 'Самолет типті жеңіл дрон, FPV режимі бар.'),
('Эндуро FPV 5"', 'FPV жарыс', 6, 1.0, 'Жоғары жылдамдықты жарыс дроны.');

INSERT INTO quizzes (lesson_id, question, a, b, c, d, correct) VALUES
(1, 'UAV қысқартуы нені білдіреді?', 'Ұшқышсыз ұшу аппараты', 'Әскери ұшақ', 'Ғарыш кемесі', 'Тікұшақ', 'a'),
(2, 'Дронды тұрақты ұстап тұратын бөлік қайсы?', 'Пропеллер', 'Ұшу контроллері', 'Рама', 'Камера', 'b'),
(3, 'Қалықтай алатын дрон түрі қайсы?', 'Қанатты', 'Квадрокоптер', 'Ракета', 'Планер', 'b'),
(4, 'Дронды қайда ұшуға болмайды?', 'Далада', 'Аэропорт маңында', 'Ашық алаңда', 'Жеке учаскеде (рұқсатпен)', 'b'),
(5, 'NDVI нені бағалайды?', 'Жел жылдамдығын', 'Өсімдік күйін', 'Жер асты суын', 'Температураны', 'b'),
(6, 'Объектіні тануға қолданылатын нейрондық желі үлгісі?', 'YOLO', 'HTML', 'FTP', 'SQL', 'a');
