<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Product</title>
    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --bg: #f8fafc;
            --surface: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --success: #10b981;
            --danger: #ef4444;
            --border: #e2e8f0;
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
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
            max-width: 800px;
            margin: 0 auto;
            background: var(--surface);
            border-radius: 1rem;
            padding: 2.5rem;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
        }

        h1 {
            color: var(--text-main);
            margin-bottom: 2rem;
            text-align: center;
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -0.025em;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            padding: 0.625rem 1.25rem;
            background: #f1f5f9;
            color: var(--text-main);
            text-decoration: none;
            border-radius: 0.5rem;
            margin-bottom: 2rem;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s;
            gap: 0.5rem;
        }

        .back-btn:hover {
            background: #e2e8f0;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-main);
            font-weight: 600;
            font-size: 0.9375rem;
        }

        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: all 0.2s;
            color: var(--text-main);
            background: #fff;
        }

        input:focus,
        textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        textarea {
            min-height: 150px;
            resize: vertical;
        }

        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            width: 100%;
        }

        .file-input-wrapper input[type=file] {
            position: absolute;
            left: -9999px;
        }

        .file-input-label {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            background: #f1f5f9;
            color: var(--text-main);
            border: 2px dashed var(--border);
            border-radius: 0.75rem;
            cursor: pointer;
            transition: all 0.2s;
            gap: 0.75rem;
            font-weight: 600;
        }

        .file-input-label:hover {
            background: #e2e8f0;
            border-color: var(--primary);
        }

        .file-names {
            margin-top: 1rem;
            padding: 0.75rem;
            background: #f8fafc;
            border-radius: 0.5rem;
            color: var(--text-muted);
            font-size: 0.875rem;
            border: 1px solid var(--border);
        }

        .error {
            color: var(--danger);
            font-size: 0.8125rem;
            margin-top: 0.375rem;
            font-weight: 500;
        }

        .error-list {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 0.75rem;
            padding: 1.25rem;
            margin-bottom: 2rem;
        }

        .error-list ul {
            list-style-position: inside;
            color: #991b1b;
            font-size: 0.875rem;
        }

        .submit-btn {
            width: 100%;
            padding: 1rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-size: 1.125rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 1rem;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
        }

        .submit-btn:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
        }

        .image-preview {
            margin-top: 1.5rem;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 1rem;
        }

        .preview-item {
            width: 100%;
            aspect-ratio: 1;
            object-fit: cover;
            border-radius: 0.5rem;
            border: 1px solid var(--border);
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('products.index') }}" class="back-btn">
            <span>←</span> Back to Products
        </a>
        
        <h1>➕ Create New Product</h1>

        @if($errors->any())
            <div class="error-list">
                <strong style="display: block; margin-bottom: 0.5rem; color: #991b1b;">Please fix the following errors:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label for="title">Product Title *</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" placeholder="e.g. Premium Wireless Headphones" required>
                @error('title')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" placeholder="Describe your product in detail...">{{ old('description') }}</textarea>
                @error('description')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="price">Price ($) *</label>
                <input type="number" id="price" name="price" step="0.01" min="0" value="{{ old('price') }}" placeholder="0.00" required>
                @error('price')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Product Images * <span style="font-weight: normal; color: var(--text-muted); font-size: 0.8rem;">(Multiple images supported)</span></label>
                <div class="file-input-wrapper">
                    <input type="file" id="images" name="images[]" accept="image/*" multiple required onchange="previewImages(event)">
                    <label for="images" class="file-input-label">
                        <span>📁</span> Choose Images (Click to browse)
                    </label>
                </div>
                <div id="fileNames" class="file-names" style="display: none;"></div>
                <div id="imagePreview" class="image-preview"></div>
                @error('images.*')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="submit-btn">Create Product</button>
        </form>
    </div>

    <script>
        function previewImages(event) {
            const files = event.target.files;
            const fileNamesDiv = document.getElementById('fileNames');
            const imagePreview = document.getElementById('imagePreview');
            
            if (files.length > 0) {
                fileNamesDiv.style.display = 'block';
                fileNamesDiv.innerHTML = `<strong>Selected files:</strong> ${files.length} image(s)`;
                
                // Clear previous previews
                imagePreview.innerHTML = '';
                
                // Show preview for each image
                Array.from(files).forEach(file => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'preview-item';
                            imagePreview.appendChild(img);
                        }
                        reader.readAsDataURL(file);
                    }
                });
            } else {
                fileNamesDiv.style.display = 'none';
                imagePreview.innerHTML = '';
            }
        }
    </script>
</body>
</html>
