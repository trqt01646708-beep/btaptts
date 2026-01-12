@extends('frontend.layouts.app')

@section('title', $post->title)

@section('content')
    <!-- Breadcrumb -->
    <section class="breadcrumb-section">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('posts.index') }}">Bài viết</a></li>
                    <li class="breadcrumb-item active">{{ Str::limit($post->title, 30) }}</li>
                </ol>
            </nav>
        </div>
    </section>

    <div class="container py-4">
        <div class="row">
            <!-- Post Content -->
            <div class="col-lg-8">
                <article class="card">
                    @if($post->featured_image)
                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="card-img-top" style="max-height: 400px; object-fit: cover;">
                    @endif
                    
                    <div class="card-body">
                        <h1 class="card-title h2 mb-3">{{ $post->title }}</h1>
                        
                        <div class="post-meta text-muted mb-4">
                            <span class="me-3"><i class="fas fa-calendar me-1"></i> {{ $post->published_at ? $post->published_at->format('d/m/Y H:i') : $post->created_at->format('d/m/Y H:i') }}</span>
                            @if($post->categories->count() > 0)
                            <span>
                                <i class="fas fa-folder me-1"></i>
                                @foreach($post->categories as $category)
                                    <a href="{{ route('posts.index', ['category' => $category->slug]) }}" class="text-decoration-none">{{ $category->name }}</a>{{ !$loop->last ? ', ' : '' }}
                                @endforeach
                            </span>
                            @endif
                        </div>

                        @if($post->excerpt)
                        <div class="lead mb-4">{{ $post->excerpt }}</div>
                        @endif

                        <div class="post-content">
                            {!! $post->content !!}
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="share-buttons">
                                <span class="me-2">Chia sẻ:</span>
                                <a href="#" class="btn btn-sm btn-outline-primary"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="btn btn-sm btn-outline-info"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="btn btn-sm btn-outline-danger"><i class="fab fa-pinterest"></i></a>
                            </div>
                            <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Quay lại
                            </a>
                        </div>
                    </div>
                </article>

                <!-- Related Posts -->
                @if($relatedPosts->count() > 0)
                <section class="related-posts mt-5">
                    <h3 class="section-title">Bài viết liên quan</h3>
                    <div class="row">
                        @foreach($relatedPosts as $related)
                        <div class="col-md-6 mb-4">
                            <div class="post-card">
                                <div class="post-image">
                                    <a href="{{ route('posts.show', $related->slug) }}">
                                        @if($related->thumbnail)
                                            <img src="{{ asset('storage/' . $related->thumbnail) }}" alt="{{ $related->name }}">
                                        @else
                                            <img src="https://via.placeholder.com/400x200" alt="{{ $related->name }}">
                                        @endif
                                    </a>
                                </div>
                                <div class="post-info">
                                    <div class="post-meta">
                                        <i class="fas fa-calendar me-1"></i> {{ $related->published_at ? $related->published_at->format('d/m/Y') : $related->created_at->format('d/m/Y') }}
                                    </div>
                                    <a href="{{ route('posts.show', $related->slug) }}" class="text-decoration-none">
                                        <h5 class="post-title">{{ $related->name }}</h5>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Latest Posts -->
                @if($latestPosts->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Bài viết mới nhất</h5>
                    </div>
                    <div class="card-body">
                        @foreach($latestPosts as $latest)
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                @if($latest->thumbnail)
                                    <img src="{{ asset('storage/' . $latest->thumbnail) }}" alt="{{ $latest->name }}" class="rounded" width="80" height="60" style="object-fit: cover;">
                                @else
                                    <img src="https://via.placeholder.com/80x60" alt="{{ $latest->name }}" class="rounded" width="80" height="60">
                                @endif
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <a href="{{ route('posts.show', $latest->slug) }}" class="text-decoration-none">
                                    <h6 class="mb-1" style="font-size: 0.9rem;">{{ Str::limit($latest->name, 50) }}</h6>
                                </a>
                                <small class="text-muted">
                                    {{ $latest->published_at ? $latest->published_at->format('d/m/Y') : $latest->created_at->format('d/m/Y') }}
                                </small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Categories -->
                @if($post->categories->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Danh mục</h5>
                    </div>
                    <div class="card-body">
                        @foreach($post->categories as $category)
                        <a href="{{ route('posts.index', ['category' => $category->slug]) }}" class="btn btn-outline-secondary btn-sm mb-2">
                            {{ $category->name }}
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection

