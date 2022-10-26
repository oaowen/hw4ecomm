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
      $sqlAdd = "insert into DogTreats (DTNAME) value (?)";
      $stmtAdd = $conn->prepare($sqlAdd);
      $stmtAdd->bind_param("s", $_POST['iName']);
      $stmtAdd->execute();
      echo '<div class="alert alert-success" role="alert">New dog treat added.</div>';
      break;
    case 'Edit':
      $sqlEdit = "update DogTreats set DTNAME=? where DTID=?";
      $stmtEdit = $conn->prepare($sqlEdit);
      $stmtEdit->bind_param("si", $_POST['iName'], $_POST['iid']);
      $stmtEdit->execute();
      echo '<div class="alert alert-success" role="alert">Dog treats edited.</div>';
      break;
    case 'Delete':
      $sqlDelete = "delete from DogTreats where DTID=?";
      $stmtDelete = $conn->prepare($sqlDelete);
      $stmtDelete->bind_param("i", $_POST['iid']);
      $stmtDelete->execute();
      echo '<div class="alert alert-success" role="alert">Dog treats deleted.</div>';
      break;
  }
}
?>
    
      <h1>Dogs treats</h1>
      <table class="table table-success table-striped-columns">
        <thead>
          <tr>
            <th>DTID</th>
            <th>DTNAME</th>
            <th></th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          
<?php
$sql = "SELECT * from DogTreats";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
?>
          
          <tr>
            <td><?=$row["DTID"]?></td>
            <td><a href="DogTreatsSection.php?id=<?=$row["DTID"]?>"><?=$row["DTNAME"]?></a></td>
            <td>
              <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#editDogTreats<?=$row["DTID"]?>">
                Edit
              </button>
              <div class="modal fade" id="editDogTreats<?=$row["DTID"]?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editDogTreats<?=$row["DTID"]?>Label" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h1 class="modal-title fs-5" id="editDogTreats<?=$row["DTID"]?>Label">Edit Dogs Treats</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <form method="post" action="">
                        <div class="mb-3">
                          <label for="editDogTreats<?=$row["DTID"]?>Name" class="form-label">Name</label>
                          <input type="text" class="form-control" id="editDogTreats<?=$row["DTID"]?>Name" aria-describedby="editDogTreats<?=$row["DTID"]?>Help" name="iName" value="<?=$row["DTNAME"]?>">
                          <div id="editDogTreats<?=$row["DTID"]?>Help" class="form-text">Enter the treat's name.</div>
                        </div>
                        <input type="hidden" name="iid" value="<?=$row["DTID"]?>">
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
                <input type="hidden" name="iid" value="<?=$row["DTID"]?>" />
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
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDogTreats">
        Add New
      </button>

      <!-- Modal -->
      <div class="modal fade" id="addDogTreats" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addDogTreats" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="addDogTreats">Add treats</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form method="post" action="">
                <div class="mb-3">
                  <label for="DTNAME" class="form-label">Name</label>
                  <input type="text" class="form-control" id="DogTreatsName" aria-describedby="nameHelp" name="iName">
                  <div id="nameHelp" class="form-text">Enter the treat's name.</div>
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
