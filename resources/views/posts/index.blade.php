@extends('layouts.user')

@section('title', 'Bài viết')

@section('content')
<h2 class="mb-4">Bài viết mới nhất</h2>
<div class="row">
    @foreach($posts as $post)
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
            @if($post->image)
                <img src="{{ asset($post->image) }}" class="card-img-top" alt="{{ $post->name }}">
            @endif
            <div class="card-body">
                <h5 class="card-title fw-bold">{{ $post->name }}</h5>
                <p class="card-text text-muted">{{ Str::limit($post->description, 120) }}</p>
                <a href="{{ route('posts.show', $post->slug) }}" class="btn btn-outline-primary btn-sm rounded-pill">Đọc tiếp</a>
            </div>
            <div class="card-footer bg-transparent border-0 text-muted">
                <small>{{ $post->published_at }}</small>
            </div>
        </div>
    </div>
    @endforeach
</div>
<div class="d-flex justify-content-center">
    {{ $posts->links() }}
</div>
@endsection
