<!DOCTYPE html>
<html>
<head>
    <title>491-Kanban</title>
    <link href="{{ asset(mix("app.css", 'vendor/kanban')) }}?v={{config('app.version')}}" rel="stylesheet" type="text/css">
</head>
<body>
<div id="app"></div>
<script src="{{ asset(mix('app.js', 'vendor/kanban')) }}?v={{config('app.version')}}"></script>
</body>
</html>
