<?php

    $pageTitle='All Items';
    include 'header.php';
    
    if(isset($_SESSION['isLogged']) == FALSE){
                    header('Location: index.php');
                    exit;
                    }
        
?>

<body id="reportsPage" class="bg02">
    

            <?php
               //This is an empy page 
                    
            
            
            
            
            ?>

</body>
        <?php
        include 'footer.html';
        ?>
