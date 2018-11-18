<?php

    $pageTitle='Edit an item';
    include 'header.php';
    
    if(isset($_SESSION['isLogged']) == FALSE){
                    header('Location: index.php');
                    exit;
                    }
        
?>

<body id="reportsPage" class="bg02">
    

            <?php

                if(isset($_GET["Item"])){
                    
                    $Item = trim($_GET["Item"]);
                     
                    if (file_exists ('data/items/'.$Item)){
                        //PreLoad all locations and sublocations
                        $AvailLocations = explode("\n", file_get_contents('data/locations/locations.txt'));
                        $AvailSubLocations = explode("\n", file_get_contents('data/locations/sublocations.txt'));
                }
                else {
                    header('Location: AllItems.php');
                    exit;
                }
                    }

            
                    
                    
                    
            ?>

<div class="container">
        <div class="row tm-mt-big">
            <div class="col-xl-10 col-lg-10 col-md-12 col-sm-12">
                <div class="bg-white tm-block">

                        <?php
                        if (isset($message)){
                        echo '<label>'.$message.'</label>';
                        }
                        ?>
                    
                    <div class="row">
                        <div class="col-12">
                            <h2 class="tm-block-title d-inline-block"><?php echo $Item;?></h2>
                        </div>
                    </div>
                    
                    <table id="myTable" class="table table-light table-striped table-bordered  bg-light" cellspacing="0" width="100%"> 
					 <thead>
                                             <tr class="header">
                                               
                                                        <th>Location</th>
							<th>Sub-Location</th>
                                                        <th>Units Available</th>
                                                        <th>Options</th>
                                             </tr>
                                         </thead>
                                         <tbody><p>
                                                
                                            <?php    
                                            //Generating the HTML
                                            
                                                $LocLines = explode("\n", file_get_contents('data/items/'.$Item.'/locations.txt'));
                                                foreach ($LocLines as &$Lines) {
                                                    
                                                    list($Loc,$SubLoc,$Units,$NetID) = explode("#",$Lines);
                                                    if ($Units==0){
                                                        
                                                        echo 
                                                        '<tr><td>'.$AvailLocations[$Loc].'</td>'
                                                        .'<td>'.$AvailSubLocations[$SubLoc].'</td>'
                                                        .'<td>'.$Units.'</td><td>'
                                                        . '<a href="checkin.php?Item='.$Item.'&Location='.$Loc.'&SubLocation='.$SubLoc.'&Units='.$Units.'">'.'Check In'.'</a></td></tr>';

                                                        }
                                                        
                                                    
                                                    else {
                                                   
                                                    echo 
                                                        '<tr><td>'.$AvailLocations[$Loc].'</td>'
                                                        .'<td>'.$AvailSubLocations[$SubLoc].'</td>'
                                                        .'<td>'.$Units.'</td>'
                                                            . '<td><a href="move.php?Item='.$Item.'&Location='.$Loc.'&SubLocation='.$SubLoc.'&Units='.$Units.'">'.'Move'.'</a> or '
                                                            . '<a href="checkout.php?Item='.$Item.'&Location='.$Loc.'&SubLocation='.$SubLoc.'&Units='.$Units.'">'.'Check Out'.'</a> or '
                                                            . '<a href="checkin.php?Item='.$Item.'&Location='.$Loc.'&SubLocation='.$SubLoc.'&Units='.$Units.'">'.'Check In'.'</a></td>'
                                                        . '</tr>';

                                                        }
                                                }
                                                
                                            ?>    
                                                    </p>
                                            </tbody>
                    </table>
                </div>
            </div>
        </div>
</div>
</body>
        <?php
        include 'footer.html';
        ?>
