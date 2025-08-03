<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reddit Image Posts</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-…"
        crossorigin="anonymous"
    >
    <style>
        .pagination-container p {
            display: none;
        }

        body {
            background-color: #f8f9fa;
        }

        .card-img-top {
            object-fit: cover;
            height: 200px;
        }

        .card {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <h1 class="mb-4 text-center">
            Image Posts from <span class="text-primary">/r/{{ request()->get('subreddit', 'malaysia') }}</span>
        </h1>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
            @forelse ($posts as $post)
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <img 
                            src="{{ $post['image_url'] }}" 
                            alt="{{ $post['post_title'] }}" 
                            loading="lazy"
                            class="card-img-top img-fluid"
                        >
                        <div class="card-body">
                            <h5 class="card-title text-truncate">{{ $post['post_title'] }}</h5>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col">
                    <div class="alert alert-warning text-center" role="alert">
                        No image posts found.
                    </div>
                </div>
            @endforelse
        </div>

        <div class="pagination-container d-flex flex-column align-items-center mt-4">
            {{ $posts->links('pagination::bootstrap-5') }}
            <small class="text-muted mt-2">
                Showing {{ $posts->firstItem() }} to {{ $posts->lastItem() }} of {{ $posts->total() }} results
            </small>
        </div>
    </div>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-…"
        crossorigin="anonymous"
    ></script>
</body>
</html>
