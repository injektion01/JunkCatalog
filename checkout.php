<?php

    $pageTitle='Check Out';
    include 'header.php';
    
    if(isset($_SESSION['isLogged']) == FALSE){
                    header('Location: index.php');
                    exit;
                    }

            if(isset($_GET["Item"]) && isset($_GET["Location"]) && isset($_GET["SubLocation"]) && isset($_GET["Units"])){
                    
                    $Item = trim($_GET["Item"]);
                    $Location = trim($_GET["Location"]);
                    $SubLocation = trim($_GET["SubLocation"]);
                    $Units = trim($_GET["Units"]);
                     
                        //PreLoad all locations and sublocations
                        $AvailLocations = explode("\n", file_get_contents('data/locations/locations.txt'));
                        $AvailSubLocations = explode("\n", file_get_contents('data/locations/sublocations.txt'));
            }
            
            if($_POST){
                
                $UnitsUpdate = $_POST['Units'];
                $LocPath = 'data/items/'.$Item.'/locations.txt';
                
                
                if($UnitsUpdate<=0){
                            //No Units were submited - nothing to check out
                            header("Location: edit.php?Item=".$Item);
                            exit();
                        }
                        //Is it just one line
                        $LocationsLines = explode("\n", file_get_contents($LocPath));
                        $Lines = count($LocationsLines);
                        
                        if ($Lines==1){
                           //It is just one line
                           list($Loc, $SubLoc, $Units, $NetId) = explode("#",trim($LocationsLines[0]));
                           if ($Loc==$Location && $SubLoc==$SubLocation ){
                               //the line matches - editing
                               if ($UnitsUpdate<=$Units){
                                   if ($UnitsUpdate<$Units){
                                       //There is leftover
                                       $Count=$Units-$UnitsUpdate;
                                       $LocationsNewLines[0] = $Location.'#'.$SubLocation.'#'.$Count.'#0';
                                   }
                                   else{
                                       //there was no leftover - must check out the line
                                       $Count=0;
                                       $LocationsNewLines[0] = $Location.'#'.$SubLocation.'#'.$Count.'#'.trim($_SESSION['NetID']);
                                   }                       
                               }
                               else {
                                    //There was something wrong
                                header("Location: edit.php?Item=".$Item);
                                exit();                                    
                               }
                           }
                           else {
                                //There was something wrong
                                header("Location: edit.php?Item=".$Item);
                                exit(); 
                           }
                            
                            
                            
                            }
                        else {
                            //There are multiple lines
                            $j=0;
                            foreach ($LocationsLines as &$Lines) {
                            
                            list($Loc, $SubLoc, $Units, $NetId) = explode("#",$Lines);                         
                            if ($Loc==$Location && $SubLoc==$SubLocation ){
                                //this is the matching line
                                if ($UnitsUpdate>$Units){
                                    //there was something wrong
                                    header("Location: edit.php?Item=".$Item);
                                    exit(); 
                                }
                                if($UnitsUpdate==$Units){
                                    //there is no leftover - must remove the line - do nothing
                                    
                                }
                                else {
                                    //there is leftover - must edit the line
                                    $Count=$Units-$UnitsUpdate;
                                    $LocationsNewLines[$j] = $Location.'#'.$SubLocation.'#'.$Count.'#0';
                                    $j++;
                                }
                                
                            }
                            else {
                                //this is not a match
                                $LocationsNewLines[$j]=trim($Lines);
                                $j++;
                            }
                            
                            
                            }//end of foreach
                            
                        }
                        
                        
                        //Overwrite the locations.txt file
                        file_put_contents($LocPath, implode(PHP_EOL, $LocationsNewLines));
                                               
                        
                        //Create the transaction line
                        //date#NetID#ItemName#Units#From location#From Sublocation#To location#To Sublocation
                        $Date = date("m.d.Y");
                        $TransLine = $Date.'#'.trim($_SESSION['NetID']).'#'.$Item.'#'.$UnitsUpdate.'#'.trim($AvailLocations[$Location]).'#'.trim($AvailSubLocations[$SubLocation]).'#Checked Out#Checked Out'.PHP_EOL;  
                            
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

                        <?php
                        if (isset($message)){
                        echo '<label>'.$message.'</label>';
                        }
                        ?>
                    
                    <div class="row">
                        <div class="col-12">
                            <h2 class="tm-block-title d-inline-block"><?php echo $Item.'<br>'.$AvailLocations[$Location].'- '.$AvailSubLocations[$SubLocation]; ?></h2>
                        </div>
                    </div>
                    <div class="row mt-4 tm-edit-product-row">
                        <div class="col-xl-9 col-lg-10 col-md-12">
                            
                            <form action="" method="post" class="tm-edit-product-form" enctype="multipart/form-data">
                                                        
                                <div class="input-group mb-3">
                                    <label for="stock" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Units to Check Out
                                    </label>
                                    <input id="stock" required name="Units" type="number" class="form-control validate col-xl-9 col-lg-8 col-md-7 col-sm-7" value="<?php echo $Units; ?>" min="1" max="<?php echo $Units; ?>" title="Format: 3 digits">
                                </div>
                                
                                
                                <div class="input-group mb-3">
                                    <div class="ml-auto col-xl-8 col-lg-8 col-md-8 col-sm-7 pl-0">
                                        <button type="submit" class="btn btn-primary">Check Out
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
