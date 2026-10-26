<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Реєстрація домену</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Реєстрація домену</h1>

<form action="calculate.php" method="POST">

    <label>Домен:</label>
    <label>
        <input type="text" name="domain">
    </label>

    <br><br>

    <label>Строк у роках:</label>
    <label>
        <input type="number" name="years" min="1" max="10">
    </label>

    <br><br>

    <label>Доменна зона:</label>
    <label>
        <select name="zone">
            <option value="ua">ua</option>
            <option value="com">com</option>
            <option value="org">org</option>
        </select>
    </label>

    <br><br>

    <label>Дата початку:</label>
    <label>
        <input type="date" name="start_date">
    </label>

    <br><br>

    <label>Коментар:</label>
    <label>
        <textarea name="comment"></textarea>
    </label>

    <br><br>

    <button type="submit">Розрахувати</button>

</form>

<br>

<a href="history.php">Переглянути історію</a>

</body>
</html>