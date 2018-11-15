<!doctype html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>New project</title>
		<link href="css/app.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        @foreach($tags as $tag)
            <div id='{{$tag->id}}'>{{$tag->name}}</div>
        @endforeach
		<script type="text/javascript" src="js/app.js"></script>
		<script type="text/javascript" src="js/addon.js"></script>
    </body>
</html>
