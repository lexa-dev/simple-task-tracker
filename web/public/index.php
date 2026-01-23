<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Task Tracker</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="container">
    <h1>Мои задачи</h1>

    <!-- Форма создания задачи -->
    <form id="create-task-form" class="task-form">
      <h2>Новая задача</h2>
      <input type="text" id="task-title" placeholder="Заголовок" required />
      <textarea id="task-description" placeholder="Описание"></textarea>
      <button type="submit">Добавить</button>
    </form>

    <!-- Список задач -->
    <div id="tasks-list" class="tasks-list">
      <!-- Задачи будут подгружаться сюда -->
    </div>
  </div>

  <script src="script.js"></script>
</body>
</html>