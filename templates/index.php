<!doctype html>
<html>
<head>
    <title>Новый год</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-12 mt-5">
            <form class="align-center" style="display: flex; justify-content: center;" method="get">
                <input type="hidden" name="submit" value="1">
                <input type="hidden" name="hash" value="<?= $_GET['hash'] ?>">
                <button class="btn btn-primary">Выбрать</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>