<?php

    $pageTitle='All Items';
    include 'header.php';
    
    if(isset($_SESSION['isLogged']) == FALSE){
                    header('Location: index.php');
                    exit;
                    }
        
                
            if(isset($_GET["Item"]) && isset($_GET["Location"]) && isset($_GET["SubLocation"]) && isset($_GET["Units"])){
                    
                $Item = trim($_GET["Item"]);
                $Location = trim($_GET["Location"]);
                $SubLocation = trim($_GET["SubLocation"]);
                $Units = $_GET["Units"];
                    
                    //PreLoad all locations and sublocations
                        $AvailLocations = explode("\n", file_get_contents('data/locations/locations.txt'));
                        $AvailSubLocations = explode("\n", file_get_contents('data/locations/sublocations.txt'));
                  
                    
                     }
                     
                else {
                    header('Location: AllItems.php');
                    exit;
                }
            
            if($_POST){
                
                $NewLocation = trim($_POST['NewLocation']);
                $NewSubLocation = trim($_POST['NewSubLocation']);
                $NewUnits = trim($_POST['NewUnits']);
                $LocPath = 'data/items/'.$Item.'/locations.txt';
                        
                        //Security checkups
                        if ($NewUnits<=0){
                            //there was something wrong
                            header("Location: edit.php?Item=".$Item);
                            exit();
                        }
                        if ($Location==$NewLocation && $SubLocation==$NewSubLocation){
                            //there was nothing to move
                            header("Location: edit.php?Item=".$Item);
                            exit();
                        }
                
                    //Is it just one line
                    $LocationsLines = explode("\n", file_get_contents($LocPath));
                    $Lines = count($LocationsLines);
                    
                    if ($Lines==1){
                        //It is just one line
                        list($Loc, $SubLoc, $Units, $NetId) = explode("#",trim($LocationsLines[0]));
                        //See if the source data is matching the file
                        if ($Loc==$Location && $SubLoc==$SubLocation){
                            
                            //See if there is a leftover
                            if($Units!=$NewUnits){
                                //there is leftover
                                //Update the old line
                                $Count=$Units-$NewUnits;
                                $LocationsNewLines[0] = $Loc.'#'.$SubLoc.'#'.$Count.'#0';
                                //Add a new line
                                $LocationsNewLines[1] = $NewLocation.'#'.$NewSubLocation.'#'.$NewUnits.'#0';
                                
                            }
                            else {
                                //There is no leftover - remove the old line
                                //Add a new line
                                $LocationsNewLines[0] = $NewLocation.'#'.$NewSubLocation.'#'.$NewUnits.'#0';
                                
                            }
                            
                        }
                        else{
                            //there was something wrong
                            header("Location: edit.php?Item=".$Item);
                            exit();
                        }
                    }
                           else {
                                //there are multiple lines
                                $Triger1=0;
                                $j=0;                                
                                foreach ($LocationsLines as &$Lines) {

                                //Start a cycle looking for the source line
                                
                                    list($Loc, $SubLoc, $Units, $NetId) = explode("#",$Lines);
                                    
                                    if ($Loc==$Location && $SubLoc==$SubLocation ){
                                    //this is the matching line
                                    //some security checks
                                        if ($Units<$NewUnits){
                                            //there was something wrong
                                            header("Location: edit.php?Item=".$Item);
                                            exit();
                                        }
                                    //Is there a leftover
                                        if($Units==$NewUnits){
                                            //there is no leftover - doing nothing in order to remove the line
                                            
                                        }
                                       else {
                                           //there is some leftover - update the line
                                           $Count=$Units-$NewUnits;
                                           $LocationsTEMP[$j] = $Loc.'#'.$SubLoc.'#'.$Count.'#0';
                                       }
                                       
                                       $Triger1=1;
                                       
                               }
                               else{
                                   //This is not a matching line
                                   $LocationsTEMP[$j]=trim($Lines);
                               }
                             $j++;  
                             }//end of foreach1
                             
                             //Mare sure there was a matching source line
                             if ($Triger1==0){
                                 //there was not a single match
                                 //there was something wrong
                                 header("Location: edit.php?Item=".$Item);
                                 exit();
                             }
                        
                        //Start a cycle looking for the destination line
                        $Triger2=0;
                        $j=0;
                        foreach ($LocationsTEMP as &$Lines) {
                            list($Loc, $SubLoc, $Units, $NetId) = explode("#",$Lines);
                            if ($Loc==$NewLocation && $SubLoc==$NewSubLocation ){
                                //This is a matching line
                                $Count=$Units+$NewUnits;
                                $LocationsNewLines[$j] = $NewLocation.'#'.$NewSubLocation.'#'.$Count.'#0';
                                $Triger2=1;
                            }
                            else {
                                //There was not a match
                                $LocationsNewLines[$j] = trim($Lines);
                            }
                            $j++;
                               
                        }//End of feoeach2
                        
                        //Check if there was a matching line
                             if ($Triger2==0){
                                 //there was not a single match
                                 //Add an aditional destination
                                 $LocationsNewLines[$j] = $NewLocation.'#'.$NewSubLocation.'#'.$NewUnits.'#0';
                                 
                             }
                        
                    }
                    
                    
                    //Overwrite the locations.txt file
                        file_put_contents($LocPath, implode(PHP_EOL, $LocationsNewLines));
                                               
                        
                        //Create the transaction line
                        //date#NetID#ItemName#Units#From location#From Sublocation#To location#To Sublocation
                        $Date = date("m.d.Y");
                        $TransLine = $Date.'#'
                                .trim($_SESSION['NetID']).'#'
                                .$Item.'#'
                                .$NewUnits.'#'
                                .trim($AvailLocations[$Location]).'#'
                                .trim($AvailSubLocations[$SubLocation]).'#'
                                .trim($AvailLocations[$NewLocation]).'#'
                                .trim($AvailSubLocations[$NewSubLocation]).PHP_EOL;  
                            
                                    //Add a transaction record                   
                                    $TransLine .= file_get_contents("data/users/transactions.txt");
                                    file_put_contents("data/users/transactions.txt", $TransLine);
                        
                                    $message='Item added successfully';
                                    
                        //Redirect
                        header("Location: edit.php?Item=".$Item);
                        exit();
                    
                    
                    
                    
            }
            ?>

<body id="reportsPage" class="bg02">
    <div class="container">
        <div class="row tm-mt-big">
            <div class="col-xl-8 col-lg-10 col-md-12 col-sm-12">
                <div class="bg-white tm-block">
   
                    <div class="row">
                        <div class="col-12">
                            <h2 class="tm-block-title d-inline-block"><?php echo $Item;?></h2>
                        </div>
                    </div>
                    
                        <div class="row mt-4 tm-edit-product-row">
                            <div class="col-xl-10 col-lg-10 col-md-12">
                            
                            
                            <form action="" method="post" class="tm-edit-product-form" enctype="multipart/form-data">
                                

                                <div class="input-group mb-3">
                                    <label for="category" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Change Location</label>
                                    <select name="NewLocation" required class="custom-select col-xl-9 col-lg-8 col-md-8 col-sm-7" id="category">
                                    
                                        <option value="<?php echo $Location; ?>"><?php echo $AvailLocations[$Location]; ?></option>
                                        
                                        <?php
                                        //generates all locations options
                                        
                                        $i=0;
                                        foreach ($AvailLocations as &$Loc) { 
                                            
                                        if ($i!=$Location){
                                            echo '<option value="'.$i.'">'.$Loc.'</option>';
                                        }   
                                         $i++;
                                        }                                        
                                       ?>         
                                                
                                    </select>
                                </div>
                                
                                
                                <div class="input-group mb-3">
                                    <label for="category" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Change Sub-Location</label>
                                    <select name="NewSubLocation" required class="custom-select col-xl-9 col-lg-8 col-md-8 col-sm-7" id="category">
                                        
                                    <option value="<?php echo $SubLocation; ?>"><?php echo $AvailSubLocations[$SubLocation]; ?></option>
                                    
                                        <?php
                                        //generates all sub-locations options
                                        
                                        $i=0;
                                        foreach ($AvailSubLocations as &$SubLoc) {
                                            
                                            if ($i!=$SubLocation){
                                            echo '<option value="'.$i.'">'.$SubLoc.'</option>';
                                            
                                        }   
                                         $i++;
                                        
                                        }
                                        ?> 

                                    </select>
                                </div>
                                
                                <div class="input-group mb-3">
                                    <label for="stock" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Units to be moved
                                    </label>
                                    <input id="stock" required name="NewUnits" type="number" class="form-control validate col-xl-9 col-lg-8 col-md-7 col-sm-7" value="<?php echo $Units; ?>" min="1" max="<?php echo $Units; ?>" title="Format: 3 digits">
                                </div>
                                                            
                                <div class="input-group mb-3">
                                    <div class="ml-auto col-xl-8 col-lg-8 col-md-8 col-sm-7 pl-0">
                                        <button type="submit" class="btn btn-primary">Move
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
        
