<?php

    $pageTitle='Add New Item';
    include 'header.php';
    

        if(isset($_SESSION['isLogged']) == FALSE){
                    header('Location: index.php');
                    exit;
                    } 
                    //Was the form submitted?
                    if($_POST){
                        
                        $FolderName='data/items/'.$_POST['name'];
                        
                        //Checkes if the Item/folder exists and creates it if not
                        if(!is_dir($FolderName)){
                        mkdir($FolderName, 0777, true);
                        
                        //Check if there is an image file uploaded
                         if(count($_FILES)>0){
                            
                        // Saving the image file into the Item directory
                        $Destination=$FolderName.'/icon.jpg';
                        if(move_uploaded_file($_FILES["fname"]["tmp_name"], $Destination)) {

                            $message='<p>The file was uploaded successfully</p>';
                            
                        } 
                        else {
                            
                            rmdir($FolderName);
                            $message='<p>There was a problem uploading the file</p>';

                            } 
                        }
                        
                            //create the locations.txt file
                        
                            //Get the locations options from locations.txt and sublocations.txt
                            $ItemLocLines = explode("\n", file_get_contents('data/locations/locations.txt'));
                            $ItemSubLocLines = explode("\n", file_get_contents('data/locations/sublocations.txt'));
                               
                                //Find out the maching location numbers for the file
                            
                                $TrimmedLoc = trim($_POST['Location']);
                                $TrimmedSubLoc = trim($_POST['SubLocation']);
                                $i=0;
                                
                                foreach ($ItemLocLines as &$LocTempNumber) {

                                   $TrimmedLocTempNumber = trim($LocTempNumber);

                                   if ($TrimmedLocTempNumber==$TrimmedLoc){
                                       $LocNumber=$i;
                                       break;
                                   }
                                   $i++;
                                }
                                
                                $i=0;
                                foreach ($ItemSubLocLines as &$SubLocTempNumber) {
                                    
                                    $TrimmedSubLocTempNumber = trim($SubLocTempNumber);
                                    
                                    if ($TrimmedSubLoc==$TrimmedSubLocTempNumber){
                                       $SubLocNumber=$i;
                                       break;
                                   }
                                   $i++;
                                }
                                //Genarate the locations.txt content
                                $CheckedOut='#0';
                                if ($LocNumber==0){
                                    $CheckedOut='#'.trim($_SESSION['NetID']);
                                }
                                $Text = $LocNumber.'#'.$SubLocNumber.'#'.$_POST['Units'].$CheckedOut;
                                
                                //Create the locations.txt
                                
                                $LocDestination=$FolderName.'/locations.txt';
                                if ($Locfile=fopen("$LocDestination", "w")){
                                    
                                    fwrite($Locfile, $Text);
                                    fclose($Locfile);
                                    
                                }
                                else { 
                            
                                       rmdir($FolderName);
                                       $message='<p>There was a problem creating a location file</p>';
                                }
                                //Create an index.php file for security reasons
                                $IndexLoc=$FolderName.'/index.php';
                                if ($LocIndFile=fopen("$IndexLoc", "w")){
                                    
                                    $Text=PHP_EOL;
                                    fwrite($LocIndFile, $Text);
                                    fclose($LocIndFile);
                                    
                                }
                                
                        $message='Item added successfully';
                        
                        
                        
                        //Create the transaction line
                        //date#NetID#ItemName#Units#From location#From Sublocation#To location#To Sublocation
                        
                        $Date = date("m.d.Y");
    
                        $TransLine = $Date.'#'.trim($_SESSION['NetID']).'#'.$_POST['name'].'#'.$_POST['Units'].'#New Item#New Item#'.$TrimmedLoc.'#'.$TrimmedSubLoc.PHP_EOL;  
                            
                                    //Add a transaction record                   
                                    $TransLine .= file_get_contents("data/users/transactions.txt");
                                    file_put_contents("data/users/transactions.txt", $TransLine);

                        }
                        
                        else {
                        
                        $message='<p>There is already an item called '.$_POST['name'].'</p>';
                        
                        }
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
                            <h2 class="tm-block-title d-inline-block">Add New Item</h2>
                        </div>
                    </div>
                    <div class="row mt-4 tm-edit-product-row">
                        <div class="col-xl-9 col-lg-10 col-md-12">
                            <form action="" method="post" class="tm-edit-product-form" enctype="multipart/form-data">
                                <div class="input-group mb-3">
                                    <label for="name" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Item
                                        Name
                                    </label>
                                    <input id="name" required name="name" type="text" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7">
                                </div>
                                
                                <div class="input-group mb-3">
                                    <label for="category" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Main Location</label>
                                    <select name="Location" required class="custom-select col-xl-9 col-lg-8 col-md-8 col-sm-7" id="category">
                                    <option value=""></option>    
                                        <?php
                                        //generates all locations options
                                        
                                        $AvailLocations = explode("\n", file_get_contents('data/locations/locations.txt'));
                                        foreach ($AvailLocations as &$Location) {   
                                        echo '<option value="'.$Location.'">'.$Location.'</option>';  
                                        }
                                       ?>         
                                                
                                    </select>
                                </div>
                                <div class="input-group mb-3">
                                    <label for="category" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Sub-Location</label>
                                    <select name="SubLocation" required class="custom-select col-xl-9 col-lg-8 col-md-8 col-sm-7" id="category">
                                    <option value=""></option>
                                        <?php
                                        //generates all sub-locations options
                                        
                                        $AvailSubLocations = explode("\n", file_get_contents('data/locations/sublocations.txt'));
                                        foreach ($AvailSubLocations as &$SubLocation) {   
                                        echo '<option value="'.$SubLocation.'">'.$SubLocation.'</option>'; 
                                        }
                                        ?> 

                                    </select>
                                </div>
                                <div class="input-group mb-3">
                                    <label for="stock" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Units In
                                        Stock
                                    </label>
                                    <input id="stock" required name="Units" type="number" class="form-control validate col-xl-9 col-lg-8 col-md-7 col-sm-7" min="0" max="999" title="Format: 3 digits">
                                </div>
                                
                                <div class="input-group mb-3">
                                    <label for="category" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Upload an image (only .jpg and .jpeg)</label> 
                                        <div class="custom-file mt-3 mb-3">                                                                         
                                           <input type="file" name="fname" class="inputfile" required/>                                                                   
                                         </div>
                                </div>
                                
                                <div class="input-group mb-3">
                                    <div class="ml-auto col-xl-8 col-lg-8 col-md-8 col-sm-7 pl-0">
                                        <button type="submit" class="btn btn-primary">Add
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
            
            

