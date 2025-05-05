<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="/api/createPost" method="POST">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea class="form-control" id="body" name="body" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">img</label>
            <textarea class="form-control" id="image" name="image" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Create Post</button>

    </form>
    <br>
    <form action="/api/searchPost" method="GET">
        @csrf
        <div class="mb-3">
            <label for="search" class="form-label">Search</label>
            <input type="text" class="form-control" id="search" name="search" required>
        </div>
        <button type="submit" class="btn btn-primary">Search Post</button>
    </form>
    <br>
    <form action="/api/createYearUser" method="POST">
        @csrf
        <div class="mb-3">
            <label for="user" class="form-label">User</label>
            <input type="text" class="form-control" id="id" name="id" required>
        </div>
        <div class="mb-3">
            <label for="year" class="form-label">Year</label>
            <input type="text" class="form-control" id="id" name="id" required>
        </div>
        <button type="submit" class="btn btn-primary">Create Year User</button>
    </form>
    <br>
    @if (isset($posts))
        @foreach ($posts as $post)
            <div class="card mb-3">
                <div class="card-header">
                    {{ $post->title }}
                </div>
                <div class="card-body">
                    <p class="card-text">{{ $post->body }}</p>
                    <img src="{{ $post->image }}" class="img-fluid" alt="{{ $post->title }}">
                </div>
            </div>
        @endforeach
    @endif
</body>
</html>

