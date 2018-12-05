<?php

session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/tooplate.css">
    <link rel="stylesheet" href="css/my.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/customjs.js"></script>
    <script src="js/bootstrap.min.js"></script>

    
<title><?= $pageTitle;?></title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

   
<div class="row">
                <div class="col-12">
                    <nav class="navbar navbar-expand-xl navbar-light bg-light">
                        <a class="navbar-brand" href="index.php">
                            
                            <h1 class="tm-site-title mb-0">junk catalog</h1>
                        </a>
                        
                        

                        <button class="navbar-toggler ml-auto mr-0" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav mx-auto">
                                
                                
                               
                          
                                <li class="nav-item">
                                    <a class="nav-link" href="AllItems.php">All Items</a>
                                </li>
                                
                                <li class="nav-item">
                                    <a class="nav-link" href="AddItem.php">Add New Item</a>                                  
                                </li>

                                 <li class="nav-item">
                                    <a class="nav-link" href="CheckIn.php">Item Check In</a>                                  
                                </li>
                                
                                <li class="nav-item">
                                    <a class="nav-link" href="CheckOutCutstom.php">Item Check Out</a>                                  
                                </li>
                                
                                 <li class="nav-item">
                                    <a class="nav-link" href="Transactions.php">Transactions</a>                                  
                                </li>
                                
                                <li class="nav-item">
                                <a href="img/CageMap.jpg" class="nav-link">Map<img src="img/CageMap.jpg" id="CageMap" alt="CageMap"  height="30"/></a>
                                </li>
                          
                            </ul>
                            
                            
                            
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link d-flex" href="LoggOff.php">
                                        <span>Logout, <?php if(isset($_SESSION['FullName'])== TRUE){echo $_SESSION['FullName'];}?></span>
                                    </a>
                                </li>
                                
                                          
                            </ul>
                        </div>
                                     
                    </nav>
                </div>
</div>
</head>


