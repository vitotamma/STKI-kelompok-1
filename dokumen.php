<?php

include './connection.php';

$sql = "SELECT * FROM document";
$queryDoc = mysqli_query($conn, $sql);

while ($query = mysqli_fetch_assoc($queryDoc)) {
    echo '<option value="'.$query['id'].'">'.$query['document'].'</option>';
    ?>    
    
<?php 
}
?>