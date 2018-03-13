<?php

/**
 * $Id: test.cropinterface.php 44 2006-06-26 10:05:41Z Andrew $
 * 
 * [Description]
 * 
 * Example file for class.cropinterface.php.
 *
 * [Author]
 * 
 * Andrew Collington <php@amnuts.com> <http://php.amnuts.com/>
 */

require('include/classe_usuari.php');
require('include/crop_canvas/class.cropinterface.php');
$ci =& new CropInterface(true);

if (isset($_GET['file'])) {
        $ci->loadImage($_GET['file']);
        $ci->cropToDimensions($_GET['sx'], $_GET['sy'], $_GET['ex'], $_GET['ey']);
        $ci->showImage('png', 100);
        exit;
}

?>

<html>

<body>

<?php

$ci->setCropAllowResize(true);
$ci->setCropTypeDefault(ccRESIZEANY);
$ci->setCropTypeAllowChange(true);
$ci->setCropSizeDefault('1:1');
$ci->setCropPositionDefault(ccCENTRE);
$ci->setCropMinSize(10, 10);
$ci->setExtraParameters(array('test' => '1', 'fake' => 'this_var'));
$ci->setCropSizeList(array('75:100' => 'Vertical', '1:1' => 'Normal', '100:75' => 'Apaisat'));
$ci->loadInterface('mypicture.jpg');

?>

</body>
</html>
