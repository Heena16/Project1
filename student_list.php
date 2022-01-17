<html>
  <head>
    <title>Display Records</title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.min.js" integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <body>
    <div id="message"></div>
    <h2 class="text-center pt-4">Students Details</h2>
    <div class="container py-3">
      <table class="table border table-striped shadow">
        <thead class="table-dark">
          <tr>
            <th>Id</th>
            <th class="text-nowrap">Roll no</th>
            <th>Name</th>
            <th>Email</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Address</th>
            <th>City</th>
            <th>Hobbies</th>
            <th>Sub</th>
            <th class="text-center">Image</th>
            <th class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
        <?php
          $conn = mysqli_connect('localhost','root','','school');
          $sql= "select * from students";
          $result = mysqli_query($conn,$sql);
          // print_r($result);
          $action = isset($_GET['action'])?$_GET['action']:"";
          if($action == 'deleted'){
            echo"<div class='alert alert-success'>Record deleted</div>";
          } $i=0;

          while($data = mysqli_fetch_assoc($result)){
          ?>
              <tr id="<?php echo 'row-'.$i; ?>">
              <td><?php echo $data['id']; ?></td>        
              <td><?php echo $data['roll_no']; ?></td>
              <td><?php echo $data['name']; ?></td>
              <td><?php echo $data['email']; ?></td>
              <td><?php echo $data['age']; ?></td>
              <td><?php echo $data['gender']; ?></td>
              <td><?php echo $data['address']; ?></td>
              <td><?php echo $data['city']; ?></td>
              <td><?php echo $data['hobbies']; ?></td>
              <td><?php echo $data['sub']; ?></td>
              <td><img class="img-thumbnail" src="simages/<?php echo $data['image']?>" width="200"></td>
              <td class="text-nowrap">
                <a href="form.php?id=<?=$data['id']?>"><button class="btn btn-info">Edit</button></a>
                &nbsp;
                <button type ="button" class="btn btn-danger delete_btn" onclick="delete_record(<?=$data['id']?>,<?=$i?>)">Delete</button>
              </td>
            </tr> 
        <?php $i++;
         } ?>
       </tbody>
      </table>
      <span class=""><a href="form.php" class="btn btn-dark">Add Student</a></span>&nbsp;
      <sapn class=""><a href="form.php" class="btn btn-dark">Bulk Upload</a></span>
      <?php mysqli_close($conn); ?>
    </div>

  <script type = "text/javascript">
    // confirm record deletion
    function delete_record(id, row){
      if(confirm('Are you sure want to delete?')){
        var html = '';
        $.ajax({
          type:"POST",
          url: "delete_data.php",
          data: { 'id': id },
          dataType: 'json',
          success: function(response){
            console.log(response);
            if(response==1){
              html = "<div class='alert alert-success'>Data deleted Succcessfully</div>";
              $('#row-'+row).remove();
            } else {
              html = "<div class='alert alert-success'>Data could not be deleted.</div>";
            }
            $('#message').html(html);
          }
        });
      } else {
        $('#message').html('');
      }
    }
  </script>
  </body>
</html>