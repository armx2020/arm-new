<!DOCTYPE html>
<html>
<head>
    <title>Диагностика</title>
</head>
<body>
    <h1>✅ Диагностика работает!</h1>
    <p>Environment: {{ config('app.env') }}</p>
    <p>PHP: {{ phpversion() }}</p>
    <p>Laravel: {{ app()->version() }}</p>
</body>
</html>
