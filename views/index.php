<!DOCTYPE html>
<html>
<head><title>ToDo List</title></head>
<body>
    <h1>ToDo List</h1>
    <a href="/todoapp/create">Add New Task</a>
    <ul>
        <?php foreach ($tasks as $task): ?>
            <li>
                <?= $task['title'] ?> - <?= $task['completed'] ? '✅' : '❌' ?>
                <a href="/todoapp/edit/<?= $task['id'] ?>">Edit</a>
                <a href="/todoapp/delete/<?= $task['id']?>" >Delete</a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>