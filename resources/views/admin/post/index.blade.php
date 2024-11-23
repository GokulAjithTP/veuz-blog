@extends('layouts.admin')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Manage Posts</h1>
    <div class="card shadow-lg border-light mb-4">
        <div class="card-body">
            <h3 class="card-title mb-4">Create or Edit Post</h3>
            <button onclick="window.location.href='{{ route('blogs.blogs') }}'" class="btn btn-primary btn-lg mb-4">Go to Blogs</button>

            <form id="postForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="postId">

                <div class="mb-3">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" id="name" name="name" class="form-control form-control-lg"  placeholder="Enter post name">
                </div>

                <div class="mb-3">
                    <label for="date" class="form-label">Date:</label>
                    <input type="date" id="date" name="date" class="form-control form-control-lg" >
                </div>

                <div class="mb-3">
                    <label for="author" class="form-label">Author:</label>
                    <input type="text" id="author" name="author" class="form-control form-control-lg" placeholder="Enter author's name">
                </div>

                <div class="mb-3">
                    <label for="editor" class="form-label">Content:</label>
                    <textarea id="editor" name="content" class="form-control form-control-lg" rows="6" placeholder="Write the content here"></textarea>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Image:</label>
                    <input type="file" id="image" name="image" class="form-control form-control-lg">
                </div>

                <button type="submit" class="btn btn-success btn-lg w-100">Save Post</button>
            </form>
        </div>
    </div>

    <!-- <button id="toggleButton" class="btn btn-primary btn-lg mb-4">Show Post Table</button>
<br><br> -->

        <div class="table-container mt-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="input-group">
                <input type="text" id="search" class="form-control form-control-lg" placeholder="Search by name" aria-label="Search posts">
                <span class="input-group-text" id="basic-addon2"><i class="fas fa-search"></i></span>
            </div>
        </div>
        <div class="col-md-6">
            <select id="authorFilter" class="form-control form-control-lg form-select form-select-lg">
                <option value="">Filter by Author</option>
                @foreach($authors as $author)
                    <option value="{{ $author }}">{{ $author }}</option>
                @endforeach
            </select>
        </div>
    </div>
    
    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <h3 class="card-title mb-4">Post List</h3>
            <table id="postTable" class="table table-striped table-bordered table-hover w-100">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Date</th>
                        <th>Author</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('editor');
</script>

<script>
    function fetchPosts(query = '', author = '') {
        $.ajax({
            url: '{{ route('posts.fetch') }}',
            method: 'GET',
            data: {
                search: query,
                author: author
            },
            success: function(posts) {
                let rows = '';
                posts.forEach(post => {
                    rows += `<tr>
                        <td>${post.name}</td>
                        <td>${post.date}</td>
                        <td>${post.author}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="editPost(${post.id})">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="deletePost(${post.id})">Delete</button>
                        </td>
                    </tr>`;
                });
                $('#postTable tbody').html(rows);
            }
        });
    }


function validatePostForm() {
    const name = $('#name').val().trim();
    const date = $('#date').val().trim();
    const author = $('#author').val().trim();
    const content = CKEDITOR.instances['editor'].getData().trim(); 
    const image = $('#image')[0].files[0];

    let errors = [];

    if (name === '') {
        errors.push('The post name is required.');
    }
    if (date === '') {
        errors.push('The post date is required.');
    }
    if (author === '') {
        errors.push('The author name is required.');
    }
    if (content === '') {
        errors.push('The content is required.');
    }
    if (image && !['image/jpeg', 'image/png', 'image/jpg', 'image/gif'].includes(image.type)) {
        errors.push('The image must be a valid image file (JPEG, PNG, JPG, GIF).');
    }
    if (image && image.size > 2048000) {
        errors.push('The image size must be less than 2MB.');
    }
    if (errors.length > 0) {
        $('#errorMessages').html(errors.join('<br>')).show();
        return false;
    }

    return true;
}
$('#postForm').on('submit', function(e) {
    e.preventDefault();
    $('#errorMessages').hide().html('');

    if (validatePostForm()) {
        const id = $('#postId').val();
        const url = id ? `/admin/posts/${id}` : '{{ route('posts.store') }}';
        const method = id ? 'PUT' : 'POST';

        let formData = new FormData(this);
        if (id) {
            formData.append('_method', 'PUT');
        }

        $.ajax({
            url: url,
            method: method,
            data: formData,
            headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
            processData: false,
            contentType: false,
            success: () => {
                alert('Post saved successfully');
                fetchPosts();
                $('#postForm')[0].reset();
                $('#postId').val('');
            },
            error: (xhr) => {
                alert('Error: ' + JSON.stringify(xhr.responseJSON.errors));
            }
        });
    }
});


    function editPost(id) {
        $.get(`/admin/posts/${id}`, function(post) {
            $('#postId').val(post.id);
            $('#name').val(post.name);
            $('#date').val(post.date);
            $('#author').val(post.author);
            CKEDITOR.instances['editor'].setData(post.content);
        });
    }

    function deletePost(id) {
        if (confirm('Are you sure you want to delete this post?')) {
            $.ajax({
                url: `/admin/posts/${id}`,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: () => {
                    alert('Post deleted successfully');
                    fetchPosts();
                },
                error: (xhr) => {
                    alert('Error: ' + JSON.stringify(xhr.responseJSON.errors));
                }
            });
        }
    }

    fetchPosts();

    $('#search').on('input', function() {
        const query = $(this).val();
        const author = $('#authorFilter').val();
        fetchPosts(query, author);
    });

    $('#authorFilter').on('change', function() {
        const author = $(this).val();
        const query = $('#search').val();
        fetchPosts(query, author);
    });


//     $(document).ready(function() {
//     $('#postForm').show();
//     $('.table-container').hide();
//     $('#toggleButton').click(function() {
//         const isFormVisible = $('#postForm').is(':visible');
//         $('#postForm').toggle();
//         $('.table-container').toggle();
//         if (isFormVisible) {
//             $('#toggleButton').text('Show Post Form');
//         } else {
//             $('#toggleButton').text('Show Post Table');
//         }
//     });
// });

</script>


<style>
    .container {
        max-width: 1200px;
        margin-top: 30px;
    }

    .card-title {
        font-size: 1.5rem;
        margin-bottom: 1.25rem;
    }

    .form-label {
        font-weight: 600;
        font-size: 1rem;
    }

    .form-control-lg {
        font-size: 1.1rem;
        padding: 10px;
    }

    .table th,
    .table td {
        vertical-align: middle;
    }

    .table {
        margin-top: 20px;
    }

    .btn-lg {
        font-size: 1.2rem;
    }

    .btn-sm {
        font-size: 0.875rem;
    }

    .card-body {
        padding: 2rem;
    }

    .mb-3 label {
        font-weight: 600;
    }

    .btn {
        font-weight: 600;
    }

    .search-filter {
        margin-bottom: 1rem;
    }

    /* Custom Styling */
    .input-group-text {
        background-color: #f8f9fa;
        border-color: #ccc;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f9f9f9;
    }

    .btn-warning {
        background-color: #f0ad4e;
        border-color: #f0ad4e;
    }

    .btn-danger {
        background-color: #d9534f;
        border-color: #d9534f;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .input-group {
        border-radius: 0.375rem;
    }

    .form-select,
    .form-control {
        border-radius: 0.375rem;
    }

    #postTable th,
    #postTable td {
        vertical-align: middle;
        text-align: center;
    }
    table {
        width: 1030px;
        border-collapse: collapse; 
        margin: 20px 0; 
    }

    table th, table td {
        border: 1px solid #ddd; 
        padding: 10px;
        text-align: center;
    }

    table th {
        background-color: #f2f2f2; 
        font-weight: bold;
    }

    table tr:nth-child(even) {
        background-color: #f9f9f9; 
    }

    table tr:hover {
        background-color: #f1f1f1; 
    }

    table td {
        vertical-align: middle; 
    }

    table tbody tr {
        border-bottom: 1px solid #ddd;
    }

    table td, table th {
        padding-left: 15px;
        padding-right: 15px;
    }

    .table-container {
        overflow-x: auto;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f9f9f9;
    }

    .table-bordered {
        border: 1px solid #ddd;
    }

    .table-hover tbody tr:hover {
        background-color: #f1f1f1;
    }

    .card {
        border-radius: 0.375rem;
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: 600;
    }
</style>
@endsection
