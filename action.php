<?php
   $con=mysqli_connect('localhost','root','','dance');
   session_start();

  // ADMIN LOGIN
  if (isset($_POST['admin'])) {
  	$name=trim($_POST['name']);
  	$psw=trim($_POST['psw']);
  	$sql="SELECT * FROM admin WHERE name=? AND psw=?";
  	$stmt = mysqli_prepare($con, $sql);
  	mysqli_stmt_bind_param($stmt, "ss", $name, $psw);
  	mysqli_stmt_execute($stmt);
  	$result = mysqli_stmt_get_result($stmt);
  	if(mysqli_num_rows($result)==1){
       $_SESSION['name']=$name;
       header('location:../admin.php');
  	} else {
      echo "<h2>Invalid admin credentials</h2>";
    }
  }

  // PERFORMER SIGNUP
  if (isset($_POST['signup'])) {
  	$name=trim($_POST['name']);
  	$usn=trim($_POST['usn']);
  	$email=trim($_POST['email']);
  	$sem=trim($_POST['sem']);
    $section=trim($_POST['section']);    
    $gender=trim($_POST['gender']);
  	$psw=trim($_POST['psw']);
  	$repsw=trim($_POST['repsw']);
  	if ($psw==$repsw) {
 	    $sql="SELECT * FROM performer WHERE usn=?";
 	    $stmt = mysqli_prepare($con, $sql);
 	    mysqli_stmt_bind_param($stmt, "s", $usn);
 	    mysqli_stmt_execute($stmt);
 	    $result = mysqli_stmt_get_result($stmt);
 	    if(mysqli_num_rows($result)>0){
         echo "<h2>USERNAME ALREADY EXISTS. PLEASE ENTER A VALID USERNAME</h2>";
     	}else{
     		$sql="INSERT INTO performer (pid, wid, pname, usn, sem, email, psw, dance_style, dtime, ddate, bookingdate, payment, section, gender)
               VALUES (NULL, '0', ?, ?, ?, ?, ?, '0', '0', '0000-00-00', '0000-00-00', 'not', ?, ?)";
     		$stmt = mysqli_prepare($con, $sql);
     		mysqli_stmt_bind_param($stmt, "sssssss", $name, $usn, $sem, $email, $psw, $section, $gender);
     		if (mysqli_stmt_execute($stmt)) {
     			header('location:login.php');
     		} else {
     			echo "<h2>Signup failed. Please try again.</h2>";
     		}
     	} 
  	}else{
 		 echo "<h2>Passwords do not match</h2>";
  	}
  }

  // PERFORMER LOGIN
  if (isset($_POST['login'])) {
    $usn = trim($_POST['usn']);
    $psw = trim($_POST['psw']);

    $sql = "SELECT * FROM performer WHERE usn=? AND psw=?";
    $stmt = mysqli_prepare($con, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $usn, $psw);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $_SESSION['usn'] = $row['usn'];
            $_SESSION['uid'] = $row['pid'];
            $_SESSION['urname'] = $row['pname'];
            header('location:../profile.php');
            exit();
        } else {
            echo "<h2>Invalid login credentials</h2>";
        }
    } else {
        echo "<h2>Database error: Unable to prepare statement.</h2>";
    }
}


  
// COORDINATOR SIGNUP
if (isset($_POST['tsignup'])) {
  $name = trim($_POST['cname']);            // Co-Ordinator Name
  $usn = trim($_POST['usn']);               // USN
  $gender = trim($_POST['gender']);         // Gender
  $username = trim($_POST['username']);     // Unique ID like 1DT20IS058
  $psw = trim($_POST['password']);          // Password
  $repsw = trim($_POST['repsw']);           // Repeat password

  if ($psw === $repsw) {
      // Check if USN or username already exists
      $sql = "SELECT * FROM coordinator WHERE usn=? OR username=?";
      $stmt = mysqli_prepare($con, $sql);
      mysqli_stmt_bind_param($stmt, "ss", $usn, $username);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);

      if (mysqli_num_rows($result) > 0) {
          echo "<h2>USN or Username already exists. Please choose another.</h2>";
      } else {
          // Hash password for security
          $hashedPassword = password_hash($psw, PASSWORD_DEFAULT);

          $sql = "INSERT INTO coordinator (cname, usn, gender, username, password)
                  VALUES (?, ?, ?, ?, ?)";
          $stmt = mysqli_prepare($con, $sql);
          mysqli_stmt_bind_param($stmt, "sssss", $name, $usn, $gender, $username, $hashedPassword);

          if (mysqli_stmt_execute($stmt)) {
              header("location:tlogin.php"); // Redirect to login after successful signup
              exit();
          } else {
              echo "<h2>Signup failed. Please try again.</h2>";
          }
      }
  } else {
      echo "<h2>Passwords do not match</h2>";
  }
}

// COORDINATOR LOGIN
if (isset($_POST['tlogin'])) {
  $username = trim($_POST['username']);
  $psw = trim($_POST['psw']);

  $sql = "SELECT * FROM coordinator WHERE username=?";
  $stmt = mysqli_prepare($con, $sql);
  mysqli_stmt_bind_param($stmt, "s", $username);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  if ($row = mysqli_fetch_assoc($result)) {
      if (password_verify($psw, $row['password'])) {
          $_SESSION['cid'] = $row['cid'];
          $_SESSION['cname'] = $row['cname'];
          $_SESSION['username'] = $row['username'];
          header("location:../profile.php"); // Redirect after successful login
          exit();
      } else {
          echo "<h2>Incorrect password.</h2>";
      }
  } else {
      echo "<h2>No user found with this username.</h2>";
  }
}

  
  if (isset($_POST['workshop'])) {
    $wname=$_POST['wname'];
    $wdate=$_POST['date'];
    $wtime=$_POST['time'];
    $wdesc=$_POST['desc'];
    $venue=$_POST['venue'];
    $sql="SELECT * FROM workshop WHERE wshow='1'";
    $run=mysqli_query($con,$sql);
    if(mysqli_num_rows($run)>0){
      header('location:../admin.php');
    }else{
      $sql="INSERT INTO `workshop` (`wname`, `wdate`, `venue`, `wshow`, `wdesc`, `wtime`) 
      VALUES ('$wname', '$wdate', '$venue', '1', '$wdesc', '$wtime')";
      
     $run=mysqli_query($con,$sql);
     if ($run) {
          header('location:../admin.php');
     }
    }
   } 


   if (isset($_POST['pjoin1'])) {
       $wid=$_POST['wid'];
       $tid=$_POST['tid'];
       $did=$_POST['did'];
       $pid=$_SESSION['uid'];
       $bdate=date("Y-m-d");
       $sql="UPDATE performer SET wid='$wid',ddate='$did',dtime='$tid',bookingdate='$bdate',payment='yes' WHERE pid='$pid'";
       $run=mysqli_query($con,$sql);
       if ($run) {
          echo "thanks for joining";
     }
    } 
 if (isset($_POST['dance1'])||isset($_POST['dance2'])||isset($_POST['dance3'])||isset($_POST['dance4'])||isset($_POST['dance5'])||isset($_POST['dance6'])||isset($_POST['dance7'])||isset($_POST['dance8'])) {
       $did=$_POST['did'];
       $pid=$_SESSION['uid'];
       $sql="UPDATE performer SET dance_style='$did' WHERE pid='$pid'";
       $run=mysqli_query($con,$sql);
       if ($run) {
          echo "Your Club :"." ".$did." "." is Selected";
     }
     
 }
   if (isset($_POST['tjoin1'])) {
       $wid=$_POST['wid'];
       $cid=$_SESSION['cid'];
       $sql="UPDATE coach SET wid='$wid', cselect='In Action' WHERE cid='$cid'";
       $run=mysqli_query($con,$sql);
       if ($run) {
          echo "thanks for joining";
     }
    } 
 if (isset($_POST['dance11'])||isset($_POST['dance21'])||isset($_POST['dance31'])||isset($_POST['dance41'])||isset($_POST['dance51'])||isset($_POST['dance61'])||isset($_POST['dance71'])||isset($_POST['dance81'])) {
       $did=$_POST['did'];
       $cid=$_SESSION['cid'];
       $sql="UPDATE coach SET dstyle='$did' WHERE cid='$cid'";
       $run=mysqli_query($con,$sql);
       if ($run) {
          echo "Your Club:"." ".$did." "." is Selected";
     }
     
 }
if (isset($_POST['cdetail'])) {
  $sql="SELECT * FROM coach WHERE cselect='In Action'";
  $run=mysqli_query($con,$sql);
  while ($row=mysqli_fetch_array($run)) {
        $cname=$row['cname'];
        $cid=$row['cid'];
        $dstyle=$row['dstyle'];
        $gender=$row['gender'];
           echo " <div class='row'>
         <div class='col-md-3'>
           <h4 class='text-center text-white'> $cname</h4>
         </div>
         <div class='col-md-2'>
           <h4 class='text-center text-white'>$gender</h4>
         </div>
         <div class='col-md-3'>
           <h4 class='text-center text-white'>$dstyle</h4>
         </div>
         <div class='col-md-2'>
              <h4 class='text-center text-white'><div class='btn btn-outline-info select' cid='$cid'>Select</div></h4>
         </div>
         <div class='col-md-2'>
             <h4 class='text-center text-white'><div class='btn btn-outline-danger reject' cid='$cid'>Reject</div></h4>
         </div>
       </div>";
  }
}
if (isset($_POST['selected1'])) {
  $sql="SELECT * FROM coach WHERE cselect='Selected'";
  $run=mysqli_query($con,$sql);
  while ($row=mysqli_fetch_array($run)) {
        $cname=$row['cname'];
        $cid=$row['cid'];
        $dstyle=$row['dstyle'];
        $gender=$row['gender'];
        $cselect=$row['cselect'];
           echo " <div class='row'>
         <div class='col-md-3'>
           <h4 class='text-center text-white'> $cname</h4>
         </div>
         <div class='col-md-3'>
           <h4 class='text-center text-white'>$gender</h4>
         </div>
         <div class='col-md-3'>
           <h4 class='text-center text-white'>$dstyle</h4>
         </div>
         <div class='col-md-3'>
           <h4 class='text-center text-white'>$cselect</h4>
         </div>
         
       </div>";
  }
}
if (isset($_POST['cselect'])) {
  $cid=$_POST['cid'];
  $sql="UPDATE coach SET  cselect='Selected' WHERE cid='$cid'";
       $run=mysqli_query($con,$sql);
       if ($run) {
          echo "Co-Ordinator Selected";
     }
}
if (isset($_POST['creject'])) {
  $cid=$_POST['cid'];
  $sql="UPDATE coach SET  cselect='Not Selected' WHERE cid='$cid'";
       $run=mysqli_query($con,$sql);
       if ($run) {
          echo "Co-Ordinator not Selected";
     }
}
if (isset($_POST['submit1'])) {
   $did=$_POST['did'];
  $sql="SELECT * FROM coach WHERE cselect='In Action'AND dstyle='$did'";
  $run=mysqli_query($con,$sql);
  while ($row=mysqli_fetch_array($run)) {
        $cname=$row['cname'];
        $cid=$row['cid'];
        $dstyle=$row['dstyle'];
        $gender=$row['gender'];
           echo " <div class='row'>
         <div class='col-md-3'>
           <h4 class='text-center text-white'> $cname</h4>
         </div>
         <div class='col-md-2'>
           <h4 class='text-center text-white'>$gender</h4>
         </div>
         <div class='col-md-3'>
           <h4 class='text-center text-white'>$dstyle</h4>
         </div>
         <div class='col-md-2'>
              <h4 class='text-center text-white'><div class='btn btn-outline-info select' cid='$cid'>Select</div></h4>
         </div>
         <div class='col-md-2'>
             <h4 class='text-center text-white'><div class='btn btn-outline-danger reject' cid='$cid'>Reject</div></h4>
         </div>
       </div>";
  }

}
if (isset($_POST['psubmit1'])) {
 $did=$_POST['did'];
 $bid=$_POST['bid'];
 $sql="SELECT * FROM performer WHERE dance_style='$did'AND bookingdate='$bid' ";
 $run=mysqli_query($con,$sql);
 while ($row=mysqli_fetch_array($run)) {
   $pname=$row['pname'];
   $sem=$row['sem'];
   $dstyle=$row['dance_style'];
   $bdate=$row['bookingdate'];
    echo " <div class='row'>
         <div class='col-md-3'>
           <h4 class='text-center text-white'> $pname</h4>
         </div>
         <div class='col-md-3'>
           <h4 class='text-center text-white'>$sem</h4>
         </div>
         <div class='col-md-3'>
           <h4 class='text-center text-white'>$dstyle</h4>
         </div>
         <div class='col-md-3'>
           <h4 class='text-center text-white'>$bid</h4>
         </div>
         
       </div>";
 }
}
if (isset($_POST['preg1'])) {
 $sql="SELECT * FROM performer WHERE payment='yes' ";
 $run=mysqli_query($con,$sql);
 while ($row=mysqli_fetch_array($run)) {
   $pname=$row['pname'];
   $sem=$row['sem'];
   $dstyle=$row['dance_style'];
   $bdate=$row['bookingdate'];
    echo " <div class='row'>
         <div class='col-md-3'>
           <h4 class='text-center text-white'> $pname</h4>
         </div>
         <div class='col-md-3'>
           <h4 class='text-center text-white'>$sem</h4>
         </div>
         <div class='col-md-3'>
           <h4 class='text-center text-white'>$dstyle</h4>
         </div>
         <div class='col-md-3'>
           <h4 class='text-center text-white'>$bdate</h4>
         </div>
         
       </div>";
 }
}
if (isset($_POST['ptreg1'])) {
 $bid=date("Y-m-d");
 $sql="SELECT * FROM performer WHERE bookingdate='$bid'AND payment='yes'";
 $run=mysqli_query($con,$sql);
 while ($row=mysqli_fetch_array($run)) {
   $pname=$row['pname'];
   $sem=$row['sem'];
   $dstyle=$row['dance_style'];
   $bdate=$row['bookingdate'];
    echo " <div class='row'>
         <div class='col-md-3'>
           <h4 class='text-center text-white'> $pname</h4>
         </div>
         <div class='col-md-3'>
           <h4 class='text-center text-white'>$sem</h4>
         </div>
         <div class='col-md-3'>
           <h4 class='text-center text-white'>$dstyle</h4>
         </div>
         <div class='col-md-3'>
           <h4 class='text-center text-white'>$bdate</h4>
         </div>
         
       </div>";
 }
}

if (isset($_POST['uptw'])) {
  $lastdate=date("Y-m-d");
     $sql="SELECT * FROM workshop WHERE wshow='1'AND wdate='$lastdate' ";
     $run=mysqli_query($con,$sql);
   if (mysqli_num_rows($run)==1){
      $sql="UPDATE workshop SET wshow='0' WHERE wdate='$lastdate'";
      $run=mysqli_query($con,$sql);
      
   }
 }
if (isset($_POST['cancle1'])) {
   $pid=$_POST['pid'];
   $sql="DELETE FROM performer WHERE pid='$pid' ";
   $run=mysqli_query($con,$sql);
   if ($run) {
       unset($_SESSION['uid']);
   }
 }


  // The rest of your logic (workshop creation, joining, selection, etc.) continues below...
  // It's left unchanged but can be cleaned up similarly if needed for security.

  // If you'd like, I can apply the same enhancements to the rest of the file — just let me know!
?>
