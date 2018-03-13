<?php

/**
 * $Id: class.cropinterface.php 44 2006-06-26 10:05:41Z Andrew $
 *
 * [Description]
 *
 * This class allows you to use all the power of the crop canvas class 
 * (class.cropcanvas.php) with a very easy to use and understand user 
 * interface.
 *
 * Using your browser you can drag and resize the cropping area and
 * select if you want to resize in any direction or proportional to
 * the image.  If you wanted to provide users a cropping area without 
 * any resizing options, then this can easily be acheived.
 * 
 * In the interface you can also have preset sizes as set pixel dimensions
 * (50x100), ratios of the image size (16:9), or divisions of the image
 * size (4/3).
 *
 * [Requirements]
 *
 * You will need the crop canvas class also from <http://php.amnuts.com/>
 * The cropping area implements the 'Drag & Drop API' javascript by
 * Walter Zorn <http://www.walterzorn.com/dragdrop/dragdrop_e.htm>.
 * 
 * [Author]
 *
 * Andrew Collington <php@amnuts.com> <http://php.amnuts.com/>
 *
 * [Feedback]
 *
 * There is message board at the following address:
 *
 *    <http://php.amnuts.com/forums/>
 *
 * Please use that to post up any comments, questions, bug reports, etc.
 * You can also use the board to show off your use of the script.
 *
 * [Support]
 *
 * If you like this script, or any of my others, then please take a moment
 * to consider giving a donation.  This will encourage me to make updates
 * and create new scripts which I would make available to you, and to give
 * support for my current scripts.  If you would like to donate anything,
 * then there is a link from my website to PayPal.
 *
 * [Example of use]
 *
 *  require('class.cropinterface.php');
 *  $ci =& new CropInterface();
 *  if ($_GET['file']) {
 *      $ci->loadImage($_GET['file']);
 *      $ci->cropToDimensions($_GET['sx'], $_GET['sy'], $_GET['ex'], $_GET['ey']);
 *      $ci->showImage('jpg', 90);
 *      exit;
 *  } else {
 *      $ci->setCropAllowResize(true);
 *      $ci->setCropTypeDefault(ccRESIZEPROP);
 *      $ci->setCropTypeAllowChange(true);
 *      $ci->setCropSizeDefault('5:3');
 *      $ci->setCropMinSize(10, 10);
 *      $ci->setExtraParameters('fake', 'this_var');
 *      $ci->setCropSizeList(array(
 *              '200x200' => '200 x 200 pixels',
 *              '320x240' => '320 x 240 pixels',
 *              '3:5'     => '3x5 ratio',
 *              '5:3'     => '5x3 ratio',
 *              '8:10'    => '8x10 ratio',
 *              '10:8'    => '10x8 ratio',
 *              '4:3'     => 'TV screen',
 *              '16:9'    => 'Widescreen',
 *              '2/2'     => 'Half size',
 *              '4/2'     => 'Quater width and half height'
 *              ));
 *      $ci->loadInterface('mypicture.jpg');
 *  }
 *
 */

require_once(dirname(__FILE__) . '/class.cropcanvas.php');

define('ccCROPSIZEMIN', 5);
define('ccRESIZEANY',   0);
define('ccRESIZEPROP',  1);

class CropInterface extends CropCanvas
{
    /**
     * The filename
     *
     * @var string
     * @access public
     */
    var $file = '';
    /**
     * Simply the name of the file
     *
     * @var string
     * @access public
     */
    var $name = '';
    /**
     * Holds information about the image
     *
     * @var array
     * @access public
     */
    var $img  = array();
    /**
     * Holds information about the crop
     *
     * @var array
     * @access public
     */
    var $crop = array(
                    'type'        => ccRESIZEANY,
                    'change'      => true,
                    'resize'      => true,
                    'min-width'   => ccCROPSIZEMIN,
                    'min-height'  => ccCROPSIZEMIN,
                    'sizes'       => array(),
                    'default'     => '',
                    'position'    => ccCENTRE
                    );
    /**
     * Holds any extra parameters supplied by user.
     *
     * @var array
     * @access public
     */
    var $params = array(
                    'list' => array(),
                    'str'  => ''
                    );



    /**
     * Class constructor.
     *
     * @param  boolean $debug
     * @return CropInterface
     */
    function CropInterface($debug = false)
    {
        parent::CropCanvas($debug);
    }

    /**
     * Set the default cropping area resize type.
     * 
     * Cropping area resize type is either ccRESIZEANY or ccRESIZEPROP for
     * any dimensions or proportional (respectively).
     *
     * @param interger $type
     * @see CropInterface::setCropTypeAllowChange
     */
    function setCropTypeDefault($type = ccRESIZEANY)
    {
        $this->crop['type'] = $type;
    }
    
    /**
     * Allow the user to change the crop area resize type.
     * 
     * By default the user can swap between resizing the cropping area by any
     * dimension of proportionally based on what the cropping area's dimensions
     * are currently.  Passing flase as a parameter will stop the user being
     * able to swap which type or resize they are using.
     *
     * @param boolean $allow
     * @see CropInterface::setCropTypeDefault
     */
    function setCropTypeAllowChange($allow = true)
    {
        $this->crop['change'] = $allow;
    }
    
    /**
     * Allow any type of resizing for the cropping area.
     * 
     * Sometimes you may want to set a default size and not allow the user
     * to resize the cropping area but instead just move it around.  If this
     * were the case, you'd call this method passing false as a parameter.
     *
     * @param boolean $allow
     */
    function setCropAllowResize($allow = true)
    {
        $this->crop['resize'] = $allow;
    }
    
    /**
     * Set preset size options.
     * 
     * The $size parameter can be an array with the size string as the index
     * and description as the value, or passed as a string and used in 
     * conjunction with the second parameter.
         * 
         * The size string can be one of three format:
         * 
         *     o 50x100 - 50 pixels by 100 pixels
         *     o 4:3 - a ratio of 4 to 3
         *     o 3/2 - a thrid of the width and half the height
         * 
         * This will give a drop-down list of preset options in the interface.
     *
     * @param array|string $size
     * @param string $description
     */
    function setCropSizeList($size, $description = '')
    {
        if (is_array($size)) {
            foreach ($size as $s => $d) {
                if (preg_match('!^(\d+)[:xX/](\d+)$!', $s)) {
                    $this->crop['sizes'][strtolower($s)] = $d;
                }
            }
        } else if (preg_match('!^(\d+)[:xX/](\d+)$!', $size)) {
            $this->crop['sizes'][strtolower($size)] = $description;
        }
    }
    
    /**
     * Allows you to set the default position of the crop window.
     * 
     * Valid positions are:
     * 
     *     o ccTOPLEFT
     *     o ccTOP
     *     o ccTOPRIGHT
     *     o ccLEFT
     *     o ccCENTRE (or) ccCENTER
     *     o ccRIGHT
     *     o ccBOTTOMLEFT
     *     o cBOTTOM
     *     o ccBOTTOMRIGHT
     *
     * @param int $position
     */
    function setCropPositionDefault($position)
    {
        $this->crop['position'] = $position;
    }
    
    /**
     * Set the initial size of the cropping area.
         * 
         * The size string can be one of three format:
         * 
         *     o 50x100 - 50 pixels by 100 pixels
         *     o 4:3 - a ratio of 4 to 3
         *     o 3/2 - a third of the width and half the height
     *
     * @param string $size
     */
    function setCropSizeDefault($size)
    {
        if (preg_match('!^(\d+)[:xX/](\d+)$!', $size)) {
            $this->crop['default'] = $size;
        }
    }
    
    /**
     * Set the smallest size of the cropping area.
     *
     * @param int $w
     * @param int $h
     */
    function setCropMinSize($w = 25, $h = 25)
    {
        $this->crop['min-width']  = ($w < ccCROPSIZEMIN) ? ccCROPSIZEMIN : $w;
        $this->crop['min-height'] = ($h < ccCROPSIZEMIN) ? ccCROPSIZEMIN : $h;
    }
    
        /**
         * Initiates the cropping interface and javascript.
         *
         * @param string $filename
         * @access public
         * @see file://inc.cropinterface.php
         */
        function loadInterface($filename, $name)
        {
                if (!file_exists($filename)) {
                        die("The file '$filename' cannot be found.");
                } else {
                        $this->file = $filename;
                        $this->name = $name;
                        $this->img['sizes'] = $_SESSION["usuari"]->tamanys_imatge; //getimagesize($filename);
                        $this->img['src'] = str_replace($_SERVER['DOCUMENT_ROOT'], '', $this->file);
                        if ($this->crop['default'] == '') {
                                $this->crop['default'] = '3/3';
                        }
                }

                if (!empty($this->params['list']))
                {
                        $params = array();
                    foreach ($this->params['list'] as $key => $val) {
                        $params[] = $key . '=' . urlencode($val);
                        }
                    $this->params['str'] = '&' . join('&', $params);
                }
                include('../include/crop_canvas/inc.cropinterface.php');
        }
        
        /**
         * Calculate the width and height based on the size string.
         * 
         * The size string can be one of three format:
         * 
         *     o 50x100 - 50 pixels by 100 pixels
         *     o 4:3 - a ratio of 4 to 3
         *     o 3/2 - a third of the width and half the height
         *
         * @param  string $size
         * @return array
         * @access public
         */
        function calculateCropDimensions($size)
        {
            if (!isset($this->img['sizes'])) {
                return array(ccCROPSIZEMIN, ccCROPSIZEMIN);
            }
            
        if (strstr($size, 'x')) {
            list($w, $h) = explode('x', $size);
        } else if (strstr($size, '/')) {
            list($dw, $dh) = explode('/', $size);
            $w = round($this->img['sizes'][0] / $dw);
            $h = round($this->img['sizes'][1] / $dh);
        } else {
            list($pw, $ph) = explode(':', $size);
            $w = $this->img['sizes'][0];
            $h = round($ph * $this->img['sizes'][0] / $pw);
            if ($h > $this->img['sizes'][1]) {
                $w = round($this->img['sizes'][1] * $pw / $ph);
                $h = $this->img['sizes'][1];
            }
        }
        if ($w < ccCROPSIZEMIN) {
            $w = ccCROPSIZEMIN;
        }
        if ($h < ccCROPSIZEMIN) {
            $h = ccCROPSIZEMIN;
        }
        if ($w > $this->img['sizes'][0]) {
            $w = $this->img['sizes'][0];
        }
        if ($h > $this->img['sizes'][1]) {
            $h = $this->img['sizes'][1];
        }
        return array($w, $h);
        }
        
    /**
         * Determine position of the crop.
         * 
         * @param  int $cw
         * @param  int $ch
         * @return array
         */
    function calculateCropPosition($cw, $ch)
    {
        if (!isset($this->img['sizes']) || !isset($this->crop['position'])) {
                return array(0, 0);
            }

        switch($this->crop['position']) {
            case ccTOPLEFT:
                return array(0, 0);
            case ccTOP:
                return array(ceil(($this->img['sizes'][0] - $cw) / 2), 0);
            case ccTOPRIGHT:
                return array(($this->img['sizes'][0] - $cw), 0);
            case ccLEFT:
                return array(0, ceil(($this->img['sizes'][1] - $ch) / 2));
            case ccCENTRE:
                return array(ceil(($this->img['sizes'][0] - $cw) / 2), ceil(($this->img['sizes'][1] - $ch) / 2));
            case ccRIGHT:
                return array(($this->img['sizes'][0] - $cw), ceil(($this->img['sizes'][1] - $ch) / 2));
            case ccBOTTOMLEFT:
                return array(0, ($this->img['sizes'][1] - $ch));
            case ccBOTTOM:
                return array(ceil(($this->img['sizes'][0] - $cw) / 2), ($this->img['sizes'][1] - $ch));
            case ccBOTTOMRIGHT:
                return array(($this->img['sizes'][0] - $cw), ($this->img['sizes'][1] - $ch));
            default:
                return array(0, 0);
        }
    }
    
        /**
         * Allows user to supply additiona parameters sent in the form.
         *
         * @param array|string $name
         * @param string $value
         */
        function setExtraParameters($name, $value = '')
    {
        if (is_array($name)) {
            foreach ($name as $key => $value) {
                $this->params['list'][$key] = $value;
            }
        } else {
            $this->params['list'][$name] = $value;
        }
    } 

}

?>
