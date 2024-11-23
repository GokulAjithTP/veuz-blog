<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">
    <h1 class="mb-4">Blog Posts</h1>
    
    <div class="row">
        @foreach ($posts as $post)
            <div class="col-md-4 col-12 mb-4"> <!-- col-md-4 for medium screens, col-12 for full-width on small screens -->
                <div class="card">
                    <!-- Display Image if it exists -->
                    @if ($post->image)
                        <img src="{{ asset('storage/' . $post->image) }}" class="card-img-top" alt="Post Image" style="height: 200px; object-fit: cover;">
                    @else
                        <img src="{{ asset('images/default-image.jpg') }}" class="card-img-top" alt="Default Image" style="height: 200px; object-fit: cover;">
                    @endif
                    
                    <div class="card-body">
                        <h5 class="card-title">{{ $post->name }}</h5>
                        <p class="card-text">Author: {{ $post->author }}</p>
                    </div>
                </div>
            </div>
        @endforeach

        <button onclick="window.location.href='{{ route('posts.index') }}'" class="btn btn-primary btn-lg mb-4">Go to Form</button>

    </div>
</div>




    <style>
    .card-img-top {
        height: 200px;
        object-fit: cover;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 600;
    }

    .card-text {
        font-size: 1rem;
        color: #555;
    }

    .container {
        max-width: 1200px;
        margin-top: 30px;
    }
</style>

