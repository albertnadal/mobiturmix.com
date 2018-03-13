<?php
include ("upload_class.php");
define("MAX_FILE_SIZE", 16777216); //16MB tamany màxim fitxer per defecte
//define("UPLOAD_DIR", $_SERVER['DOCUMENT_ROOT']."/tmp/"); //Ruta per defecte on es penjen els fitxers al servidor. Constant ja definida a rutes.inc.php

class file_uploader extends file_upload
{
        var $number_of_files = 0;
        var $names_array;
        var $tmp_names_array;
        var $error_array;
        var $wrong_extensions = 0;
        var $bad_filenames = 0;
        var $max_size;
        var $on_upload_function = '';
        var $delete_files_after_on_upload_function_call = false;

        function file_uploader($lang, $size=MAX_FILE_SIZE)
        {
                $this->language=$lang;
                $this->max_size = $size;
                $this->rename_file = false;
                $this->ext_string = "";
                $this->upload_dir=UPLOAD_DIR;
                $this->do_filename_check='y';
                $this->delete_files_after_on_upload_function_call = false;
                $this->do_filename_check = false;
                $this->on_upload_optional_param = null;
        }

        function set_language($lang)
        {
                $this->language=$lang;
        }

        function set_max_file_size($size)
        {
                $this->max_size = $size;
        }

        function set_upload_dir($dir)
        {
                $this->upload_dir=$dir;
        }

        function set_allowed_file_extensions($ext)
        {
                $this->extensions=$ext;
        }

        function do_filename_check($opt)
        {
                $this->do_filename_check=$opt;
        }

        function set_on_upload_function($function_name, $optional_param)
        {
                $this->on_upload_function=$function_name;
                $this->on_upload_optional_param=$optional_param;
        }

        function delete_files_after_on_upload_function_call($flag)
        {
                $this->delete_files_after_on_upload_function_call = $flag;
        }

        function extra_text($msg_num)
        {
                switch ($this->language)
                {
                        case "cat":
                                $extra_msg[1] = "Error: <b>".$this->the_file."</b>";
                                $extra_msg[2] = "Has probat de puijar fitxers amb extensions ".$this->wrong_extensions.", només les següents extensions estan permeses: <b>".$this->ext_string."</b>";
                                $extra_msg[3] = "Escull almenys un fitxer.";
                                $extra_msg[4] = "Escull el(s) fitxer(s) a penjar.";
                                $extra_msg[5] = "Has probat de puijar el fitxer <b>".$this->bad_filenames."</b> que conté caràcters no vàlids al nom.";
                                $extra_msg[6] = "Fitxer";
                                $extra_msg[7] = "Adjuntar més fitxers...";
                        break;
                        case "es":
                                $extra_msg[1] = "Error: <b>".$this->the_file."</b>";
                                $extra_msg[2] = "Has probado de subir ficheros con extensiones ".$this->wrong_extensions.", solo las siguientes extensiones estan permitidas: <b>".$this->ext_string."</b>";
                                $extra_msg[3] = "Escoje almenos un fichero.";
                                $extra_msg[4] = "Escoje el/los fichero(s) a subir.";
                                $extra_msg[5] = "Has probado de subir el fichero <b>".$this->bad_filenames."</b> que contiene carácteres no válidos al nombre.";
                                $extra_msg[6] = "Fichero";
                                $extra_msg[7] = "Adjuntar más ficheros...";
                        break;
                        default:
                                $extra_msg[1] = "Error for: <b>".$this->the_file."</b>";
                                $extra_msg[2] = "You have tried to upload ".$this->wrong_extensions." files with a bad extension, the following extensions are allowed: <b>".$this->ext_string."</b>";
                                $extra_msg[3] = "Select at least on file.";
                                $extra_msg[4] = "Select the file(s) for upload.";
                                $extra_msg[5] = "You have tried to upload <b>".$this->bad_filenames." files</b> with invalid characters inside the filename.";
                                $extra_msg[6] = "File";
                                $extra_msg[7] = "Attach more files...";
                }
                return $extra_msg[$msg_num];
        }
        // this method checkes the number of files for upload
        // this example works with one or more files
        function count_files()
        {
                if($this->names_array !="") { $this->number_of_files=1; return true; }
                else return false;
        }

        function upload_multi_files()
        {
                $this->message = "";
                if ($this->count_files())
                {
                                $value = $this->names_array;

                                if ($value != "")
                                {
                                        $this->the_file = $value;
                                        $new_name = $this->set_file_name();
                                        if ($this->check_file_name($new_name))
                                        {
                                                if ($this->validateExtension())
                                                {
                                                        $this->file_copy = $new_name;
                                                        $this->the_temp_file = $this->tmp_names_array;
                                                        if (is_uploaded_file($this->the_temp_file))
                                                        {
                                                                if ($this->move_upload($this->the_temp_file, $this->file_copy))
                                                                {
                                                                        $this->message[] = $this->error_text($this->error_array[$key]);
                                                                        if ($this->rename_file) $this->message[] = $this->error_text(16);
                                                                        if ($this->on_upload_function!='')
                                                                        {
                                                                                $content = $this->get_file_content($this->upload_dir."".$this->file_copy);
                                                                                $file_size = $this->get_file_size($this->upload_dir."".$this->file_copy);
                                                                                $sizes = getimagesize($this->upload_dir."".$this->file_copy);
                                                                                $mime_type = $this->get_file_mime_type($this->upload_dir."".$this->file_copy);
                                                                                $file_name = $this->file_copy;

                                                                                call_user_func($this->on_upload_function, $content, $file_name , $mime_type, $file_size, $sizes, $this->on_upload_optional_param);
                                                                                if($this->delete_files_after_on_upload_function_call) $this->delete_file($this->upload_dir."".$this->file_copy);
                                                                        }
                                                                        sleep(1); // wait a seconds to get an new timestamp (if rename is set)
                                                                }
                                                        } else
                                                        {
                                                                $this->message[] = $this->extra_text(1);
                                                                $this->message[] = $this->error_text($this->error_array[$key]);
                                                        }
                                                } else  $this->wrong_extensions++;
                                        } else  $this->bad_filenames++;
                                }

                        if ($this->bad_filenames > 0) $this->message[] = $this->extra_text(5);
                        if ($this->wrong_extensions > 0)
                        {
                                $this->show_extensions();
                                $this->message[] = $this->extra_text(2);
                        }
                } else  $this->message[] = $this->extra_text(3);
        }

        function get_file_mime_type($file)
        {
                $mime_types = array('.ai' => 'application/postscript',
                                '.aif' => 'audio/x-aiff',
                                '.aifc' => 'audio/x-aiff',
                                '.aiff' => 'audio/x-aiff',
                                '.asc' => 'text/plain',
                                '.au' => 'audio/basic',
                                '.avi' => 'video/x-msvideo',
                                '.bcpio' => 'application/x-bcpio',
                                '.bin' => 'application/octet-stream',
                                '.bmp' => 'image/bmp',
                                '.c' => 'text/plain',
                                '.cc' => 'text/plain',
                                '.ccad' => 'application/clariscad',
                                '.cdf' => 'application/x-netcdf',
                                '.class' => 'application/octet-stream',
                                '.cpio' => 'application/x-cpio',
                                '.cpt' => 'application/mac-compactpro',
                                '.csh' => 'application/x-csh',
                                '.css' => 'text/css',
                                '.dcr' => 'application/x-director',
                                '.dir' => 'application/x-director',
                                '.dms' => 'application/octet-stream',
                                '.doc' => 'application/msword',
                                '.drw' => 'application/drafting',
                                '.dvi' => 'application/x-dvi',
                                '.dwg' => 'application/acad',
                                '.dxf' => 'application/dxf',
                                '.dxr' => 'application/x-director',
                                '.eps' => 'application/postscript',
                                '.etx' => 'text/x-setext',
                                '.exe' => 'application/octet-stream',
                                '.ez' => 'application/andrew-inset',
                                '.f' => 'text/plain',
                                '.f90' => 'text/plain',
                                '.fli' => 'video/x-fli',
                                '.gif' => 'image/gif',
                                '.gtar' => 'application/x-gtar',
                                '.gz' => 'application/x-gzip',
                                '.h' => 'text/plain',
                                '.hdf' => 'application/x-hdf',
                                '.hh' => 'text/plain',
                                '.hqx' => 'application/mac-binhex40',
                                '.htm' => 'text/html',
                                '.html' => 'text/html',
                                '.ice' => 'x-conference/x-cooltalk',
                                '.ief' => 'image/ief',
                                '.iges' => 'model/iges',
                                '.igs' => 'model/iges',
                                '.ips' => 'application/x-ipscript',
                                '.ipx' => 'application/x-ipix',
                                '.jpe' => 'image/jpeg',
                                '.jpeg' => 'image/jpeg',
                                '.jpg' => 'image/jpeg',
                                '.js' => 'application/x-javascript',
                                '.kar' => 'audio/midi',
                                '.latex' => 'application/x-latex',
                                '.lha' => 'application/octet-stream',
                                '.lsp' => 'application/x-lisp',
                                '.lzh' => 'application/octet-stream',
                                '.m' => 'text/plain',
                                '.man' => 'application/x-troff-man',
                                '.me' => 'application/x-troff-me',
                                '.mesh' => 'model/mesh',
                                '.mid' => 'audio/midi',
                                '.midi' => 'audio/midi',
                                '.mif' => 'application/vnd.mif',
                                '.mime' => 'www/mime',
                                '.mov' => 'video/quicktime',
                                '.movie' => 'video/x-sgi-movie',
                                '.mp2' => 'audio/mpeg',
                                '.mp3' => 'audio/mpeg',
                                '.mpe' => 'video/mpeg',
                                '.mpeg' => 'video/mpeg',
                                '.mpg' => 'video/mpeg',
                                '.mpga' => 'audio/mpeg',
                                '.ms' => 'application/x-troff-ms',
                                '.msh' => 'model/mesh',
                                '.nc' => 'application/x-netcdf',
                                '.oda' => 'application/oda',
                                '.pbm' => 'image/x-portable-bitmap',
                                '.pdb' => 'chemical/x-pdb',
                                '.pdf' => 'application/pdf',
                                '.pgm' => 'image/x-portable-graymap',
                                '.pgn' => 'application/x-chess-pgn',
                                '.png' => 'image/png',
                                '.pnm' => 'image/x-portable-anymap',
                                '.pot' => 'application/mspowerpoint',
                                '.ppm' => 'image/x-portable-pixmap',
                                '.pps' => 'application/mspowerpoint',
                                '.ppt' => 'application/mspowerpoint',
                                '.ppz' => 'application/mspowerpoint',
                                '.pre' => 'application/x-freelance',
                                '.prt' => 'application/pro_eng',
                                '.ps' => 'application/postscript',
                                '.qt' => 'video/quicktime',
                                '.ra' => 'audio/x-realaudio',
                                '.ram' => 'audio/x-pn-realaudio',
                                '.ras' => 'image/cmu-raster',
                                '.rgb' => 'image/x-rgb',
                                '.rm' => 'audio/x-pn-realaudio',
                                '.roff' => 'application/x-troff',
                                '.rpm' => 'audio/x-pn-realaudio-plugin',
                                '.rtf' => 'text/rtf',
                                '.rtx' => 'text/richtext',
                                '.scm' => 'application/x-lotusscreencam',
                                '.set' => 'application/set',
                                '.sgm' => 'text/sgml',
                                '.sgml' => 'text/sgml',
                                '.sh' => 'application/x-sh',
                                '.shar' => 'application/x-shar',
                                '.silo' => 'model/mesh',
                                '.sit' => 'application/x-stuffit',
                                '.skd' => 'application/x-koan',
                                '.skm' => 'application/x-koan',
                                '.skp' => 'application/x-koan',
                                '.skt' => 'application/x-koan',
                                '.smi' => 'application/smil',
                                '.smil' => 'application/smil',
                                '.snd' => 'audio/basic',
                                '.sol' => 'application/solids',
                                '.spl' => 'application/x-futuresplash',
                                '.src' => 'application/x-wais-source',
                                '.step' => 'application/STEP',
                                '.stl' => 'application/SLA',
                                '.stp' => 'application/STEP',
                                '.sv4cpio' => 'application/x-sv4cpio',
                                '.sv4crc' => 'application/x-sv4crc',
                                '.swf' => 'application/x-shockwave-flash',
                                '.t' => 'application/x-troff',
                                '.tar' => 'application/x-tar',
                                '.tcl' => 'application/x-tcl',
                                '.tex' => 'application/x-tex',
                                '.texi' => 'application/x-texinfo',
                                '.texinfo -  application/x-texinfo',
                                '.tif' => 'image/tiff',
                                '.tiff' => 'image/tiff',
                                '.tr' => 'application/x-troff',
                                '.tsi' => 'audio/TSP-audio',
                                '.tsp' => 'application/dsptype',
                                '.tsv' => 'text/tab-separated-values',
                                '.txt' => 'text/plain',
                                '.unv' => 'application/i-deas',
                                '.ustar' => 'application/x-ustar',
                                '.vcd' => 'application/x-cdlink',
                                '.vda' => 'application/vda',
                                '.viv' => 'video/vnd.vivo',
                                '.vivo' => 'video/vnd.vivo',
                                '.vrml' => 'model/vrml',
                                '.wav' => 'audio/x-wav',
                                '.wrl' => 'model/vrml',
                                '.xbm' => 'image/x-xbitmap',
                                '.xlc' => 'application/vnd.ms-excel',
                                '.xll' => 'application/vnd.ms-excel',
                                '.xlm' => 'application/vnd.ms-excel',
                                '.xls' => 'application/vnd.ms-excel',
                                '.xlw' => 'application/vnd.ms-excel',
                                '.xml' => 'text/xml',
                                '.xpm' => 'image/x-xpixmap',
                                '.xwd' => 'image/x-xwindowdump',
                                '.xyz' => 'chemical/x-pdb',
                                '.zip' => 'application/zip');
                $extensio = $this->get_extension($file);
                return $mime_types[$extensio];
        }

        function get_upload_html_form_part()
        {
                print "\t<form action=\"#\" method=\"post\" name=\"upload_form\" enctype=\"multipart/form-data\">\n";
                print "\t\t<input name=\"upload\" type=\"file\"  /><br><br>\n"; //style=\"background-color:#FFFF99; text-shadow:#33FF33; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:14px;\"
                print "\t\t<input value=\"Upload!\" type=\"submit\" />\n"; //style=\"text-shadow:#33FF33; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:14px; font-style:oblique;\"
                print "\t</form>\n";
        }

        function delete_file($file)
        {
                unlink($file);
        }

        function get_file_size($file)
        {
                return filesize($file);
        }

        function get_file_content($file)
        {
                $file_size = $this->get_file_size($file);
                $handle = fopen($file, "r");
                $content = fread($handle, $file_size);
                fclose($handle);
                return $content;
                //return chunk_split(base64_encode($content));
        }

        function upload_files()
        {
                $this->tmp_names_array = $_FILES['upload']['tmp_name'];
                $this->names_array = $_FILES['upload']['name'];
                $this->error_array = $_FILES['upload']['error'];
                //$this->replace = (isset($_POST['replace'])) ? $_POST['replace'] : "n"; // because only a checked checkboxes is true
                $this->upload_multi_files();
        }
}
?>
