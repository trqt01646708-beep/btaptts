<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products List</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        h1 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
            font-size: 2.5em;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 20px;
            transition: transform 0.3s;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }
        .product-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        .product-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }
        .product-info {
            padding: 20px;
        }
        .product-title {
            font-size: 1.5em;
            color: #333;
            margin-bottom: 10px;
        }
        .product-description {
            color: #666;
            margin-bottom: 15px;
            line-height: 1.6;
        }
        .product-price {
            font-size: 1.8em;
            color: #667eea;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .product-actions {
            display: flex;
            gap: 10px;
        }
        .btn-small {
            flex: 1;
            padding: 10px;
            text-align: center;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.9em;
            transition: all 0.3s;
        }
        .btn-view {
            background: #4CAF50;
            color: white;
        }
        .btn-edit {
            background: #2196F3;
            color: white;
        }
        .btn-delete {
            background: #f44336;
            color: white;
            border: none;
            cursor: pointer;
        }
        .btn-small:hover {
            opacity: 0.8;
            transform: translateY(-2px);
        }
        .no-products {
            text-align: center;
            padding: 50px;
            color: #666;
            font-size: 1.2em;
        }
        .pagination {
            margin-top: 30px;
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📦 Product Management</h1>
        
        <a href="<?php echo e(route('products.create')); ?>" class="btn">➕ Add New Product</a>

        <?php if(session('success')): ?>
            <div class="alert">
                ✅ <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if($products->count() > 0): ?>
            <div class="products-grid">
                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="product-card">
                        <?php if($product->thumbnail): ?>
                            <img src="<?php echo e(asset('storage/' . $product->thumbnail)); ?>" alt="<?php echo e($product->title); ?>" class="product-image">
                        <?php else: ?>
                            <div class="product-image" style="background: #f0f0f0; display: flex; align-items: center; justify-content: center; color: #999;">
                                No Image
                            </div>
                        <?php endif; ?>
                        
                        <div class="product-info">
                            <h2 class="product-title"><?php echo e($product->title); ?></h2>
                            <p class="product-description"><?php echo e(Str::limit($product->description, 100)); ?></p>
                            <div class="product-price">$<?php echo e(number_format($product->price, 2)); ?></div>
                            
                            <div class="product-actions">
                                <a href="<?php echo e(route('products.show', $product->id)); ?>" class="btn-small btn-view">View</a>
                                <a href="<?php echo e(route('products.edit', $product->id)); ?>" class="btn-small btn-edit">Edit</a>
                                <form action="<?php echo e(route('products.destroy', $product->id)); ?>" method="POST" style="flex: 1;" onsubmit="return confirm('Are you sure you want to delete this product?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn-small btn-delete" style="width: 100%;">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="pagination">
                <?php echo e($products->links()); ?>

            </div>
        <?php else: ?>
            <div class="no-products">
                📭 No products found. Create your first product!
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
<?php /**PATH D:\xamcc\htdocs\b7\resources\views/product/index.blade.php ENDPATH**/ ?>