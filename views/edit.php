<!DOCTYPE html>
<html>
<head>
    <title>Edit Task</title>
</head>
<body>
    <h1>Edit Task</h1>
    <form method="POST" action="/todoapp/update/<?= $task['id'] ?>">
 
        <input type="text" name="title" value="<?= htmlspecialchars($task['title']) ?>">
        <button type="submit">Update</button>
    </form>
    <a href="/">Back</a>
</body>
</html>
