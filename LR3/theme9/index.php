<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title> Найти ближайшее метро </title>
    <link rel="stylesheet" type="text/css" href="style.css">

    <script
            src="https://code.jquery.com/jquery-3.6.0.js"
            integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
            crossorigin="anonymous" defer></script>
    <script src="script.js" defer></script>
</head>
<body>
    <div class="content">
        <form name='form' class="form" method='POST'>
            <h3 class="content__header"> Найти ближайшее метро </h3>
            <label for="address" class="form__label">Введите адрес: </label><br>
            <input class="address form__input" type="text" id="address" name="address" required><br>
            <button class="form__submit" id="submitBtn" name="submit">Найти</button>
        </form>
        <div class="result" id="result">
        </div>
        </div>
    </div>
</body>
</html>