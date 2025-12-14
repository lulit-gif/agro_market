<?php
// Add review & rating page
?>
<?php require __DIR__ . '/../layout/header.php'; ?>

<h2>Add a Review</h2>
<form method="POST" action="add-review.php">
	<label>Rating:
		<input type="number" name="rating" min="1" max="5" required>
	</label>
	<label>Comment:
		<textarea name="comment" required></textarea>
	</label>
	<button type="submit">Submit Review</button>
</form>

<?php require __DIR__ . '/../layout/footer.php'; ?>
