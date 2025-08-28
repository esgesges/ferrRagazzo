<?php
    $conn = mysqli_connect("localhost", "root", "", "ferramentaragazzo");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>


<!DOCTYPE html>
<html>
    <body>
        <h1>Prodotti in evidenzia:</h1>
        <table>
            <?php
                $sql = "SELECT * FROM prodotti";
                $result = $conn->query($sql);
                foreach($row in $result){
                    echo "<tr>"
                    echo "<td> $row['titolo'] </td>"
                }
            ?>
        </table>
    </body>
</html>