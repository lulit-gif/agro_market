<?php require __DIR__ . '/../layout/header.php'; ?>

<h1>Add Product</h1>
<form method="POST" action="add-product.php" enctype="multipart/form-data">
	<label>Title:
		<input type="text" name="title" required>
	</label>
	<label>Price:
		<input type="text" name="price" required>
	</label>
	<label>Image:
		<input type="file" name="image">
	</label>
	<button type="submit">create product</button>
</form>

<?php require __DIR__ . '/../layout/footer.php'; ?>
