<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>RMI Class</title>
    @viteReactRefresh 
    @vite(['resources/js/index.tsx', 'resources/css/app.css'])
</head>
<body>
    <div id="root"></div>
</body>
</html>