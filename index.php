<?php
$insert = false;
// Connect to the server
$servername = "localhost";
$username = "root";
$password = "";
$database = "notes";


// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);


// Die if connection was not successful
if(!$conn){
  die("Sorry we faild to connect : " . mysqli_connect_error());
}


if($_SERVER["REQUEST_METHOD"] == "POST"){
  
  if($_POST['tsSnoEdit']){
      $sno = $_POST["tsSnoEdit"];
      $title = $_POST["tsEditNoteTitle"];
      $description = $_POST["tsEditNoteDescription"];

      
      $sql =  "UPDATE `notes` SET `title` = $title, `description` = $description WHERE `notes`.`sno` = $sno";
 

    $result = mysqli_query($conn, $sql);

  }else{
      $title = $_POST["title"];
      $description = $_POST["description"];

      // echo $title;
      // echo $description;
      
      $sql =  "INSERT INTO `notes` ( `title`, `description`) VALUES ( '$title', '$description')";
      
      
    $result = mysqli_query($conn, $sql);
    
    if($result){
      // echo "Record has been inserted successfully";
      $insert = true;
    }else{
      echo "Record isn't inserted" . mysqli_error($conn);
    }
}

}

?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>I Notes</title>
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi"
            crossorigin="anonymous" />
        <link rel="stylesheet"
            href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    </head>

    <body>
        <!-- Modal -->
        <div class="modal fade" id="editModal" tabindex="-1"
            aria-labelledby="editModalLabel" aria-hidden="true">
            <div
                class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="editModalLabel">
                            Edit Note</h1>
                        <button type="button" class="btn-close"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="/CRUD/?update=true" method="post">
                            <input type="hidden" name="tsSnoEdit"
                                class="form-control form-control-lg"
                                id="tsSnoEdit" />
                            <div class="mb-3">
                                <label for="tsEditNoteTitle" class="form-label">
                                    Note Title
                                </label>
                                <input type="text" name="tsEditNoteTitle"
                                    class="form-control form-control-lg"
                                    id="tsEditNoteTitle" />
                            </div>
                            <div class="mb-3">
                                <label for="tsEditNoteDescription"
                                    class="form-label">
                                    Note Description</label>
                                <textarea name="tsEditNoteDescription"
                                    class="form-control form-control-lg"
                                    id="tsEditNoteDescription" rows="5">
                        </textarea>
                            </div>
                            <button type="submit"
                                class="btn btn-lg btn-primary w-100">
                                Update Note
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <header>
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">Php Crud</a>
                    <button class="navbar-toggler" type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse"
                        id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page"
                                    href="#">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">About</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link">Contacts</a>
                            </li>
                        </ul>
                        <form class="d-flex" role="search">
                            <input class="form-control form-control-lg me-2"
                                type="search" placeholder="Search"
                                aria-label="Search" />
                            <button class="btn btn-outline-success"
                                type="submit">
                                Search
                            </button>
                        </form>
                    </div>
                </div>
            </nav>
        </header>
        <?php 
          if( $insert ){
       
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
              <strong>Holy guacamole!</strong> Your Note has been inserted successfully
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
          }
        ?>
        <main class="mb-5">
            <div class="container py-5 mb-5">
                <h1 class="text-center mb-3">ADD NOTE</h1>
                <form action="/CRUD/" method="post">
                    <div class="mb-3">
                        <label for="tsNoteTitle" class="form-label">
                            Note Title
                        </label>
                        <input type="text" name="title"
                            class="form-control form-control-lg"
                            id="tsNoteTitle" />
                    </div>
                    <div class="mb-3">
                        <label for="tsNoteDescription" class="form-label">
                            Note Description</label>
                        <textarea name="description"
                            class="form-control form-control-lg"
                            id="tsNoteDescription" rows="5">
                        </textarea>
                    </div>
                    <button type="submit" class="btn btn-lg btn-primary w-100">
                        Add Note
                    </button>
                </form>
            </div>
            <div class="container">
                <table class="table" id="myTable">
                    <thead>
                        <tr>
                            <th scope="col">Serial Number</th>
                            <th scope="col">Title</th>
                            <th scope="col">Description</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                $sql = "SELECT * FROM `notes`";
                $result = mysqli_query($conn , $sql);
                $sno  = 1;
                  while($row = mysqli_fetch_assoc($result)){
                    echo "<tr>"
                    . "<th scope='row'>". $sno . "</th>"
                    . "<td scope='row'>". $row['title'] . "</td>"
                    . "<td scope='row'>". $row['description'] . "</td>
                    <td scope='row'> 
                    <button type='button' class='btn-edit btn btn-primary' data-bs-toggle='modal' data-bs-target='#editModal' id=".$row['sno'].">
                    Edit</button>
                     <button class='btn btn-danger'>Delete</button> </td> 
                    </tr>";  
                    // echo $row['sno'] ;
                    // echo "Title" . $row['title'] ;
                    // echo "Description" . $row['description'];
                    
                    // echo "<br/>";
                    $sno  = $sno + 1;
                  }
              ?>
                    </tbody>
                </table>
            </div>
            </div>
        </main>
        <footer></footer>
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3"
            crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js">
        </script>
        <script type="text/javascript">
        $(document).ready(function() {
            $('#myTable').DataTable();

            $(".btn-edit").click(function(e) {

                let tr = e.target.parentNode.parentNode;
                let sNo = e.target.id;
                let title = tr.getElementsByTagName("td")[0]
                    .innerText;
                let desc = tr.getElementsByTagName("td")[1]
                    .innerText;


                $("#tsSnoEdit").val(sNo);
                $("#tsEditNoteTitle").val(title);
                $("#tsEditNoteDescription").val(desc);

                console.log("sNo" + sNo)
                console.log("desc" + desc)
            })
        });
        </script>
    </body>

</html>