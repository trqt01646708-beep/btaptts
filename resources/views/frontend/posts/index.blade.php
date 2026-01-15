@extends('frontend.layouts.app')

@section('title', 'Bài viết')

@section('content')
    <!-- Breadcrumb -->
    <section class="breadcrumb-section">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item active">Bài viết</li>
                </ol>
            </nav>
        </div>
    </section>

    <div class="container">
        <div class="row">
            <!-- Posts -->
            <div class="col-lg-8">
                <div class="row">
                    @forelse($posts as $post)
                    <div class="col-md-6 mb-4">
                        <div class="post-card">
                            <div class="post-image">
                                <a href="{{ route('posts.show', $post->slug) }}">
                                    @if($post->featured_image)
                                        <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" style="width: 100%; height: 200px; object-fit: cover;">
                                    @else
                                        <img src="https://via.placeholder.com/400x200" alt="{{ $post->title }}" style="width: 100%; height: 200px; object-fit: cover;">
                                    @endif
                                </a>
                            </div>
                            <div class="post-info">
                                <div class="post-meta">
                                    <i class="fas fa-calendar me-1"></i> {{ $post->published_at ? $post->published_at->format('d/m/Y') : $post->created_at->format('d/m/Y') }}
                                </div>
                                <a href="{{ route('posts.show', $post->slug) }}" class="text-decoration-none">
                                    <h5 class="post-title">{{ $post->title }}</h5>
                                </a>
                                <p class="post-excerpt">{{ Str::limit($post->excerpt ?? $post->content, 100) }}</p>
                                <a href="{{ route('posts.show', $post->slug) }}" class="btn btn-outline-primary btn-sm">
                                    Đọc tiếp <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="fas fa-newspaper fa-3x mb-3"></i>
                            <p class="mb-0">Chưa có bài viết nào</p>
                        </div>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $posts->links() }}
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Search -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-search me-2"></i>Tìm kiếm</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('posts.index') }}" method="GET">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm bài viết..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Categories -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-folder me-2"></i>Danh mục</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            @foreach($categories as $category)
                            <li class="mb-2">
                                <a href="{{ route('posts.index') }}" class="text-decoration-none text-dark">
                                    <i class="fas fa-chevron-right me-2 text-primary"></i>
                                    {{ $category->name }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Popular Posts -->
                @if(isset($popularPosts) && $popularPosts->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-fire me-2"></i>Bài viết phổ biến</h5>
                    </div>
                    <div class="card-body">
                        @foreach($popularPosts as $popular)
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                @if($popular->featured_image)
                                    <img src="{{ asset('storage/' . $popular->featured_image) }}" alt="{{ $popular->title }}" class="rounded" width="80" height="60" style="object-fit: cover;">
                                @else
                                    <img src="https://via.placeholder.com/80x60" alt="{{ $popular->title }}" class="rounded" width="80" height="60">
                                @endif
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <a href="{{ route('posts.show', $popular->slug) }}" class="text-decoration-none">
                                    <h6 class="mb-1" style="font-size: 0.9rem;">{{ Str::limit($popular->title, 50) }}</h6>
                                </a>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i> {{ $popular->published_at ? $popular->published_at->format('d/m/Y') : $popular->created_at->format('d/m/Y') }}
                                </small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection

