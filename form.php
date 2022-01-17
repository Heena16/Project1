<html>
	<head>
		<title>Students Form 1</title>
		<?php 
			$conn = mysqli_connect('localhost','root','','school');
		?>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	</head>
	<body>
		<?php
			// View Edit Student details
			if(isset($_GET['id'])){
			    $id = $_GET['id'];
			    $query="SELECT * from students WHERE id = '" . $id . "'";
			    $result = mysqli_query($conn,$query);
			    $squery = mysqli_fetch_assoc($result);
			    //echo "<pre>"; print_r($squery); die();
			    if($squery) {
			    	$id = $squery['id'];
					$roll_no = $squery['roll_no'];
			    	$name = $squery['name'];
					$email = $squery['email'];
					$age = $squery['age'];
					$gender = $squery['gender'];
					$address = $squery['address'];
					$city = $squery['city'];
					$hobbies =$squery['hobbies'];
					$sub = $squery['sub'];
					$h= explode(",",$squery['hobbies']);
					$s = explode(",",$squery['sub']);
					$image =$squery['image'];
				} else {
			     	echo "Error: " . $squery . "" . mysqli_error($conn);
			    }			 
			}

			// Add/Edit Student
			if(isset($_POST['submit'])){
				$name = $_POST['name'];
				$email = $_POST['email'];
				$age = $_POST['age'];
				$roll_no = $_POST['roll_no'];
				$gender = $_POST['gender'];
				$address = $_POST['address'];
				$city = $_POST['city'];
				$hobbies = implode(',',$_POST['hobbies']);
				$sub = implode(',',$_POST['sub']);
								
				//FIle UPload
				if($_FILES["image"]["name"]){
					$filename = $_FILES["image"]["name"];
		    		$tempname = $_FILES["image"]["tmp_name"];    
		        	$folder = "simages/".$filename;
			        if(move_uploaded_file($tempname, $folder)){
				    	echo "The file ".$filename. " has been uploaded successfully.";
				 	} else {
				 		$filename = '';
				    	echo "Sorry, there was an error uploading your file.";
				  	}
				} else if(isset($_GET['id'])){
				 	$filename = $image;
				}
				else{
					$filename = '';
				}

				//sql query for UPDATE and ADD
				if(isset($_GET['id'])){
					$sqlquery = "UPDATE `students` SET `roll_no`='".$roll_no."',`name`='".$name."',`email`='".$email."',`age`='".$age."',`gender`='".$gender."',`address`='".$address."',`city`='".$city."',`hobbies`='".$hobbies."',`sub`='".$sub."',`image`='".$filename."' WHERE id = '".$_GET['id']."'";
					//echo $sqlquery;
					//die();
				} else {
					$sqlquery = "INSERT INTO `students`(`name`, `email`, `age`, `roll_no`, `gender`, `address`, `city`, `hobbies`,`sub`,`image`) VALUES ('".$name."','".$email."','".$age."','".$roll_no."','".$gender."','".$address."','".$city."','".$hobbies."','".$sub."','".$filename."')";
					//echo $sqlquery;
					//die();
					
				}			

				$squery = mysqli_query($conn, $sqlquery);
					if($squery){
						if(isset($_GET['id'])){
							echo "Record Updated Successfully...";
							header('location:student_list.php'); // record update
						} else {
							echo "Record Added Successfully..."; // record insertion
							header('location:form.php');
						}
					} else{
						echo "Record Insertion Failed...";
					}
			}

			//BULK UPLOAD
			if(isset($_POST['put_data'])){
				//echo 'hello its new';
				$filetype = pathinfo($_FILES['bulk_upload']["name"], PATHINFO_EXTENSION);
					//echo "<pre>"; print_r($filetype); die();
					if($_FILES['bulk_upload']['error']>0){
						echo "error: File not Found...";
					} else if($filetype == "xlsx" || $filetype == "csv") {
						$file = fopen($_FILES['bulk_upload']['tmp_name'], "a+"); //a+ is for read and write called mode
						$i=0;
						while(!feof($file)) {
							$row = fgetcsv($file);
							if($i>0&& !empty($row))
							{
								// $arr_data[] = explode(',',$row);
								$arr_data[] = $row;
							} $i++;
						} 
						echo '<pre>';
						foreach($arr_data as $key => $arr){
							// $hobby = ''; //1-Dancing,2-Singing,3-Reading,4-Painting
							// echo $arr[8]; echo '<br>';
							$hobs = explode(',', $arr[8]);
							// print_r($hobs);
							foreach ($hobs as $key => $value) {
								if(trim($value) == 'Dancing'){
									$hobs[$key] = '1';
								} else if(trim($value) == 'Singing'){
									$hobs[$key] = '2';
								} else if(trim($value) == 'Reading'){
									$hobs[$key] = '3';
								} else if(trim($value) == 'Painting'){
									$hobs[$key] = '4';
								} 	
							}
							// print_r($hobs);
							$hobbs = implode(',', $hobs);
							// echo $hobbs; echo "<br>";
							// print_r($hobs);
							// echo $hobbs; echo "<br>";
							$subj = explode(',', $arr[9]);
							// print_r($hobs);
							foreach ($subj as $key => $value) { // 1-Maths,2-Chemistry,3-Physics,4-English,5-Hindi
								if(trim($value) == 'Maths'){
									$subj[$key] = '1';
								} else if(trim($value) == 'Chemistry'){
									$subj[$key] = '2';
								} else if(trim($value) == 'Physics'){
									$subj[$key] = '3';
								} else if(trim($value) == 'English'){
									$subj[$key] = '4';
								} else if(trim($value) == 'Hindi'){
									$subj[$key] = '5';
								}  	
							}
							// print_r($hobs);
							$subject = implode(',', $subj);
							$sqlquery = "INSERT INTO `students`(`roll_no`, `name`, `email`, `age`, `gender`, `address`, `city`, `hobbies`,`sub`) VALUES ('$arr[1]','$arr[2]','$arr[3]','$arr[4]','$arr[5]','$arr[6]','$arr[7]','$hobbs','$subject')";
							$squery = mysqli_query($conn, $sqlquery);
							//if(isset($sqlquery)){ print_r($sqlquery); }
							if($squery){
								echo " - Added Successfully...";
							} else{
								echo " - Failed...";
							} echo "<br>";
						}
					}
					fclose($file);
			}

		?>
		<h1 style="text-align:center; font-size:30px; font-weight:bold;">Data Entry Form</h1>
		<form name="student" method="POST" enctype="multipart/form-data">
			<div class="w-50 mx-auto">
				<table class="table table-bordered table-striped text-sm" style="font-size:20px;">
					<thead >
						<tr>
							<td><a href="student_list.php" class="btn px-5 btn-dark">List</a></td>
							<td>
								<!-- <form method="POST" enctype="multipart/form-data" > -->
									<div class="input-group">
										<input type="file" name="bulk_upload" value="Upload File" class="form-control">
										<input type="submit" name="put_data" class="btn btn-primary" value="Upload">
									</div>
								<!-- </form> -->
							</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th>Name  :</th> 
							<td>
								<input type="text" class="form-control form-control-sm" name="name"  placeholder="enter name" autofocus autocomplete="off" value="<?php if(isset($name)){ echo $squery['name']; } ?>" />
								</span>
							</td>
						</tr>
						<tr>
							<th>E mail : </th>
							<td>
								<input type="email" class="form-control form-control-sm" name="email" placeholder="email" value="<?php if(isset($email)){ echo $email; } ?>" autocomplete="off">
							</td>
						</tr>
						<tr>
							<th>Age : </th> 
							<td>
								<input type="text" class="form-control form-control-sm" name="age" value="<?php if(isset($age)){ echo $age; } ?>" placeholder="enter age" />
							</td>
						</tr>
						<tr>
							<th>Roll_no : </th>
							<td>
								<input type="text" class="form-control form-control-sm" name="roll_no" value="<?php if(isset($roll_no)){ echo $roll_no; } ?>" placeholder="enter roll_no">
							</td>
						</tr>
						<tr>
							<th> Gender : </th>
							<td> 
								<input id="gml" type="radio" name="gender" value="male" <?php if(isset($gender) && $gender== "male"){?> checked <?php } ?> > Male 
								<input id="gfl" type="radio" name="gender" value="female" <?php if(isset($gender) && $gender == "female"){?> checked <?php } ?> > Female
							</td>
						</tr>
						<tr>
							<th>Address : </th>
							<td>
								<textarea class="form-control form-control-sm" rows="8" cols="22" name="address"><?php if(isset($address)){ echo $address; } ?></textarea>
							</td>
						</tr>
						<tr>
							<th>City : </th>
							<td>
								<select id="city" name="city" class="form-control form-control-sm toggle-dropdown" >
									<option value="Tanakpur" <?php if(isset($city) && $city == "Tanakpur") {?> selected <?php } ?> >Tanakpur</option>
									<option value="Haldwani" <?php if (isset($city) && $city == "Haldwani") { ?> selected <?php } ?> >Haldwani</option>
									<option value="Almora" <?php if (isset($city) && $city == "Almora") { ?> selected <?php } ?>>Almora</option>
									<option value="Pithoragarh" <?php if (isset($city) && $city == "Pithoragarh") { ?> selected <?php } ?>>Pithoragarh</option>
									<option value="Dehradun" <?php if (isset($city) && $city == "Dehradun") { ?> selected <?php } ?>>Dehradun</option>
								</select>
							</td>
						</tr>
						<tr>
							<th>Hobbies : </th>
							<td>
								<?php $hobbies = array('1'=>'Dancing','2'=>'Singing','3'=>'Reading','4'=>'Painting');
									foreach($hobbies as $key=>$val) { ?>
										<input type="checkbox" <?php if(isset($h) && in_array($key, $h)){ echo 'checked'; } ?> name="hobbies[]" value="<?=$key?>" ><?=$val?>
								<?php } ?>

									<!--<input type="checkbox" name="hobbies[]" value="Singing" 
										<?php if (empty($h)){ 
												$h ="" ; 
											} else {?> checked <?php } ?> > Singing
									<input type="checkbox" name="hobbies[]" value="Dancing" <?php if (empty($h)){ 
												$h ="" ; 
											} else {?> checked <?php } ?> >Dancing
									<input type="checkbox" name="hobbies[]" value="Reading" <?php if (empty($h)){ 
												$h ="" ; 
											} else {?> checked <?php } ?> >Reading
									<input type="checkbox" name="hobbies[]" value="Painting" <?php if (empty($h)){ 
												$h ="" ; 
											} else {?> checked <?php } ?> >Painting-->
							</td>
						</tr>
						<tr>
							<th>Subject :</th>
							<td>
								<?php
									$sub = array('1'=>'Maths','2'=>'Chemistry','3'=>'Physics','4'=>'English','5'=> 'Hindi');
									foreach($sub as $key=>$val)
									{?>
										<input type ="checkbox" <?php if(isset($s) && in_array($key, $s)){ echo 'checked'; } ?> name ="sub[]" value ="<?=$key?>" ><?=$val?>
								<?php }	
									//$sub_key_arr = array_keys($sub);
								?>
								<!--<input type="checkbox" name="sub[]" value="Maths" <?php if (empty($s)){ 
									$s ="" ; } else {?> checked <?php } ?>  >Maths
								<input type="checkbox" name="sub[]" value="Chemistry" <?php if (empty($s)){ 
									$s ="" ; } else {?> checked <?php } ?> >Chemistry
								<input type="checkbox" name="sub[]" value="Physics" <?php if (empty($s)){ 
									$s ="" ; } else {?> checked <?php } ?>  >Physics
								<input type="checkbox" name="sub[]" value="English" <?php if (empty($s)){ 
									$s ="" ; } else {?> checked <?php } ?>  >English
								<input type="checkbox" name="sub[]" value="Hindi" <?php if (empty($s)){ 
									$s ="" ; } else {?> checked <?php } ?> >Hindi-->
							</td>
						</tr>
						<tr>
							<th>Upload Image : </th>
							<td><input class="form-control form-control-sm" onchange="imageshow(this)" type="file" name="image" accept="image/*">
								<img class="img-thumbnail mt-2" id="preview" src="<?php if(isset($squery['image'])){ echo 'simages/'.$squery['image']; } ?>" width="200">
							</td>
						</tr>
						<tr>
							<td></td>
							<td><input type="Submit" name="submit" class="btn btn-primary" value="submit">&nbsp;</td>
						</tr>
					</tbody>
				</table>
			</div>
		</form>
		<script type="text/javascript">
			function imageshow(input) {
			    if (input.files && input.files[0]) {
			      var reader = new FileReader();
			      reader.onload = function (e) {
			        var img = new Image;
			        img.onload = function() {
			        };
			        img.src = reader.result;
			        document.getElementById('preview').src = e.target.result;
			      };
			      reader.readAsDataURL(input.files[0]);
			    }
			}
		</script>
	</body>
</html>