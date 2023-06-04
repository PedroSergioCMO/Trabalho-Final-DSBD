document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('taskForm');
  const input = document.getElementById('taskInput');
  const taskList = document.getElementById('taskList');
  const assignSelect = document.getElementById('assignSelect');
  const prioritySelect = document.getElementById('prioritySelect');
  const filterSelect = document.getElementById('filterSelect');

  form.addEventListener('submit', function(event) {
    event.preventDefault();

    const taskText = input.value.trim();
    const taskDate = taskDateInput.value.trim();
    const assignValue = assignSelect.value;
    const priorityValue = prioritySelect.value;

    if (taskText !== '' && taskDate !== '') {
      addTask(taskText, taskDate, assignValue, priorityValue);
      input.value = '';
      taskDateInput.value = '';
      assignSelect.value = 'self';
      prioritySelect.value = 'low';
    }
  });

  function addTask(taskText, taskDate, assignValue, priorityValue) {
    const taskItem = document.createElement('li');
    taskItem.className = 'list-group-item';

    const taskTextSpan = document.createElement('span');
    taskTextSpan.textContent = taskText;

    const taskDateSpan = document.createElement('span');
    taskDateSpan.textContent = taskDate;

    const assignSpan = document.createElement('span');
    assignSpan.textContent = assignValue;

    const prioritySpan = document.createElement('span');
    prioritySpan.textContent = priorityValue;

    const deleteButton = document.createElement('button');
    deleteButton.className = 'btn btn-danger btn-sm float-right';
    deleteButton.innerHTML = '&times;';
    deleteButton.addEventListener('click', function() {
      deleteTask(taskItem, taskText);
    });

    const editButton = document.createElement('button');
    editButton.className = 'btn btn-primary btn-sm mr-2 float-right';
    editButton.textContent = 'Editar';
    editButton.addEventListener('click', function() {
      editTask(taskItem, taskTextSpan);
    });

    const completeCheckbox = document.createElement('input');
    completeCheckbox.type = 'checkbox';
    completeCheckbox.addEventListener('change', function() {
      if (completeCheckbox.checked) {
        taskItem.classList.add('completed');
      } else {
        taskItem.classList.remove('completed');
      }
    });

    taskItem.appendChild(completeCheckbox);
    taskItem.appendChild(taskTextSpan);
    taskItem.appendChild(taskDateSpan);
    taskItem.appendChild(assignSpan);
    taskItem.appendChild(prioritySpan);
    taskItem.appendChild(editButton);
    taskItem.appendChild(deleteButton);
    taskList.appendChild(taskItem);

    saveTaskToDatabase(taskText, taskDate, assignValue, priorityValue);
  }

  function deleteTask(taskItem, taskText) {
    taskItem.remove();
    deleteTaskFromDatabase(taskText);
  }

  function editTask(taskItem, taskTextSpan) {
    const newTaskText = prompt('Digite o novo texto da tarefa:');
    if (newTaskText !== null) {
      taskTextSpan.textContent = newTaskText.trim();
      // Atualize a tarefa no banco de dados aqui
    }
  }

  function saveTaskToDatabase(taskText, taskDate, assignValue, priorityValue) {
    const data = {
      texto: taskText,
      data_conclusao: taskDate,
      assign: assignValue,
      priority: priorityValue
    };

    fetch('api.php?action=addTask', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
      console.log('Tarefa salva com sucesso:', data);
    })
    .catch(error => {
      console.error('Erro ao salvar a tarefa:', error);
    });
  }

  function deleteTaskFromDatabase(taskText) {
    const data = {
      texto: taskText
    };

    fetch('api.php?action=deleteTask', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
      console.log('Tarefa excluída com sucesso:', data);
    })
    .catch(error => {
      console.error('Erro ao excluir a tarefa:', error);
    });
  }

  function fetchTasksFromDatabase() {
    fetch('api.php?action=getTasks')
    .then(response => response.json())
    .then(data => {
      data.forEach(task => {
        addTask(task.texto, task.data_conclusao, task.assign, task.priority);
      });
    })
    .catch(error => {
      console.error('Erro ao obter as tarefas:', error);
    });
  }

  fetchTasksFromDatabase();

  filterSelect.addEventListener('change', function() {
    filterTasks(filterSelect.value);
  });

  function filterTasks(filterValue) {
    const taskItems = taskList.getElementsByClassName('list-group-item');
    Array.from(taskItems).forEach(function(taskItem) {
      if (filterValue === 'all') {
        taskItem.style.display = 'block';
      } else if (filterValue === 'completed') {
        if (taskItem.classList.contains('completed')) {
          taskItem.style.display = 'block';
        } else {
          taskItem.style.display = 'none';
        }
      } else if (filterValue === 'pending') {
        if (!taskItem.classList.contains('completed')) {
          taskItem.style.display = 'block';
        } else {
          taskItem.style.display = 'none';
        }
      }
    });
  }

  $('#calendarContainer').fullCalendar({
    defaultView: 'month',
    height: 500,
    events: function(start, end, timezone, callback) {
      fetch('api.php?action=getTasks')
        .then(response => response.json())
        .then(data => {
          const events = data.map(task => ({
            title: task.texto,
            start: task.data_conclusao
          }));

          callback(events);
        })
        .catch(error => {
          console.error('Erro ao obter as tarefas:', error);
        });
    }
  });
});
