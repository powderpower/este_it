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
        <div class='container'>
            <div class='row'>
                <div class='col-md-12'>
                    <h1 class="display-4">Выберите тэги</h1>
                    <div class='tag-pad p-tag'>
                        @foreach($tags as $tag)
                            <div id='{{$tag->id}}' c-act='push-tag' opt='tags' pos='pad' class='btn btn-primary mr-1 mt-1 clickable'>
                                {{$tag->name}}
                            </div>
                        @endforeach
                    </div>
                    <h1 class="display-4">Выберите исключающие тэги</h1>
                    <div class='tag-pad p-exc'>
                        @foreach($tags as $tag)
                            <div id='{{$tag->id}}' c-act='push-tag' opt='exclude' pos='pad' class='btn btn-danger mr-1 mt-1 clickable'>{{$tag->name}}</div>
                        @endforeach                    
                    </div>
                    <div class='tag-pool mt-2'></div>
                    <div c-act='send-tags' class='btn btn-success mt-1 clickable'>Отправить</div>
                </div>
            </div>
        </div>
		<script type="text/javascript" src="js/app.js"></script>
    </body>
</html>
