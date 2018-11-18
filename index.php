<?php
$pageTitle='Loggin screen';
include 'LoginHeader.php';


$LoginMessage=' ';
if(isset($_SESSION['isLogged'])== TRUE){
    
    header('Location: AllItems.php');
        exit;
    
} else {

if($_POST){
    $username=trim($_POST['username']);
    $password=trim($_POST['password']);
    
    $UserFile='data/users/'.$username.'.txt';
    $FileHash=1;
    $PassHash=2;
    
    //$hash = password_hash($password, PASSWORD_DEFAULT); Just in case you have to generate more hashes
    
    //checks if a username and password are correct
    if(file_exists($UserFile)) {
    
            $handle = fopen($UserFile, 'r');
            $FileHash = fread($handle,filesize($UserFile));
            fclose($handle);
        
    }
        
    if(password_verify($password, $FileHash)){
        //correct username and password below
       
        $_SESSION['isLogged']=true;
                
   
        
            // lookes up the full name of the user and the name of the transactions file
            $AccLines = explode("\n", file_get_contents('data/users/accounts.txt'));
                foreach ($AccLines as &$Lines) {
                
                    
                list($NetId,$FullName) = explode("#",$Lines);
                
                    if ($username==$NetId){
                    
                        $_SESSION['FullName']=$FullName;
                        $_SESSION['NetID']=$NetId;
                        
                        //Save the login information
                        date_default_timezone_set('America/New_York');
                        $Date = date("Y-m-d H:i:s");
                        $LogLine = $Date.'#'.trim($_SESSION['NetID']).PHP_EOL;  
                        //Add a login record                   
                        $LogLine .= file_get_contents('data/users/logins.txt');
                        file_put_contents('data/users/logins.txt', $LogLine);
                        
                        break;   
                    }
                }
    
        header('Location: AllItems.php');
        exit;
    }
    else {
        $LoginMessage='Wrong username and/or password!';
    }                                        
}

}
?>
        

  <body class="bg03">
    <div class="container">
        <div class="row tm-mt-big">
            <div class="col-12 mx-auto tm-login-col">
                <div class="bg-white tm-block">
                    <div class="row">
                        <div class="col-12 text-center">
                            <i class="fas fa-3x fa-tachometer-alt tm-site-icon text-center"></i>
                            <h2 class="tm-block-title mt-3">Login to the junk catalog</h2>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <form method="post" class="tm-login-form">
                                <div class="input-group mt-3">
                                    <p><em><?php if($LoginMessage!=' ') {echo $LoginMessage;}?></em></p>
                                </div>
                                <div class="input-group">
                                    <label for="username" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Username</label>
                                    <input name="username" type="text" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7" id="username" required>
                                </div>
                                <div class="input-group mt-3">
                                    <label for="password" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Password</label>
                                    <input name="password" type="password" class="form-control validate" id="password" required>
                                </div>
                                <div class="input-group mt-3">
                                    <button type="submit" class="btn btn-primary d-inline-block mx-auto">Login</button>
                                </div>
                                <div class="input-group mt-3">
                                    <p><em>If you need help login in please contact <a href="mailto:cvetan.terziyski@yale.edu?Subject=Junk%20room%20catalog" target="_top">Cvetan</a> </em></p>
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