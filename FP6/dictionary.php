<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>
<?php

require_once __DIR__ . '/includes/db_mysqli.php';
//include 'dbname.php'; 

$result = $conn->query("SELECT * FROM dictionary_entries");

$update = false;
$dict_id = '';
$lang_1 = '';
$lang_2 = '';
$lang_3 = '';
?>

<?php
	if (isset($_POST['save'])) {
    $dict_id = $_POST['dict_id'];
    $lang_1 = $_POST['lang_1'];
	$lang_2 = $_POST['lang_2'];
	$lang_3 = $_POST['lang_3'];
    $conn->query("INSERT INTO dictionary_entries (dict_id, lang_1, lang_2, lang_3) VALUES ('$dict_id', '$lang_1', '$lang_2', '$lang_3')");
	header("location: dictionary.php");
	}

	if (isset($_GET['delete'])){
	$dict_id = $_GET['delete'];
	$stmt = $conn->prepare("DELETE FROM dictionary_entries WHERE dict_id=?");
	$stmt->bind_param("i", $dict_id);
	$stmt->execute();							
	header("location: dictionary.php");
	}
	
	if (isset($_GET['edit'])){
		$dict_id = $_GET['edit'];
		$update = true;
		$result = $conn->query("SELECT * from dictionary_entries WHERE dict_id=$dict_id");
		
	}
	
	if (isset($_POST['update'])){
		$dict_id = $_POST['dict_id'];
		$lang_1 = $_POST['lang_1'];
		$lang_2 = $_POST['lang_2'];
		$lang_3 = $_POST['lang_3'];
		
		$conn->query("UPDATE IGNORE dictionary_entries SET dict_id='$dict_id', lang_1='$lang_1', lang_2='$lang_2', lang_3='$lang_3' WHERE dict_id=$dict_id");
		
		header("location: dictionary.php");
	}
	?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
  table {
    width: 100%;
    border-collapse: collapse;
  }
}
</style>
  <title>Dictionary</title>
</head>
<body

	<?php 
	if (isset($_SESSION['message'])): ?>
	<div class="alert alert-<?=$_SESSION['msg_type']?>
		<?php
			echo $_SESSION['message'];
			unset($_SESSION['message']);
		?>
	</div>
	<?php endif ?>
	
	<div class="container">
	<h2>Dictionary Manager</h2>
    <table border="1">
        <tr><th>entry_id</th><th>dict_id</th><th>lang_1</th><th>lang_2</th><th>lang_3</th></tr>
        <?php
        $result = $conn->query("SELECT * FROM dictionary_entries");
        while($row = $result->fetch_assoc()): ?>
        <tr>
			<td><?php echo $row['entry_id']; ?></td>
            <td><?php echo $row['dict_id']; ?></td>
            <td><?php echo $row['lang_1']; ?></td>
			<td><?php echo $row['lang_2']; ?></td>
			<td><?php echo $row['lang_3']; ?></td>
			<td> <a href= "dictionary.php?edit=<?php echo $row['dict_id'] ?>"
					class="btn btn-info">Edit</a>
				<a href="dictionary.php?delete=<?php echo $row['dict_id'] ?>"
					class="btn btn-danger">Delete</a>
			</td>
        </tr>
        <?php endwhile; ?>
	</table>
	
	
	<div class="row justify-content-center">
	<form action="" method="POST">
		<input type="hidden" name="dict_id" value="<?php echo $dict_id; ?>">
		
		<div class="form-group">
		<label>dict_id</label>
		<input type="text" name="dict_id" class="form-control" 
			   value= "<?php echo $dict_id; ?>" placeholder="Enter dict_id">
		</div>
		
		<div class="form-group">
		<label>lang_1</label>
		<input type="text" name="lang_1" class="form-control" 
			   value= "<?php echo $lang_1; ?>" placeholder="Enter lang_1">
		</div>
		
		<div class="form-group">
		<label>lang_2</label>
		<input type="text" name="lang_2" class="form-control" 
			   value= "<?php echo $lang_2; ?>" placeholder="Enter lang_2">
		</div>
		
		<div class="form-group">
		<label>lang_3</label>
		<input type="text" name="lang_3" class="form-control" 
			   value= "<?php echo $lang_3; ?>" placeholder="Enter lang_3">
		</div>
		
		<div class="form-group">
		<?php
		if ($update == true):
		?>
			<button type="submit" class="btn btn-info" name="update">Update</button>
		<?php else: ?>
			<button type="submit" class="btn btn-primary" name="save">Save</button>
		<?php endif; ?>
		</div>
	</form>
	</div>

  
</body>
</html>


<?php include 'includes/footer.php'; ?>
