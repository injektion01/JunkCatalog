<?php

    $pageTitle='Check in an item';
    include 'header.php';
    
     if(isset($_SESSION['isLogged']) == FALSE){
                    header('Location: index.php');
                    exit;
                    }
                    //Read all available locations and SubLocations
                    $AvailLocations = explode("\n", file_get_contents('data/locations/locations.txt'));
                    $AvailSubLocations = explode("\n", file_get_contents('data/locations/sublocations.txt'));
                    
                    //Was the form submited
                    if($_POST){
                        $ItemName = trim($_POST['Name']);
                        $ItemUnits = trim($_POST['Units']);
                        
                        if($ItemUnits==0){
                            //No Units were submited - nothing to check in
                            header("Location: edit.php?Item=".$ItemName);
                            exit();
                        }
                        $Trigger=0;
                        $ItemLocation = trim($_POST['Location']);
                        $ItemSubLocation = trim($_POST['SubLocation']);
                        $LocPath = 'data/items/'.$ItemName.'/locations.txt';
                        
                        //Is it just one line
                        $LocationsLines = explode("\n", file_get_contents($LocPath));
                        $Lines = count($LocationsLines);
                        
                        if ($Lines==1){
                           //It is just one line
                            
                           //Is the line matching 
                           list($Loc, $SubLoc, $Units, $NetId) = explode("#",trim($LocationsLines[0]));
                           if ($Loc==$ItemLocation && $SubLoc==$ItemSubLocation ){
                               //the line matches - editing
                               $Count=$Units+$ItemUnits;
                               $LocationsNewLines[0] = $ItemLocation.'#'.$ItemSubLocation.'#'.$Count.'#0'; 
                           }
                           else {
                               //The Line is not matching
                               //See if the Item was checked out
                               if ($Units==0){
                                   //The Item was checked out
                                   $LocationsNewLines[0] = $ItemLocation.'#'.$ItemSubLocation.'#'.$ItemUnits.'#0';
                               }
                               else{
                                   //The Item was not checked out
                                   //Keep the old line
                                   $LocationsNewLines[0] = $LocationsLines[0];
                                   //Add a new line
                                   $LocationsNewLines[1] = $ItemLocation.'#'.$ItemSubLocation.'#'.$ItemUnits.'#0';
                                }
                            }   
                        }
                        
                        else {
                            //It contains multiple lines
                            //Find a matching line 
                            $j=0;
                        foreach ($LocationsLines as &$Lines) {
                            
                            list($Loc, $SubLoc, $Units, $NetId) = explode("#",$Lines);                         
                            if ($Loc==$ItemLocation && $SubLoc==$ItemSubLocation ){
                                $Count=$Units+$ItemUnits;
                                $LocationsNewLines[$j] = $ItemLocation.'#'.$ItemSubLocation.'#'.$Count.'#0';
                                $Trigger=1;
                            }
                           else {
                                $LocationsNewLines[$j] = $Lines;
                           }
                           $j++;
                        }//end of foreach
                        
                        if ($Trigger!=1){
                            //there was no matching line - add an aditional
                            $LocationsNewLines[$j] = $ItemLocation.'#'.$ItemSubLocation.'#'.$ItemUnits.'#0';
                        }
                        
      
                        }
                        
                        //Overwrite the locations.txt file
                        file_put_contents($LocPath, implode(PHP_EOL, $LocationsNewLines));
                                               
                        
                        //Create the transaction line
                        //date#NetID#ItemName#Units#From location#From Sublocation#To location#To Sublocation
                        $Date = date("m.d.Y");
                        $TransLine = $Date.'#'.trim($_SESSION['NetID']).'#'.$ItemName.'#'.$ItemUnits.'#Checked in#Checked in#'.trim($AvailLocations[$ItemLocation]).'#'.trim($AvailSubLocations[$ItemSubLocation]).PHP_EOL;  
                            
                                    //Add a transaction record                   
                                    $TransLine .= file_get_contents("data/users/transactions.txt");
                                    file_put_contents("data/users/transactions.txt", $TransLine);
                        
                                    $message='Item added successfully';
                                    
                        //Redirect
                        header("Location: edit.php?Item=".$ItemName);
                        exit();
             
                    }

?>

<body id="reportsPage" class="bg02">
    <div class="container">
        <div class="row tm-mt-big">
            <div class="col-xl-8 col-lg-10 col-md-12 col-sm-12">
                <div class="bg-white tm-block">

                        <?php
                        if (isset($message)){
                        echo '<label>'.$message.'</label>';
                        }
                        ?>
                    
                    <div class="row">
                        <div class="col-12">
                            <h2 class="tm-block-title d-inline-block">Check In An Item</h2>
                        </div>
                    </div>
                    
                        <div class="row mt-4 tm-edit-product-row">
                            <div class="col-xl-10 col-lg-10 col-md-12">
                            
                            
                            <form action="" method="post" class="tm-edit-product-form" enctype="multipart/form-data">
                                
                                
                                <div class="input-group mb-3">
                                    <label for="category" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Item Name</label>
                                    <select name="Name" required class="custom-select col-xl-9 col-lg-8 col-md-8 col-sm-7" id="category">
                                      
                                        <?php
                                        //generates the names available
                                        if (isset ($_GET["Item"])) {
                                            $Item = trim($_GET["Item"]);
                                            echo '<option value="'.$Item.'">'.$Item.'</option>'; 
                                        }
                                        else {
                                            echo '<option value=""></option>'; 
                                        }

                                        $ItemNamesArray = array_slice(scandir('data/items/'), 2);
                                        foreach ($ItemNamesArray as &$Names) { 
                                            
                                            if($Item!=$Names){
                                                echo '<option value="'.$Names.'">'.$Names.'</option>';
                                            }
                                          
                                        }
                                       ?>         
                                                
                                    </select>
                                </div>
                                
                                
                                <div class="input-group mb-3">
                                    <label for="category" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">To Location</label>
                                    <select name="Location" required class="custom-select col-xl-9 col-lg-8 col-md-8 col-sm-7" id="category">
                                     
                                        <?php
                                        //generates all locations options
                                        if (isset ($_GET["Location"])) {
                                            $Location = trim($_GET["Location"]);
                                            echo '<option value="'.$Location.'">'.$AvailLocations[$Location].'</option>'; 
                                        }
                                        else {
                                            echo '<option value=""></option>'; 
                                        }
                                        $i=0;
                                        foreach ($AvailLocations as &$Loc) { 
                                            
                                            if ($Loc!=$AvailLocations[$Location]){
                                                echo '<option value="'.$i.'">'.$Loc.'</option>';
                                                
                                        }
                                        $i++;
                                        }
                                       ?>         
                                                
                                    </select>
                                </div>
                                
                                
                                <div class="input-group mb-3">
                                    <label for="category" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">To Sub-Location</label>
                                    <select name="SubLocation" required class="custom-select col-xl-9 col-lg-8 col-md-8 col-sm-7" id="category">
                                    
                                        <?php
                                        //generates all sub-locations options
                                        if (isset ($_GET["SubLocation"])) {
                                            $SubLocation = trim($_GET["SubLocation"]);
                                            echo '<option value="'.$SubLocation.'">'.$AvailSubLocations[$SubLocation].'</option>'; 
                                        }
                                        else {
                                            echo '<option value=""></option>'; 
                                        }
                                        $i=0;
                                        foreach ($AvailSubLocations as &$SubLoc) {
                                            if ($SubLoc!=$AvailSubLocations[$SubLocation]) {
                                                echo '<option value="'.$i.'">'.$SubLoc.'</option>';
                                            }
                                         $i++;
                                        }
                                        ?> 

                                    </select>
                                </div>
                                
                                
                                <div class="input-group mb-3">
                                    <label for="stock" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Units
                                    </label>
                                    <input id="stock" required name="Units" type="number" class="form-control validate col-xl-9 col-lg-8 col-md-7 col-sm-7" min="1" max="999" title="Format: 3 digits">
                                </div>
                                
                                                                
                                <div class="input-group mb-3">
                                    <div class="ml-auto col-xl-8 col-lg-8 col-md-8 col-sm-7 pl-0">
                                        <button type="submit" class="btn btn-primary">CheckIn
                                        </button>
                                    </div>
                                </div>
                            

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

        <?php
        include 'footer.html';
        ?>