<?php

$treat = false;
if (isset($_POST['src'])) {
  $script = $_POST['src'];
  if (get_magic_quotes_gpc())
    $script = stripslashes($script);
  $encoding = (int)$_POST['ascii_encoding'];
  $fast_decode = isset($_POST['fast_decode']) && $_POST['fast_decode'];
  $special_char = isset($_POST['special_char'])&& $_POST['special_char'];
  
  require 'class.JavaScriptPacker.php';
  $t1 = microtime(true);
  $packer = new JavaScriptPacker($script, $encoding, $fast_decode, $special_char);
  $packed = $packer->pack();
  $t2 = microtime(true);
  
  $originalLength = strlen($script);
  $packedLength = strlen($packed);
  $ratio =  number_format($packedLength / $originalLength, 3);
  $time = sprintf('%.4f', ($t2 - $t1) );
  
  $treat = true;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
          "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>JavaScript Packer in PHP</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	 
	<link rel="shortcut icon" href="http://getbootstrap.com/assets/ico/favicon.ico">

   
    <!-- Bootstrap core CSS -->
    <link href="http://getbootstrap.com/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="http://getbootstrap.com/examples/jumbotron-narrow/jumbotron-narrow.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->   
  
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript">window.jQuery || document.write("<script type='text/javascript' src='js/jquery-1.10.2.min.js'><\/script>")</script>

<script type="text/javascript">
function decode() {
  var packed = document.getElementById('packed');
  eval("packed.value=String" + packed.value.slice(4));
}
</script>
</head>
<body>
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">JS Packer</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>
    <br>
    <div class="container">
  <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" class="form-horizontal" role="form">
  
  <div class="form-group">
      <h3>Script to pack:</h3>
      <textarea name="src" id="src" rows="10" cols="80"  class="form-control"><?php if($treat) echo htmlspecialchars($script);?></textarea>
  </div>
    <div class="row">
  <div class="col-xs-6 col-sm-3">
  <label for="ascii-encoding">Encoding:</label>
      <select name="ascii_encoding" id="ascii-encoding" class="form-control col-3">
        <option value="0"<?php if ($treat && $encoding == 0) echo ' selected'?>>None</option>
        <option value="10"<?php if ($treat && $encoding == 10) echo ' selected'?>>Numeric</option>
        <option value="62"<?php if (!$treat) echo 'selected';if ($treat && $encoding == 62) echo ' selected';?>>Normal</option>
        <option value="95"<?php if ($treat && $encoding == 95) echo ' selected'?>>High ASCII</option>
      </select>
</div>
  <div class="col-xs-6 col-sm-3">

  <!-- Add the extra clearfix for only the required viewport -->
  
  <label>
        Fast Decode:
        <input type="checkbox" name="fast_decode" id="fast-decode"<?php if (!$treat || $fast_decode) echo ' checked'?>>
      </label>
</div>
  <div class="col-xs-6 col-sm-3">
  <label>
        Special Characters:
        <input type="checkbox" name="special_char" id="special-char"<?php if ($treat && $special_char) echo ' checked'?>>
      </label></div>
  <div class="col-xs-6 col-sm-3">
  	<input type="submit" value="Pack" class="btn btn-primary">
  </div>
  </div>

  </form>
  
  
  <?php if ($treat) {?>
  <div id="result" class="form-group">
  <form class="form-horizontal" role="form"> 
    <h3>Packed result:</h3>
    <textarea id="packed" class="result form-control" rows="10" cols="80"><?php echo htmlspecialchars($packed);?></textarea>
    <p>
      compression ratio:
      <?php echo $originalLength, '/', $packedLength, ' = ',$ratio; ?>
      <br>performed in <?php echo $time; ?> s.
    </p>
    <p><button type="button" onclick="decode()" class="btn btn-primary">decode</button></p>
  </div>
  <?php };//end if($treat)?>
  </form>
    </div><!--/container-->
</body>
</html>
