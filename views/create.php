<!DOCTYPE html>
<html>
<head>
    <title>Add Task</title>
</head>
<body>
    <h1>Create Task</h1>
<?php if (!empty($errors)): ?>
    <ul>
        <?php foreach ($errors as $fieldErrors): ?>
            <?php foreach ($fieldErrors as $error): ?>
                <li style="color:red"><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>


    <form method="POST" action="store">
        <!-- <input type="hidden" name="_token" value=""> -->
        <input type="text" name="title" placeholder="Enter task title">
        <button type="submit">Add</button>
    </form>
    <a href="/todoapp">Back</a>
</body>
</html>