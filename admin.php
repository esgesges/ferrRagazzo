<?php
    $conn = mysqli_connect("100.70.0.1", "root", "password", "ferramentaragazzo");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    // Handle product deletion
    if(isset($_POST['delete_product']) && isset($_POST['product_id'])) {
        $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
        $delete_sql = "DELETE FROM prodotti WHERE idProdotto = '$product_id'";
        
        if(mysqli_query($conn, $delete_sql)) {
            echo "<script>alert('Prodotto eliminato con successo!');</script>";
        } else {
            echo "<script>alert('Errore durante l\'eliminazione: " . mysqli_error($conn) . "');</script>";
        }
    }
    
    // Handle product addition
    if(isset($_POST['add_product'])) {
        $titolo = mysqli_real_escape_string($conn, $_POST['titolo']);
        $descrizione = mysqli_real_escape_string($conn, $_POST['descrizione']);
        
        // Handle file upload
        $target_dir = "img/";
        $upload_success = false;
        $path = "";
        
        if(isset($_FILES["product_image"]) && $_FILES["product_image"]["error"] == 0) {
            $file_name = basename($_FILES["product_image"]["name"]);
            $target_file = $target_dir . $file_name;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            
            // Check if image file is an actual image
            $check = getimagesize($_FILES["product_image"]["tmp_name"]);
            if($check !== false) {
                // Check file size (limit to 5MB)
                if ($_FILES["product_image"]["size"] <= 5000000) {
                    // Allow certain file formats
                    if($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif" ) {
                        // Rename file to avoid duplicates
                        $new_file_name = uniqid() . "." . $imageFileType;
                        $target_file = $target_dir . $new_file_name;
                        
                        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
                            $path = $target_file;
                            $upload_success = true;
                        } else {
                            echo "<script>alert('Si è verificato un errore durante il caricamento del file.');</script>";
                        }
                    } else {
                        echo "<script>alert('Sono consentiti solo file JPG, JPEG, PNG e GIF.');</script>";
                    }
                } else {
                    echo "<script>alert('Il file è troppo grande. Dimensione massima: 5MB.');</script>";
                }
            } else {
                echo "<script>alert('Il file caricato non è un\'immagine.');</script>";
            }
        } else {
            echo "<script>alert('Seleziona un\'immagine da caricare.');</script>";
        }
        
        // Only insert into database if upload was successful
        if($upload_success) {
            $insert_sql = "INSERT INTO prodotti (titolo, descrizione, path) VALUES ('$titolo', '$descrizione', '$path')";
            
            if(mysqli_query($conn, $insert_sql)) {
                echo "<script>alert('Prodotto aggiunto con successo!');</script>";
            } else {
                echo "<script>alert('Errore durante l\'aggiunta: " . mysqli_error($conn) . "');</script>";
                // Delete the uploaded file if database insertion fails
                if(file_exists($path)) {
                    unlink($path);
                }
            }
        }
    }
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Admin Panel - Ferramenta Ragazzo</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            h1 { color: #333; }
            table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
            th, td { padding: 8px; text-align: left; }
            th { background-color: #f2f2f2; }
            .form-container { margin-top: 20px; border: 1px solid #ddd; padding: 15px; background-color: #f9f9f9; }
            .form-group { margin-bottom: 15px; }
            label { display: block; margin-bottom: 5px; font-weight: bold; }
            input[type="text"] { width: 100%; padding: 8px; box-sizing: border-box; }
            button, input[type="submit"] { background-color: #4CAF50; color: white; padding: 10px 15px; border: none; cursor: pointer; margin-right: 10px; }
            button:hover, input[type="submit"]:hover { background-color: #45a049; }
            .delete-btn { background-color: #f44336; }
            .delete-btn:hover { background-color: #d32f2f; }
            .hidden { display: none; }
        </style>
        <script>
            function toggleForm(formId) {
                var form = document.getElementById(formId);
                if (form.classList.contains('hidden')) {
                    form.classList.remove('hidden');
                } else {
                    form.classList.add('hidden');
                }
            }
        </script>
    </head>
    <body>
        <h1>Prodotti in evidenzia:</h1>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Titolo</th>
                <th>Descrizione</th>
                <th>Path</th>
                <th>Azioni</th>
            </tr>
            <?php
                $sql = "SELECT * FROM prodotti";
                $result = $conn->query($sql);
                foreach($result as $row){
                    echo "<tr>";
                    echo "<td>{$row['idProdotto']}</td>";
                    echo "<td>{$row['titolo']}</td>";
                    echo "<td>{$row['descrizione']}</td>";
                    echo "<td>{$row['path']}</td>";
                    echo "<td>
                            <form method='post' style='display:inline;'>
                                <input type='hidden' name='product_id' value='{$row['idProdotto']}'>
                                <input type='submit' name='delete_product' value='Elimina' class='delete-btn'>
                            </form>
                          </td>";
                    echo "</tr>";
                }
            ?>
        </table>

        <button onclick="toggleForm('addProductForm')">Aggiungi prodotto</button>
        
        <!-- Add Product Form -->
        <div id="addProductForm" class="form-container hidden">
            <h2>Aggiungi Nuovo Prodotto</h2>
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="titolo">Titolo:</label>
                    <input type="text" id="titolo" name="titolo" required>
                </div>
                <div class="form-group">
                    <label for="descrizione">Descrizione:</label>
                    <input type="text" id="descrizione" name="descrizione" required>
                </div>
                <div class="form-group">
                    <label for="product_image">Immagine Prodotto:</label>
                    <input type="file" id="product_image" name="product_image" accept="image/*" required>
                </div>
                <input type="submit" name="add_product" value="Salva Prodotto">
            </form>
        </div>
    </body>
</html>