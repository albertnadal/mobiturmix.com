<?php
require('../include/constants_smsturmix.php');

define("PUIJAR_ORIGINAL",               0);
define("RETALLAR_NORMAL",               1);
define("RETALLAR_VERTICAL",             2);
define("RETALLAR_APAISAT",              3);
define("VALIDAR_PREVIEW",               4);
define("ENTRAR_METAINFORMACIO",         5);
define("MOSTRAR_REPORT",                6);

list($w, $h) = $this->calculateCropDimensions($this->crop['default']);
list($x, $y) = $this->calculateCropPosition($w, $h);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
print META_CONTENT_TYPE."\n";
print META_AUTHOR."\n";
print META_COPYRIGHT."\n";
print META_DESCRIPTION_PHOTO_ES."\n";
print META_DESCRIPTION_PHOTO_EN."\n";
print META_KEYWORDS_ES."\n";
print META_KEYWORDS_EN."\n";
print META_ROBOTS_INDEX_NOFOLLOW."\n";
print TITLE_PHOTOTURMIX."\n";
?>
        <link rel="shortcut icon" href="favicon.ico" />

  <style type="text/css">
  /*<![CDATA[*/
   td.photo { background: url(u_photo2.gif); cursor: hand; cursor: pointer;}
   td.video { background: url(u_video.gif); cursor: hand; cursor: pointer;}
   td.audio { background: url(u_audio.gif); cursor: hand; cursor: pointer;}
   td.anima { background: url(u_anima2.gif); cursor: hand; cursor: pointer;}
  /*]]>*/

<?php
$_SESSION["sessio_usuari"]->incrustar_codi_css_menu_superior_sessio();
?>

  </style>
</head>

<body>

<style type="text/css">
    #cropInterface {
        border: 0px solid black;
        padding: 0;
        margin: 0;
        text-align: center;
        background-color: transparent;
        color: #fff;
                font-family: Tahoma, Verdana, Arial, Helvetica, sans-serif;
                font-size: 11px;
        width: <?php echo $this->img['sizes'][0];?>px;
    }
    #cropDetails {
        margin: 5px;
        padding: 0;
    }
    #cropResize, #cropResize p {
        margin: 5px;
        padding: 0;
        font-size: 11px;
        display: <?php echo ($this->crop['change'] && $this->crop['resize']) ? 'inherit' : 'none'; ?>;
    }
    #cropSizes {
        margin: 5px;
        padding: 0;
        font-size: 11px;
        display: <?php echo (!empty($this->crop['sizes']) && $this->crop['resize']) ? 'inherit' : 'none'; ?>;
    }
    #cropImage {
        border-top: 0px solid black;
        border-bottom: 0x solid black;
        margin: 0;
        padding: 0;
    }
    #cropSubmitButton {
        font-size: 10px;
        font-family: "MS Sans Serif", Geneva, sans-serif;
        //background-color: #D4D0C8;
        border: 0;
        margin: 0;
        padding: 5px;
        width: 100%;
    }
    #theCrop {
        position: absolute;
        background-color: transparent;
        border: 1px solid yellow;
        background-image: url(<?php echo $this->file."?id=".($_SESSION["usuari"]->codi_usuari); ?>);
        background-repeat: no-repeat;
        padding: 0;
        margin: 0;
    }
    #theCrop { 
        width: <?php echo ($w - 2); ?>px; 
        font-family:inherit;
    } 
    #theCrop { 
        height: <?php echo ($h - 2); ?>px;
        font-family:inherit;
    }
    html>body #theCrop {
        width:<?php echo ($w - 2); ?>px;
        height:<?php echo ($h - 2); ?>px;
    }
</style>



<script type="text/javascript">
//<![CDATA[
function URLEncode(plaintext)
{
        // The Javascript escape and unescape functions do not correspond
        // with what browsers actually do...
        var SAFECHARS = "0123456789" +                                  // Numeric
                                        "ABCDEFGHIJKLMNOPQRSTUVWXYZ" +  // Alphabetic
                                        "abcdefghijklmnopqrstuvwxyz" +
                                        "-_.!~*'()";                                    // RFC2396 Mark characters
        var HEX = "0123456789ABCDEF";
        var encoded = "";
        for (var i = 0; i < plaintext.length; i++ ) {
                var ch = plaintext.charAt(i);
            if (ch == " ") {
                    encoded += "+";                             // x-www-urlencoded, rather than %20
                } else if (SAFECHARS.indexOf(ch) != -1) {
                    encoded += ch;
                } else {
                    var charCode = ch.charCodeAt(0);
                        if (charCode > 255) {
                            alert( "Unicode Character '"
                        + ch
                        + "' cannot be encoded using standard URL encoding.\n" +
                                          "(URL encoding only supports 8-bit characters.)\n" +
                                                  "A space (+) will be substituted." );
                                encoded += "+";
                        } else {
                                encoded += "%";
                                encoded += HEX.charAt((charCode >> 4) & 0xF);
                                encoded += HEX.charAt(charCode & 0xF);
                        }
                }
        }
        return encoded;
}

function gebi(id) { return document.getElementById(id); }
function show(id,b)
{
  e=gebi(id);
  e.style.display=b;
}
function s(id)
{
  e=gebi('none');
  e.style.display='none';
  e.style.visibility='hidden';
  e=gebi(id);
  e.style.visibility='visible';
  e.style.display='block';
}
function h(id)
{
  e=gebi(id);
  e.style.visibility='hidden';
  e.style.display='none';
  e=gebi('none');
  e.style.visibility='visible';
  e.style.display='block';
}
//]]>
</script>

<?php
$_SESSION["sessio_usuari"]->incrustar_codi_menu_superior_sessio();
?>

<table width="903" height="60" border="0" align="center">
  <tr>
    <td width="106" align="center" valign="middle"><img src="http://www.mobiturmix.com/arrows.gif" id="arr"/></td>
    <td width="427" height="60"><?php
	$pas = $_SESSION["usuari"]->proxim_pas;
	switch($pas)
	{
		case RETALLAR_NORMAL:	print "<img id=\"mt\" src=\"v_normal.gif\" />"; break;
		case RETALLAR_VERTICAL:	print "<img id=\"mt\" src=\"v_vertical.gif\" />"; break;
		case RETALLAR_APAISAT:	print "<img id=\"mt\" src=\"v_apaisada.gif\" />"; break;
	}
?><img id="mfot" src="m_recorta.gif" /></td>
    <td width="358" align="right"><a href="http://www.mobiturmix.com"><img src="http://www.mobiturmix.com/logo.gif" border="0"/></a></td>
  </tr>
</table>
<table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr background="u_audio.gif">
    <td colspan="8" align="left"><img src="u_top.gif" width="905" height="11" /></td>
  </tr>
  <tr align="left" valign="top">
    <td width="105"><img src="d0.gif" height="34" /></td>
    <td width="150" height="34" class="photo" onMouseOver="show('pho','block');" onMouseOut="show('pho','none');" onclick="document.location.href='http://photo.mobiturmix.com';"><img style="display:none" id="pho" src="r_photo2.gif"/></td>
    <td width="117" height="34" class="video" onMouseOver="show('vid','block');" onMouseOut="show('vid','none');" onclick="document.location.href='http://video.mobiturmix.com';"><img style="display:none" id="vid" src="r_video.gif"/></td>
    <td width="118" height="34" class="audio" onMouseOver="show('aud','block');" onMouseOut="show('aud','none');" onclick="document.location.href='http://audio.mobiturmix.com';"><img style="display:none" id="aud" src="r_audio.gif"/></td>
    <td width="153" height="34" class="anima" onMouseOver="show('ani','block');" onMouseOut="show('ani','none');" onclick="document.location.href='http://anima.mobiturmix.com';"><img style="display:none" id="ani" src="r_anima2.gif"/></td>
    <td width="43"><img src="d1.gif" height="34" /></td>
    <td width="141" valign="middle" background="cerca.gif"><input id="search" type="text" size="17" maxlength="40" /></td>
    <td width="78"><img style="cursor:pointer;cursor:hand;" alt="Buscar fotos" title="Buscar fotos" src="bcerca.gif" height="34" onclick="c=gebi('search'); window.location='ft.php?s='+URLEncode(c.value);"/><img src="d2.gif" height="34" /></td>
  </tr>
</table>
  
  
  <table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="650" rowspan="4" background="d17.gif" valign="middle" align="right">
<!-- INICI ZONA DE RETALL -->
                        <div id="theCrop"></div>
                        <div id="cropInterface">
                            <div id="cropDetails">
                                <strong><?php echo basename($this->name); ?> (<?php echo $this->img['sizes'][0]; ?> x <?php echo $this->img['sizes'][1]; ?>)</strong><div id="cropDimensions"></div>
                            </div>
                            <div id="cropImage">
                                <img src="<?php echo $this->img['src']; ?>" <?php echo $this->img['sizes'][3]; ?> alt="Recortar esta imagen" title="Recortar esta imagen" name="theImage" id="theImage" />
                            </div>
                            <div id="cropSubmit">
                                <form action="#" method="POST" name="crop_form">
                                        <input type="hidden" name="sx" value="">
                                        <input type="hidden" name="sy" value="">
                                        <input type="hidden" name="ex" value="">
                                        <input type="hidden" name="ey" value="">
					<input type="hidden" name="a" value="">
                                </form>
                            </div>
                        </div>
<!-- FI ZONA DE RETALL -->
    </td>
    <td width="36" rowspan="4" align="left" background="d18.gif" valign="top"></td>
	<td><img src="d13.gif" /></td>
  </tr>
  <tr>
    <td height="40"><img src="d14.gif" /><img style="cursor:pointer;cursor:hand;" alt="Anterior" title="Anterior" src="d7.gif" onmouseover="s('back');" onmouseout="h('back');" onClick="cc_Submit('a');"/><img style="cursor:pointer;cursor:hand;" alt="Cancelar" title="Cancelar" src="d6.gif" onmouseover="s('cancel');" onmouseout="h('cancel');" onclick="cc_Submit('c');"/><img style="cursor:pointer;cursor:hand;" alt="Siguiente" title="Siguiente" src="d5.gif" onmouseover="s('next');" onmouseout="h('next');"  onClick="cc_Submit('s');"/><img src="d4.gif" /></td>
    </tr>
  <tr>
    <td width="219" height="60"><img id="none" src="d12.gif" /><img style="display:none" id="back" src="r12_back.gif" width="219" height="60" /><img style="display:none" id="cancel" src="r12_cancel.gif" width="219" height="60" /><img style="display:none" id="next" src="r12_next.gif" width="219" height="60" /></td>
  </tr>
  <tr>
    <td height="148"><img src="d15.gif" /></td>
  </tr>
  <tr>
    <td height="30" colspan="3" align="center" valign="middle"><img src="d11.gif" /></td>
  </tr>
  <tr>
    <td colspan="3"><img src="http://www.mobiturmix.com/p_foot.gif" width="882" height="25" /></td>
  </tr>
</table>


<script type="text/javascript" src="js/wz_dragdrop.js"></script>
<script type="text/javascript">
    SET_DHTML("theCrop"+MAXOFFLEFT+0+MAXOFFRIGHT+<?php echo $this->img['sizes'][0]; ?>+MAXOFFTOP+0+MAXOFFBOTTOM+<?php echo $this->img['sizes'][1], ($this->crop['resize'] ? '+RESIZABLE' : ''); ?>+MAXWIDTH+<?php echo $this->img['sizes'][0]; ?>+MAXHEIGHT+<?php echo $this->img['sizes'][1]; ?>+MINHEIGHT+<?php echo $this->crop['min-height']; ?>+MINWIDTH+<?php echo $this->crop['min-width']; ?>,"theImage"+NO_DRAG,"cropDimensions"+NO_DRAG);

    dd.elements.theCrop.moveTo(dd.elements.theImage.x + <?php echo $x; ?>, dd.elements.theImage.y + <?php echo $y; ?>);
    dd.elements.theCrop.setZ(dd.elements.theImage.z+1);
    dd.elements.theImage.addChild("theCrop");
    dd.elements.theCrop.defx = dd.elements.theImage.x;
    dd.elements.theCrop.defy = dd.elements.theImage.y;
    dd.elements.theImage.setOpacity(0.3);

//    <?php if ($this->crop['resize']) { echo 'cc_SetResizingType(', (string)$this->crop['type'], ');'; } ?>
    //Les següents 4 linies fan que l'area de tall sigui proporcional en tamany
    dd.elements.theCrop.defw = dd.elements.theCrop.w;
    dd.elements.theCrop.defh = dd.elements.theCrop.h;
    dd.elements.theCrop.scalable  = 1;
    dd.elements.theCrop.resizable = 0;

    cc_showCropSize();
    cc_reposBackground();

    function my_DragFunc()
    {
        dd.elements.theCrop.maxoffr = dd.elements.theImage.w - dd.elements.theCrop.w;
        dd.elements.theCrop.maxoffb = dd.elements.theImage.h - dd.elements.theCrop.h;
        dd.elements.theCrop.maxw    = <?php echo $this->img['sizes'][0]; ?>;
        dd.elements.theCrop.maxh    = <?php echo $this->img['sizes'][1]; ?>;
        cc_showCropSize();
                cc_reposBackground();
    }

    function my_ResizeFunc()
    {
        dd.elements.theCrop.maxw = (dd.elements.theImage.w + dd.elements.theImage.x) - dd.elements.theCrop.x;
        dd.elements.theCrop.maxh = (dd.elements.theImage.h + dd.elements.theImage.y) - dd.elements.theCrop.y;
        cc_showCropSize();
                cc_reposBackground();
    }
    
    function cc_Submit(a)
    {
        document.forms['crop_form'].sx.value = (dd.elements.theCrop.x - dd.elements.theImage.x);
        document.forms['crop_form'].sy.value = (dd.elements.theCrop.y - dd.elements.theImage.y);
        document.forms['crop_form'].ex.value = ((dd.elements.theCrop.x - dd.elements.theImage.x) + dd.elements.theCrop.w);
        document.forms['crop_form'].ey.value = ((dd.elements.theCrop.y - dd.elements.theImage.y) + dd.elements.theCrop.h);
	document.forms['crop_form'].a.value = a;
        document.forms['crop_form'].submit();
        return true;
    }

/*    function cc_SetResizingType(proportional)
    {
        if (proportional) {
            dd.elements.theCrop.defw = dd.elements.theCrop.w;
            dd.elements.theCrop.defh = dd.elements.theCrop.h;
            dd.elements.theCrop.scalable  = 1;
            dd.elements.theCrop.resizable = 0;
        } else {
            dd.elements.theCrop.scalable  = 0;
            dd.elements.theCrop.resizable = 1;
        }
    }*/

    function cc_reposBackground()
    {
        xPos = (dd.elements.theCrop.x - dd.elements.theImage.x + 1);
        yPos = (dd.elements.theCrop.y - dd.elements.theImage.y + 1);
        
        if (document.getElementById) {
            tc = document.getElementById('theCrop'); 
            tc.style.backgroundPosition = '-' + xPos + 'px -' + yPos + 'px';
            tc.style.cursor = 'pointer';
            tc.style.cursor = 'hand';
        }
        else if (document.all) document.all['theCrop'].style.backgroundPosition = '-' + xPos + 'px -' + yPos + 'px';
        else document.layers['theCrop'].backgroundPosition = '-' + xPos + 'px -' + yPos + 'px';
    }
    
    function cc_showCropSize()
    {
        dd.elements.cropDimensions.write('Recorte: ' + dd.elements.theCrop.w + ' / ' + dd.elements.theCrop.h);
    }

    function cc_setSize()
    {
        element = document.getElementById('setSize');
        switch(element.value) {
        <?php
            $str = "case '%s':
                        cc_setCropDimensions(%d, %d);
                        dd.elements.theCrop.moveTo(dd.elements.theImage.x + %d, dd.elements.theImage.y + %d);
                        cc_reposBackground();
                        break\n";
            if ($this->crop['sizes']) {
                foreach ($this->crop['sizes'] as $s => $d) {
                    list($w, $h) = $this->calculateCropDimensions($s);
                    list($x, $y) = $this->calculateCropPosition($w, $h);
                    printf($str, $s, $w, $h, $x, $y);
                }
            }
        ?>
        }
        cc_showCropSize();
    }
    
    function cc_setCropDimensions(w, h)
    {
        dd.elements.theCrop.moveTo(dd.elements.theImage.x, dd.elements.theImage.y);
        dd.elements.theCrop.resizeTo(w, h);
        dd.elements.theCrop.defw = w;
        dd.elements.theCrop.defh = h;
        cc_reposBackground();
    }
</script>


  <script type="text/javascript">
    // <![CDATA[
      var Engine = {
        detect: function() {
          var UA = navigator.userAgent;
          this.isKHTML = /Konqueror|Safari|KHTML/.test(UA);
          this.isGecko = (/Gecko/.test(UA) && !this.isKHTML);
          this.isOpera = /Opera/.test(UA);
          this.isMSIE  = (/MSIE/.test(UA) && !this.isOpera);
          this.isMSIE7 = this.isMSIE && !(/MSIE 6\./.test(UA) && !this.isOpera);
        }
      }
      Engine.detect();
      
      // poor IE6 gets no shadows, ha!
      if(Engine.isMSIE && !Engine.isMSIE7) {
        $('logo').src = 'phototurmix_logo.gif';
        $$('.shot').each(function(s){
          s.src = 'shot.gif';
        });
      } else {
        $('logo').src = 'images/phototurmix_logo.png';
      }
      
      $('header').setStyle({top:'80px',left:'-700px'});
      $('logo').setStyle({display:'block'});
      
      new Effect.Move('header',{ x: 730, y: -80 });
    
      function bubble(id,x,y){
        $(id+'-bubble').setStyle({left:x+'px',top:y+'px'});
        new Effect.Scale(id+'-bubble',100, Object.extend({
          beforeStart:function(effect){
            $(effect.element).style.display = 'block';
            $(effect.element).setOpacity(0);
            $$('#'+id+'-bubble p').each(function(p){p.hide()});
          },
          afterUpdate:function(effect){
            $(effect.element).setOpacity(effect.position);
          },
          scaleFrom:0,
          scaleFromCenter:true,
          afterFinish:function(effect){
            $$('#'+id+'-bubble p').each(function(p){
              new Effect.Appear(p,{duration:0.4});
            });
          }
        }, arguments[3] || {}));        
      }

    // ]]>
  </script>
</body>
</html>
