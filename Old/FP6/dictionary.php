<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>
<?php

require_once __DIR__ . '/includes/db_mysqli.php';
//include 'dbname.php'; 

$result = $conn->query("SELECT * FROM dictionaries");

$update = false;
$dict_id = '';
$dict_identifier = '';
$name = '';
$type = '';
$source_lang_1 = '';
$source_lang_2 = '';
$source_lang_3 = '';
?>

<?php
	if (isset($_POST['save'])) {
    $dict_id = $_POST['dict_id'];
    $dict_identifier = $_POST['dict_identifier'];
	$name = $_POST['name'];
	$type = $_POST['type'];
	$source_lang_1 = $_POST['source_lang_1'];
	$source_lang_2 = $_POST['source_lang_2'];
	$source_lang_3 = $_POST['source_lang_3'];
    $conn->query("INSERT INTO dictionaries (dict_id, dict_identifier, name, type, source_lang_1, source_lang_2, source_lang_3)
	VALUES ('$dict_id', '$dict_identifier', '$name', '$type', '$source_lang_1', '$source_lang_2', '$source_lang_3')");
	header("location: dictionary.php");
	}

	if (isset($_GET['delete'])){
	$dict_id = $_GET['delete'];
	$stmt = $conn->prepare("DELETE FROM dictionaries WHERE dict_id=?");
	$stmt->bind_param("i", $dict_id);
	$stmt->execute();							
	header("location: dictionary.php");
	}
	
	if (isset($_GET['edit'])){
		$dict_id = $_GET['edit'];
		$update = true;
		$result = $conn->query("SELECT * from dictionaries WHERE dict_id=$dict_id");
		
	}
	
	if (isset($_POST['update'])){
		$dict_id = $_POST['dict_id'];
		$dict_identifier = $_POST['dict_identifier'];
		$name = $_POST['name'];
		$type = $_POST['type'];
		$source_lang_1 = $_POST['source_lang_1'];
		$source_lang_2 = $_POST['source_lang_2'];
		$source_lang_3 = $_POST['source_lang_3'];
		
		$conn->query("UPDATE IGNORE dictionaries SET dict_id='$dict_id', dict_identifier='$dict_identifier', name='$name', type='$type',
		source_lang_1='$source_lang_1', source_lang_2='$source_lang_2', source_lang_3='$source_lang_3' WHERE dict_id=$dict_id");
		
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
  	<h2>Dictionary Manager</h2>
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
	<table id='dictionaryTable' class='display' border='1'>
	<thead>
        <tr><th>dict_id</th><th>dict_identifier</th><th>name</th><th>type</th>
		<th>source_lang_1</th><th>source_lang_2</th><th>source_lang_3</th>
		<th>Manage</th></tr>
	</thead>
		<tbody>
        <?php
        $result = $conn->query("SELECT * FROM dictionaries");
        while($row = $result->fetch_assoc()): ?>
        <tr>
			<td><?php echo $row['dict_id']; ?></td>
            <td><?php echo $row['dict_identifier']; ?></td>
            <td><?php echo $row['name']; ?></td>
			<td><?php echo $row['type']; ?></td>
			<td><?php echo $row['source_lang_1']; ?></td>
			<td><?php echo $row['source_lang_2']; ?></td>
			<td><?php echo $row['source_lang_3']; ?></td>
			<td>    <a href= "dictionary.php?edit=<?php echo $row['dict_id'] ?>"
					class="btn btn-info">Edit</a>
					<a href="dictionary.php?delete=<?php echo $row['dict_id'] ?>"
					class="btn btn-danger">Delete</a>
			</td>
        </tr>
        <?php endwhile; ?>
	</tbody>
	</table>
	
	
	<div class="row justify-content-center">
	<form action="" method="POST">
		<input type="hidden" name="dict_id" value="<?php echo $dict_id; ?>">
		
		<div class="form-group">
		<label>Dict_Id</label>
		<input type="text" name="dict_id" class="form-control" 
			   value= "<?php echo $dict_id; ?>" placeholder="Enter dict_id">
		</div>
		
		<div class="form-group">
		<label>Dict_Identifer</label>
		<input type="text" name="dict_identifier" class="form-control" 
			   value= "<?php echo $dict_identifier; ?>" placeholder="Enter dict_identifer">
		</div>
		
		<div class="form-group">
		<label>Name</label>
		<input type="text" name="name" class="form-control" 
			   value= "<?php echo $name; ?>" placeholder="Enter name">
		</div>
		
		<div class="form-group">
		<label>Type</label>
		<input type="text" name="type" class="form-control" 
			   value= "<?php echo $type; ?>" placeholder="Enter type">
		</div>
		
		<div class="form-group">
		<label>Source_Lang_1</label>
		<input type="text" name="source_lang_1" class="form-control" 
			   value= "<?php echo $source_lang_1; ?>" placeholder="Enter lang_1">
		</div>
		
		<div class="form-group">
		<label>Source_Lang_2</label>
		<input type="text" name="source_lang_2" class="form-control" 
			   value= "<?php echo $source_lang_2; ?>" placeholder="Enter lang_2">
		</div>
		
		<div class="form-group">
		<label>Source_Lang_3</label>
		<input type="text" name="source_lang_3" class="form-control" 
			   value= "<?php echo $source_lang_3; ?>" placeholder="Enter lang_3">
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

  
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/2.3.7/js/dataTables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function () {
    $('#dictionaryTable').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        info: true
    });
});
</script>
</body>
</html>

<?php mysqli_close($conn); // Close the database connection ?>
<?php include 'includes/footer.php'; ?>

</body>
</html>

<?php include 'includes/footer.php'; ?>
