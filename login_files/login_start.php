<!DOCTYPE html>

<html>
  <head>    
    <title>login start - password_hash example</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/main.css">
  </head>   
  
  <body>      
    <main>
        
        <form action="login.php" method="post">
          <input type="radio" name="type" value="existing" id="existing">
          <label for="existing">I have an account.</label><br>
          <input type="radio" name="type" value="new" id="new" checked>
          <label for="new">I need to create an account</label><br>
          <input type="submit" value="Go"> 
        </form>
     
    </main>  
  </body>
</html>


