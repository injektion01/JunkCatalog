<?php

    $pageTitle='Transactions';
    include 'header.php';

        if(isset($_SESSION['isLogged']) == FALSE){
                    header('Location: index.php');
                    exit;
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
                                               
                                                        <th onclick="sortTable('table', 0)">Date↕</th>
							<th onclick="sortTable('table', 1)">NetID↕</th>
							<th onclick="sortTable('table', 2)">Item Name↕</th>
                                                        <th onclick="sortTable('table', 3)">Units↕</th>
							<th onclick="sortTable('table', 4)">From Location↕</th>
							<th onclick="sortTable('table', 5)">From Sub-Location↕</th>
							<th onclick="sortTable('table', 6)">To Location↕</th>
                                                        <th onclick="sortTable('table', 7)">To Sub-Location↕</th> 
                                                                                                        
                                                </tr>
					</thead>
                                            <tbody><p>

            <?php
            //Get the data from the transactions file
            $Alltransactions = explode("\n", file_get_contents('data/users/transactions.txt'));
            
            //Manipulate the  data
            
            foreach ($Alltransactions as &$TransLines) {
            
                list($Date,$INetID,$ItemName,$Units,$FromLocation,$FromSubLocation,$ToLocation,$ToSubLocation) = explode("#",$TransLines);

            // Generate the html code
                                        echo  '<tr>'
                                                . '<td>'.$Date.'</td>'
                                                . '<td>'.$INetID.'</td>'
                                                . '<td><a href="edit.php?Item='.trim($ItemName).'">'.$ItemName.'</a></td>'
                                                . '<td>'.$Units.'</td>'
                                                . '<td>'.$FromLocation.'</td>'
                                                . '<td>'.$FromSubLocation.'</td>'
                                                . '<td>'.$ToLocation.'</td>'
                                                . '<td>'.$ToSubLocation.'</td>'
                                                .'</tr>';              
            
            }
            //Do not make changes below
            ?>

                                        
                                        </p></tbody>
				</table>
    
            </div>   
        </div>
    </div>
</body>

        <?php
        include 'footer.html';
        ?>
            
            

