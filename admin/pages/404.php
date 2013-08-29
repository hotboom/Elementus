<?
$sapi_type = php_sapi_name();
if($sapi_type=="cgi") header("Status: 404");
else header("HTTP/1.1 404 Not Found");
?>
<html>
<head>
    <title>404 not found</title>
</head>
<body>
    <h1>Not found</h1>
</body>
</html>