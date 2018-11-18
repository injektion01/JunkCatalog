<?php

    $pageTitle='Check Out';
    include 'header.php';

        if(isset($_SESSION['isLogged']) == FALSE){
                    header('Location: index.php');
                    exit;
                    } else {
                    }
        
?>
<br><br>
<body id="reportsPage" class="bg02">
    <div class="" id="home">
                        
        <div class="container">
        
             <div class="row">
                 
                 <input type="text" id="myInput" onkeyup="mySearch()" placeholder="Search" title="Type in something">
                 
				<table id="myTable" class="table table-light table-striped table-bordered  bg-light" cellspacing="0" width="100%"> 
					
                                        <thead>
                                                
						<tr onMouseOver="this.style.cursor='pointer'" class="header">
                                               
                                                        <th onclick="sortTable('table', 0)">Item Name↕</th>
							                                                                                                
                                                </tr>
					</thead>
					<tbody><p>
                                        <?php
    
                                        //Do not make changes above
                                        
                                        //Reading the item names
                                        $ItemNamesArray = array_slice(scandir('data/items/'), 2);
                                        
                                            //PreLoad all locations and sublocations
                                            $AvailLocations = explode("\n", file_get_contents('data/locations/locations.txt'));
                                            $AvailSubLocations = explode("\n", file_get_contents('data/locations/sublocations.txt'));
                                         
                                        
                                        //Generating the items table
                                        foreach ($ItemNamesArray as &$ItemName) {
                                            if (trim($ItemName)!='index.php'){
                                                
                                            
                                            
                                            //read the locations files
                                            $ItemLocations='data/items/'.$ItemName.'/locations.txt';
                                            $ItemLocLines = explode("\n", file_get_contents($ItemLocations));
                                               
                                            
                                            $ItemLocation='';
                                            $ItemSubLocation='';
                                            $UnitsInStock='';
                                            $CheckedOut='';
                                            $i=0;        
                                                    
                                            foreach ($ItemLocLines as &$ItemLines) {
                                            list($Loc,$SubLoc,$Units,$NetId) = explode("#",$ItemLines);
                                            
                                          
                                                $ItemLocationTemp=$AvailLocations[$Loc];
                                                $ItemSubLocationTemp=$AvailSubLocations[$SubLoc];
                                                $UnitsInStockTemp=$Units;
                                                $CheckedOutTemp='Available';
                                                
                                                
                                                if ($Units==0) {
                                                    $CheckedOutTemp=$NetId;
                                                }
                                                
                                                if ($Units<0) {
                                                    $CheckedOutTemp='Unavailable';
                                                }
                                                if ($i==0){
                                                    $i++;
                                                    
                                                    $ItemLocation=$ItemLocation.$ItemLocationTemp;
                                                    $ItemSubLocation=$ItemSubLocation.$ItemSubLocationTemp;    
                                                    $UnitsInStock=$UnitsInStock.$UnitsInStockTemp;
                                                    $CheckedOut=$CheckedOut.$CheckedOutTemp;
                                                    
                                                }
                                                else {
                                                $ItemLocation=$ItemLocation.'<br>'.$ItemLocationTemp;
                                                $ItemSubLocation=$ItemSubLocation.'<br>'.$ItemSubLocationTemp;    
                                                $UnitsInStock=$UnitsInStock.'<br>'.$UnitsInStockTemp;
                                                $CheckedOut=$CheckedOut.'<br>'.$CheckedOutTemp;
                                                        
                                            //echo var_dump($CheckedOut);
                                            //exit;
                                           
                                                }           
                                            
                                        }

                                        // Generate the html
                                        echo  '<tr>'
                                                . '<td><a href="edit.php?Item='.trim($ItemName).'">'.$ItemName.'</a></td>'
                                                .'</tr>';              
                                        
                                        }
                                        }

                                        ?>

				</p></table>
			</div>
               </div>
        </div>
    </body>
           
        <?php
        include 'footer.html';
      
            

