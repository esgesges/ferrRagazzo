<?php
    $conn = mysqli_connect("localhost", "root", "", "ferramentaragazzo");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Ferramenta Ragazzo</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles.css">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    </head>
    <body>
        <header>
            <img id="logo" src="img/logo.png" alt="Ferramenta Ragazzo logo">
            
            <div class="dropdown" id="menuP">
                <button type="button" class="dropdown-toggle">Prodotti</button>
                <div class="dropdown-menu">
                    <div class="dropdown-item" data-value="utensileria" data-href="utensileria.php">Utensileria</div>
                    <div class="dropdown-item" data-value="giardinaggio" data-href="giardinaggio.php">Giardinaggio</div>
                    <div class="dropdown-item" data-value="ferramenta" data-href="ferramenta.php">Ferramenta</div>
                    <div class="dropdown-item" data-value="pitture" data-href="pitture.php">Pitture</div>
                    <div class="dropdown-item" data-value="pulizia" data-href="pulizia.php">Prodotti per la pulizia</div>
                    <div class="dropdown-item" data-value="matElettrico" data-href="elettrico.php">Materiale elettrico</div>
                </div>
            </div>

            <div class="header-buttons">
                <button onclick="window.location.href='info.html'">INFO</button>
            </div>
        </header>
        <div id="offerte">
            <div id="offDesc">

                <?php
                    $rows = [];
                    $titles = [];
                    $descs = [];
                    $sql = "SELECT * FROM prodotti";
                    $result = $conn->query($sql);
                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $rows[] = 'img/' . $row['path'];
                            $titles[] = $row['titolo'];
                            $descs[] = $row['descrizione'];
                        }
                    }
                ?>
                <div id="text-content">
                    <h1 id="offTitle">Titolo</h1>
                    <p id="offDescription">Descrizione</p>
                </div>
                <div id="offImg"><img id="rotator" src="<?=!empty($rows) ? $rows[0] : 'img/ferrRagazzo.svg'?>" alt="Ferramenta Ragazzo"></div>
                
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const imgs = <?=json_encode($rows)?>;
                        const titles = <?=json_encode($titles)?>;
                        const descs = <?=json_encode($descs)?>;
                        
                        if (imgs.length > 0) {
                            let i = 0;
                            
                            // Function to update the display
                            function updateDisplay() {
                                document.getElementById('rotator').src = imgs[i];
                                document.getElementById('offTitle').innerHTML = titles[i];
                                document.getElementById('offDescription').innerHTML = descs[i];
                            }
                            
                            // Update immediately with first item
                            updateDisplay();
                            
                            // Then start the interval
                            setInterval(() => {
                                i += 1;
                                if (i >= imgs.length) {
                                    i = 0;
                                }
                                updateDisplay();
                            }, 6000);
                        }
                    });
                </script>
            </div>
        </div>
        <div class="container">
            <h1 id="cco">CHE COSA OFFRIAMO</h1>
            <table id="offTable">

                <tr>
                    <td>
                        <div class="section-text">Utensileria</div>
                        <div class="section-image">
                            <a href="utensileria.html">
                                <img src="img/accessori.png" alt="Utensileria">
                            </a>
                        </div>
                    </td>
                    <td>
                        <div class="section-text">Giardinaggio</div>
                        <div class="section-image">
                            <a href="giardinaggio.html">
                                <img src="img/giardinaggio.png" alt="Giardinaggio">
                            </a>
                        </div>
                    </td>            
                </tr>
                <tr>
                    <td>
                        <div class="section-text">Ferramenta</div>
                        <div class="section-image">
                            <a href="ferramenta.html">
                                <img src="img/ferrRagazzo.svg" alt="Ferramenta">
                            </a>
                        </div>
                    </td>
                    <td>
                        <div class="section-text">Pitture</div>
                        <div class="section-image">
                            <a href="pitture.html">
                                <img src="img/ferrRagazzo.svg" alt="Pitture">
                            </a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="section-text">Prodotti per la pulizia</div>
                        <div class="section-image">
                            <a href="pulizia.html">
                                <img src="img/ferrRagazzo.svg" alt="Prodotti per la pulizia">
                            </a>
                        </div>
                    </td>
                    <td>
                        <div class="section-text">Materiale elettrico</div>
                        <div class="section-image">
                            <a href="elettrico.html">
                                <img src="img/ferrRagazzo.svg" alt="Materiale elettrico">
                            </a>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <!-- Floating action buttons -->
        <a href="#" class="floating-btn scroll-top-btn" id="scrollTopBtn">
            <i class="fas fa-arrow-up"></i>
            <span class="tooltip">Torna su</span>
        </a>
        
        <script>
            
            // Scroll to top button visibility
            window.addEventListener('scroll', function() {
                const scrollTopBtn = document.getElementById('scrollTopBtn');
                if (window.pageYOffset > 300) {
                    scrollTopBtn.classList.add('visible');
                } else {
                    scrollTopBtn.classList.remove('visible');
                }
            });
            
            // Smooth scroll to top
            document.getElementById('scrollTopBtn').addEventListener('click', function(e) {
                e.preventDefault();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
            
            // Custom dropdown behavior
            (function() {
                const dropdown = document.getElementById('menuP');
                if (!dropdown) return;
                const toggle = dropdown.querySelector('.dropdown-toggle');
                const menu = dropdown.querySelector('.dropdown-menu');
                if (!toggle || !menu) return;

                function closeMenu() {
                    dropdown.classList.remove('open');
                }

                toggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdown.classList.toggle('open');
                });

                menu.addEventListener('click', function(e) {
                    const item = e.target.closest('.dropdown-item');
                    if (!item) return;
                    const label = item.textContent.trim();
                    const href = item.getAttribute('data-href');
                    toggle.textContent = label;
                    closeMenu();
                    if (href) {
                        window.location.href = href;
                    }
                });

                document.addEventListener('click', function() {
                    closeMenu();
                });

                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        closeMenu();
                    }
                });
            })();
            
            // No smooth scrolling
        </script>
    </body>
</html>