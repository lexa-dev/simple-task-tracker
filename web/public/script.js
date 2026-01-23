// Базовый URL вашего API (настройте под ваш сервер)
const API_BASE = '/api/tasks';

// Загрузка задач при старте
document.addEventListener('DOMContentLoaded', () => {
  loadTasks();
  document.getElementById('create-task-form').addEventListener('submit', createTask);
});

async function loadTasks() {
  try {
    const res = await fetch(API_BASE);
    const tasks = await res.json();
    renderTasks(tasks);
  } catch (err) {
    alert('Ошибка загрузки задач');
  }
}

function renderTasks(tasks) {
  console.log('Received tasks:', tasks);
  const container = document.getElementById('tasks-list');
  container.innerHTML = '';

  tasks.forEach(task => {
    const isEditing = task._editing || false;

    const div = document.createElement('div');
    div.className = 'task-item';
    div.dataset.id = task.id;

    const statusClass = task.status.toLowerCase().replace(/ /g, '_');
    const statusText = task.status.replace(/_/g, ' ');

    if (isEditing) {
      div.innerHTML = `
        <input type="text" class="edit-title" value="${escapeHtml(task.title)}" />
        <textarea class="edit-description">${escapeHtml(task.description)}</textarea>
        <select class="status-select">
          <option value="new" ${task.status === 'new' ? 'selected' : ''}>new</option>
          <option value="in_progress" ${task.status === 'in_progress' ? 'selected' : ''}>In progress</option>
          <option value="done" ${task.status === 'done' ? 'selected' : ''}>Done</option>
        </select>
        <div class="actions">
          <button class="save-btn" onclick="saveTask(${task.id})">Сохранить</button>
          <button class="cancel-btn" onclick="cancelEdit(${task.id})">Отмена</button>
        </div>
      `;
    } else {
      div.innerHTML = `
        <h3>${escapeHtml(task.title)}</h3>
        <p>${escapeHtml(task.description)}</p>
        <div class="status ${statusClass}">Статус: ${statusText}</div>
        <div class="actions">
          <button class="edit-btn" onclick="editTask(${task.id})">Редактировать</button>
          <button class="delete-btn" onclick="deleteTask(${task.id})">Удалить</button>
        </div>
      `;
    }

    container.appendChild(div);
  });
}

function escapeHtml(text) {
  const map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  };
  return text.replace(/[&<>"']/g, m => map[m]);
}

// === Создание задачи ===
async function createTask(e) {
  e.preventDefault();
  const title = document.getElementById('task-title').value.trim();
  const description = document.getElementById('task-description').value.trim();

  if (!title) return;

  try {
    const res = await fetch(API_BASE, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ title, description })
    });

    if (res.ok) {
      document.getElementById('task-title').value = '';
      document.getElementById('task-description').value = '';
      loadTasks();
    }
  } catch (err) {
    alert('Не удалось создать задачу');
  }
}

// === Редактирование ===
function editTask(id) {
  // Просто перерисовываем с флагом редактирования
  const container = document.getElementById('tasks-list');
  const items = Array.from(container.children);
  const item = items.find(el => el.dataset.id == id);
  if (!item) return;

  // Получаем текущие данные
  const taskDiv = item.cloneNode(true);
  const task = {
    id,
    title: taskDiv.querySelector('h3').textContent,
    description: taskDiv.querySelector('p').textContent,
    status: taskDiv.querySelector('.status').className.split(' ').find(cls => cls !== 'status')
  };

  // Преобразуем статус в enum-формат
  let statusEnum = 'TODO';
  if (task.status.includes('in_progress')) statusEnum = 'IN_PROGRESS';
  else if (task.status.includes('done')) statusEnum = 'DONE';

  renderTasks([{ ...task, status: statusEnum, _editing: true }]);
}

async function saveTask(id) {
  const container = document.getElementById('tasks-list');
  const item = container.querySelector(`.task-item[data-id="${id}"]`);
  if (!item) return;

  const title = item.querySelector('.edit-title').value.trim();
  const description = item.querySelector('.edit-description').value.trim();
  const status = item.querySelector('.status-select').value;

  if (!title) return;

  try {
    await fetch(`${API_BASE}/${id}`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ title, description, status })
    });
    loadTasks();
  } catch (err) {
    alert('Ошибка сохранения');
  }
}

function cancelEdit(id) {
  loadTasks(); // Просто перезагружаем
}

// === Удаление ===
async function deleteTask(id) {
  if (!confirm('Удалить задачу?')) return;

  try {
    await fetch(`${API_BASE}/${id}`, { method: 'DELETE' });
    loadTasks();
  } catch (err) {
    alert('Ошибка удаления');
  }
}

// Экспорт функций для onclick (в реальном проекте лучше использовать addEventListener)
window.editTask = editTask;
window.saveTask = saveTask;
window.cancelEdit = cancelEdit;
window.deleteTask = deleteTask;