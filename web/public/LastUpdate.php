<?php

$file = "index.php";
$font = "Verdana";
$size = "2";
$color = "#000000";
$alignment = "center";
$bold = "yes"; // yes or no

$date = date("d/m/Y", filectime($file));

if($bold == "yes") {
     echo "<$alignment><font face='$font' size='$size' color='$color'><b>Last update on $date.</b></font></$alignment>";
} else {
     echo "<$alignment><font face='$font' size='$size' color='$color'>Last update on $date.</font></$alignment>";
}
?>
