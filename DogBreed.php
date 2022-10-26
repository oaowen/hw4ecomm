<?php
include('header.php');

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dogs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
  </head>
  <body>
    <div class="container">
      
<?php
$servername = "localhost";
$username = "oaowenou_hw4ecomm";
$password = "TAnner01!!";
$dbname = "oaowenou_hw4ecomm";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  switch ($_POST['saveType']) {
    case 'Add':
      $sqlAdd = "insert into DogBreed (DBName) value (?)";
      $stmtAdd = $conn->prepare($sqlAdd);
      $stmtAdd->bind_param("s", $_POST['iName']);
      $stmtAdd->execute();
      echo '<div class="alert alert-success" role="alert">New dog breed added.</div>';
      break;
    case 'Edit':
      $sqlEdit = "update DogBreed set DBName=? where DBID=?";
      $stmtEdit = $conn->prepare($sqlEdit);
      $stmtEdit->bind_param("si", $_POST['iName'], $_POST['iid']);
      $stmtEdit->execute();
      echo '<div class="alert alert-success" role="alert">Dog breed edited.</div>';
      break;
    case 'Delete':
      $sqlDelete = "delete from DogBreed where DBID=?";
      $stmtDelete = $conn->prepare($sqlDelete);
      $stmtDelete->bind_param("i", $_POST['iid']);
      $stmtDelete->execute();
      echo '<div class="alert alert-success" role="alert">Dog breed deleted.</div>';
      break;
  }
}
?>
    
      <h1>Dog Breeds</h1>
      <table class="table table-success table-striped-columns">
        <thead>
          <tr>
            <th>DBID</th>
            <th>DBName</th>
            <th></th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          
<?php
$sql = "SELECT * from DogBreed";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
?>
          
          <tr>
            <td><?=$row["DBID"]?></td>
            <td><a href="DBSection.php?id=<?=$row["DBID"]?>"><?=$row["DBName"]?></a></td>
            <td>
              <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#editDogBreed<?=$row["DBID"]?>">
                Edit
              </button>
              <div class="modal fade" id="editDogBreed<?=$row["DBID"]?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editDogBreed<?=$row["DBID"]?>Label" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h1 class="modal-title fs-5" id="editDogBreed<?=$row["DBID"]?>Label">Edit Dog BReed</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <form method="post" action="">
                        <div class="mb-3">
                          <label for="editDogBreed<?=$row["DBID"]?>Name" class="form-label">Name</label>
                          <input type="text" class="form-control" id="editDogBreed<?=$row["DBID"]?>Name" aria-describedby="editDogBreed<?=$row["DBID"]?>Help" name="iName" value="<?=$row["DBName"]?>">
                          <div id="editDogBreed<?=$row["DBID"]?>Help" class="form-text">Enter the name.</div>
                        </div>
                        <input type="hidden" name="iid" value="<?=$row["DBID"]?>">
                        <input type="hidden" name="saveType" value="Edit">
                        <input type="submit" class="btn btn-primary" value="Submit">
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </td>
            <td>
              <form method="post" action="">
                <input type="hidden" name="iid" value="<?=$row["DBID"]?>" />
                <input type="hidden" name="saveType" value="Delete">
                <input type="submit" class="btn" onclick="return confirm('Are you sure?')" value="Delete">
              </form>
            </td>
          </tr>
          
<?php
  }
} else {
  echo "0 results";
}
$conn->close();
?>
          
        </tbody>
      </table>
      <br />
      <!-- Button trigger modal -->
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#DogBreed">
        Add New
      </button>

      <!-- Modal -->
      <div class="modal fade" id="addDogBreed" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addDogBreedLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="addDogBreedLabel">Add Dog Breed</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form method="post" action="">
                <div class="mb-3">
                  <label for="DBName" class="form-label">Name</label>
                  <input type="text" class="form-control" id="DBName" aria-describedby="nameHelp" name="iName">
                  <div id="nameHelp" class="form-text">Enter the name.</div>
                </div>
                <input type="hidden" name="saveType" value="Add">
                <button type="submit" class="btn btn-primary">Submit</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
  </body>
</html>
