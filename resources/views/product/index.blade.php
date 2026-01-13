<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products List</title>
    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --bg: #f8fafc;
            --surface: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --success: #10b981;
            --info: #3b82f6;
            --warning: #f59e0b;
            --danger: #ef4444;
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--bg);
            color: var(--text-main);
            min-height: 100vh;
            padding: 40px 20px;
            line-height: 1.5;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        h1 {
            font-size: 2.25rem;
            font-weight: 800;
            color: var(--text-main);
            letter-spacing: -0.025em;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 0.5rem;
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            border: none;
            gap: 0.5rem;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
            box-shadow: 0 4px 14px 0 rgba(37, 99, 235, 0.39);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.23);
        }

        .alert {
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
            border-radius: 0.75rem;
            background-color: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from { transform: translateY(-10px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 2rem;
        }

        .product-card {
            background: var(--surface);
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: #cbd5e1;
        }

        .product-image-wrapper {
            position: relative;
            width: 100%;
            padding-top: 75%; /* 4:3 Aspect Ratio */
            background: #f1f5f9;
        }

        .product-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .no-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            font-size: 0.875rem;
            gap: 0.5rem;
        }

        .product-info {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .product-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 0.75rem;
            line-height: 1.2;
        }

        .product-description {
            color: var(--text-muted);
            font-size: 0.9375rem;
            margin-bottom: 1.5rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.6;
        }

        .product-price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
            margin-bottom: 1.25rem;
        }

        .product-price {
            font-size: 1.5rem;
            color: var(--primary);
            font-weight: 800;
        }

        .product-actions {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 0.5rem;
            padding-top: 1rem;
            border-top: 1px solid #f1f5f9;
        }

        .btn-action {
            padding: 0.5rem;
            font-size: 0.8125rem;
            border-radius: 0.375rem;
            text-align: center;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-view { background: #f1f5f9; color: var(--text-main); }
        .btn-view:hover { background: #e2e8f0; }

        .btn-edit { background: #fff7ed; color: #9a3412; }
        .btn-edit:hover { background: #ffedd5; }

        .btn-delete { background: #fef2f2; color: #991b1b; border: none; cursor: pointer; }
        .btn-delete:hover { background: #fee2e2; }

        .no-products {
            grid-column: 1 / -1;
            text-align: center;
            padding: 5rem 2rem;
            background: var(--surface);
            border-radius: 1rem;
            border: 2px dashed #e2e8f0;
            color: var(--text-muted);
        }

        .pagination {
            margin-top: 3rem;
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>📦 Product Management</h1>
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <span>➕</span> Add New Product
            </a>
        </header>

        @if(session('success'))
            <div class="alert">
                <span>✅</span> {{ session('success') }}
            </div>
        @endif

        <div class="products-grid">
            @forelse($products as $product)
                <div class="product-card">
                    <div class="product-image-wrapper">
                        @if($product->thumbnail)
                            <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->title }}" class="product-image">
                        @elseif($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}" class="product-image">
                        @else
                            <div class="no-image">
                                <span>🖼️</span>
                                <span>No Image Available</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="product-info">
                        <h2 class="product-title">{{ $product->title }}</h2>
                        <p class="product-description">{{ Str::limit($product->description, 100) }}</p>
                        
                        <div class="product-price-row">
                            <div class="product-price">${{ number_format($product->price, 2) }}</div>
                        </div>
                        
                        <div class="product-actions">
                            <a href="{{ route('products.show', $product->id) }}" class="btn-action btn-view">View</a>
                            <a href="{{ route('products.edit', $product->id) }}" class="btn-action btn-edit">Edit</a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" style="width: 100%;">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="no-products">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">📭</div>
                    <h3>No products found</h3>
                    <p>Get started by creating your first product!</p>
                </div>
            @endforelse
        </div>

        @if($products->hasPages())
            <div class="pagination">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</body>
</html>
