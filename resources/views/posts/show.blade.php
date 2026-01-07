@extends('layouts.user')

@section('title', $post->name)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('posts.index') }}">Bài viết</a></li>
            <li class="breadcrumb-item active">{{ $post->name }}</li>
          </ol>
        </nav>
        
        <article class="bg-white p-5 shadow-sm rounded">
            <h1 class="display-4 fw-bold mb-3">{{ $post->name }}</h1>
            <p class="text-muted"><i class="far fa-calendar-alt me-2"></i>Được đăng ngày {{ $post->published_at }}</p>
            
            @if($post->image)
                <img src="{{ asset($post->image) }}" class="img-fluid rounded mb-4 w-100" style="max-height: 500px; object-fit: cover;">
            @endif
            
            <div class="lead mb-4 fw-normal text-secondary">
                {{ $post->description }}
            </div>
            
            <div class="post-content fs-5" style="line-height: 1.8;">
                {!! nl2br(e($post->content)) !!}
            </div>
        </article>
    </div>
</div>
@endsection
