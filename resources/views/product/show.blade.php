<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->title }}</title>
    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --bg: #f8fafc;
            --surface: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
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
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: var(--surface);
            border-radius: 1.5rem;
            padding: 3rem;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border);
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            padding: 0.625rem 1.25rem;
            background: #f1f5f9;
            color: var(--text-main);
            text-decoration: none;
            border-radius: 0.5rem;
            margin-bottom: 2.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s;
            gap: 0.5rem;
        }

        .back-btn:hover {
            background: #e2e8f0;
        }

        .product-detail {
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            gap: 4rem;
        }

        .image-section {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .main-image {
            width: 100%;
            border-radius: 1rem;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            aspect-ratio: 1;
            object-fit: cover;
        }

        .thumbnail-card {
            background: #f8fafc;
            border-radius: 1rem;
            padding: 1.5rem;
            border: 1px solid var(--border);
        }

        .thumbnail-card h3 {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .thumbnail-image {
            width: 100%;
            max-width: 250px;
            aspect-ratio: 1;
            object-fit: cover;
            border-radius: 0.5rem;
            border: 1px solid var(--border);
            background: white;
        }

        .info-section {
            display: flex;
            flex-direction: column;
        }

        .info-section h1 {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 1rem;
            letter-spacing: -0.025em;
            line-height: 1.1;
        }

        .price-tag {
            font-size: 2.5rem;
            color: var(--primary);
            font-weight: 800;
            margin-bottom: 2rem;
        }

        .description-box {
            background: #f8fafc;
            padding: 2rem;
            border-radius: 1rem;
            border: 1px solid var(--border);
            margin-bottom: 2.5rem;
        }

        .description-box h2 {
            font-size: 1rem;
            margin-bottom: 1rem;
            color: var(--text-main);
        }

        .description-text {
            color: var(--text-muted);
            line-height: 1.8;
            font-size: 1.0625rem;
        }

        .actions {
            display: flex;
            gap: 1rem;
            margin-top: auto;
        }

        .btn {
            flex: 1;
            padding: 1rem;
            border-radius: 0.75rem;
            text-decoration: none;
            font-weight: 700;
            text-align: center;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-edit {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
        }

        .btn-edit:hover {
            background: var(--primary-hover);
        }

        .btn-delete {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
            cursor: pointer;
        }

        .btn-delete:hover {
            background: #fee2e2;
        }

        @media (max-width: 900px) {
            .product-detail {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            .container {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('products.index') }}" class="back-btn">
            <span>←</span> Back to Products
        </a>
        
        <div class="product-detail">
            <div class="image-section">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}" class="main-image">
                    
                    @if($product->thumbnail)
                        <div class="thumbnail-card">
                            <h3>Thumbnail Preview</h3>
                            <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="Thumbnail" class="thumbnail-image">
                        </div>
                    @endif
                @else
                    <div class="main-image" style="display: flex; flex-direction: column; align-items: center; justify-content: center; background: #f1f5f9; color: var(--text-muted);">
                        <span style="font-size: 4rem;">🖼️</span>
                        <p style="margin-top: 1rem; font-weight: 600;">No Image Provided</p>
                    </div>
                @endif
            </div>
            
            <div class="info-section">
                <h1>{{ $product->title }}</h1>
                <div class="price-tag">${{ number_format($product->price, 2) }}</div>
                
                <div class="description-box">
                    <h2>Product Description</h2>
                    <div class="description-text">
                        {{ $product->description ?? 'No description available for this product.' }}
                    </div>
                </div>
                
                <div class="actions">
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-edit">
                        <span>✏️</span> Edit Details
                    </a>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="flex: 1;" onsubmit="return confirm('Are you sure you want to delete this product?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-delete" style="width: 100%;">
                            <span>🗑️</span> Delete Product
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
