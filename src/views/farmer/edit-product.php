<?php require __DIR__ . '/../layout/header.php'; ?>

<h1>Edit Product</h1>
<form method="POST" action="edit-product.php" enctype="multipart/form-data">
	<label>Title:
		<input type="text" name="title" value="Sample product">
	</label>
	<label>Price:
		<input type="text" name="price" value="0.00">
	</label>
	<button type="submit">Save changes</button>
</form>

<?php require __DIR__ . '/../layout/footer.php'; ?>
