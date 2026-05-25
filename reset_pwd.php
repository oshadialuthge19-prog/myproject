<<<<<<< HEAD
<?php

include("Includes/db.php");

$email = $_POST['email'];

$new_password = password_hash(
    $_POST['new_password'],
    PASSWORD_DEFAULT
);

$query = "UPDATE users
SET usersPwd='$new_password'
WHERE usersEmail='$email'";

$result = mysqli_query($conn, $query);

if($result){

    echo "Password Updated Successfully";

}else{

    echo "Error";

}

=======
<?php

include("Includes/db.php");

$email = $_POST['email'];

$new_password = password_hash(
    $_POST['new_password'],
    PASSWORD_DEFAULT
);

$query = "UPDATE users
SET usersPwd='$new_password'
WHERE usersEmail='$email'";

$result = mysqli_query($conn, $query);

if($result){

    echo "Password Updated Successfully";

}else{

    echo "Error";

}

>>>>>>> ee59040cc70d5b4e2460ecdb82f21e941850dc09
?>