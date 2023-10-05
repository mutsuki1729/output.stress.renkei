<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>ストレス解消アプリ</title>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    </head>
    <body>
        <h1>Blog Name</h1>
        <div class='posts'>
            @foreach ($posts as $post)
            <div class='post'>
                <h2 class='title'>{{ $post->title }}</h2>
                <p class='body'>{{ $post->body }}</p>
            </div>
            @endforeach
        </div>
        <div class='paginate'>
            {{ $posts->links() }}
        </div>
    </body>
</html>
