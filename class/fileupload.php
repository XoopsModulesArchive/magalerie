<?php

// $Id: fileupload.php,v 1.1 2003/10/15 05:36:27 mikhail Exp $
// ------------------------------------------------------------------------- //
//               E-Xoops: Content Management for the Masses                  //
//                       < http://www.e-xoops.com >                          //
// ------------------------------------------------------------------------- //
// Original Author: Half-Dead
// Author Website : http://www.e-xoops.com
// Licence Type   : Proprietary
// ------------------------------------------------------------------------- //

if (!defined('EXOOPS_FILEUPLOAD_INCLUDED')) {
    define('EXOOPS_FILEUPLOAD_INCLUDED', 1);

    //---------------------------------------------------------------------------------------//

    /**
     * On submit page:
     * require_once __DIR__ . '/fileupload.php';
     * $upload = new fileupload();
     *
     * $upload->set_max_file_size(50, 'k', 'myfile');
     * $upload->render(1, 'myfield');
     * --------------------
     *
     * On upload page:
     * require_once __DIR__ . '/fileupload.php';
     * $upload = new fileupload();
     *
     * $upload->set_upload_dir('/myfolder', 'myfile');
     * $result = $upload->upload();
     * if ($result) {
     *    print_r($result['myfile']);
     * }
     * $upload->errors(1);
     * -----------------------
     *
     * Of course there are many other options. This is just a very basic sample.
     *
     * Note:
     * Filenames/Extensions are always converted to lowercase and may only contain the following NON
     * accentuated characters (A-Z a-z 0-9 _ . -) Anything else (including spaces) will be stripped!
     */

    class fileupload
    {
        // Variable vars

        public $file = [];

        public $errors = [];

        public $accepted = [];

        // This is a temporary var!

        public $max_file_size = 0;

        // Default vars

        public $def_overwrite = 0;

        public $def_max_image_width = 0;

        public $def_max_image_height = 0;

        public $def_max_file_size = 0;

        public $def_chmod = 0666;

        public $def_accepted = '.*';

        //---------------------------------------------------------------------------------------//

        /**
         * Sets the path to which the files should be uploaded to
         *
         * @param string $upload_dir Path to upload files to
         * @param mixed $filename
         */
        public function set_upload_dir($upload_dir, $filename = 'uploaded_files')
        {
            $upload_dir = str_replace('\\', '/', $upload_dir);

            if ('/' != mb_substr($upload_dir, -1)) {
                $this->file[$filename]['upload_dir'] = $upload_dir . '/';
            } else {
                $this->file[$filename]['upload_dir'] = $upload_dir;
            }
        }

        //---------------------------------------------------------------------------------------//

        /**
         * Set the maximum file size in bytes ($size), allowable by the object.
         *
         * @param int    $size Maximum file size.
         * @param string $type What $size refers to -> m: MegaBytes, k: KiloBytes, b: Bytes
         * @param mixed $filename
         */
        public function set_max_file_size($size = 0, $type = 'b', $filename = 'uploaded_files')
        {
            $type = mb_strtolower($type);

            switch ($type) {
                case 'k':
                case 'ko':
                case 'kb':
                    $size = ((int)$size * 1024);
                    $this->file[$filename]['max_file_size'] = $size;
                    break;
                case 'm':
                case 'mo':
                case 'mb':
                    $size = ((((int)$size * 1024) * 1024));
                    $this->file[$filename]['max_file_size'] = $size;
                    break;
                default:
                    $this->file[$filename]['max_file_size'] = $size;
                    break;
            }
        }

        //---------------------------------------------------------------------------------------//

        /**
         * Sets the maximum pixel height for image uploads, O means unlimited
         *
         * @param int $height Maximum pixel height of image uploads
         * @param mixed $filename
         */
        public function set_max_image_height($height = 0, $filename = 'uploaded_files')
        {
            if (is_numeric($height)) {
                $this->file[$filename]['max_image_height'] = $height;
            }
        }

        //---------------------------------------------------------------------------------------//

        /**
         * Sets the maximum pixel width for image uploads, O means unlimited
         *
         * @param int $width Maximum pixel width of image uploads
         * @param mixed $filename
         */
        public function set_max_image_width($width = 0, $filename = 'uploaded_files')
        {
            if (is_numeric($width)) {
                $this->file[$filename]['max_image_width'] = $width;
            }
        }

        //---------------------------------------------------------------------------------------//

        /**
         * Sets if the uploaded file should be cleaned up or not (only applies to text type files)
         *
         * @param mixed $value
         * @param mixed $filename
         */
        public function set_filecleanup($value = 0, $filename = 'uploaded_files')
        {
            $this->file[$filename]['filecleanup'] = (int)$value;
        }

        //---------------------------------------------------------------------------------------//

        /**
         * Cleans up & sets the base filename without the extension
         * Don't call this if uploading multiple files ..use $this->add_field() instead!
         * Only applies to 1st file if uploading multiple files
         *
         * @param string $value The base filename without any extension.
         * @param mixed $filename
         */
        public function set_basename($value, $filename = 'uploaded_files')
        {
            $this->file[$filename]['basename'] = mb_strtolower(preg_replace('/[^a-z0-9_.-]/i', '_', $value));
        }

        //---------------------------------------------------------------------------------------//

        /**
         * Sets if a extension should be forced on the file
         * Only use this if you realy need to force a extension!
         * Only applies to 1st file if uploading multiple files
         *
         * @param string $value File extension including .
         * @param mixed $filename
         */
        public function set_extension($value, $filename = 'uploaded_files')
        {
            $this->file[$filename]['extension'] = $this->fixed_extension($value);
        }

        //---------------------------------------------------------------------------------------//

        /**
         * Sets the chmod value, if any that should be applied to uploaded files
         *
         * @param int $value Numric value to chmod the file to
         * @param mixed $filename
         */
        public function set_chmod($value = 0666, $filename = 'uploaded_files')
        {
            if (is_numeric($value)) {
                $this->file[$filename]['chmod'] = $value;
            }
        }

        //---------------------------------------------------------------------------------------//

        /**
         * Sets allowable file extensions
         *
         * @param string $value A list of accepted extensions delimited by | (ie .gif|.png)
         * @param mixed $filename
         */
        public function set_accepted($value = '.*', $filename = 'uploaded_files')
        {
            $accepted = explode('|', $value);

            $size = count($accepted);

            for ($i = 0; $i < $size; $i++) {
                $accepted[$i] = $this->fixed_extension($accepted[$i]);
            }

            $this->accepted[$filename] = $accepted;

            $this->file[$filename]['accepted'] = implode('|', $accepted);
        }

        //---------------------------------------------------------------------------------------//

        /**
         * Description
         *
         * @param int   $value Set the file overwrite mode
         *                     0: Do nothing if exists
         *                     1: Make copy
         *                     2: Overwrite file
         * @param mixed $filename
         * @return void description
         */
        public function set_overwrite($value = 0, $filename = 'uploaded_files')
        {
            if (is_numeric($value)) {
                $this->file[$filename]['overwrite'] = $value;
            }
        }

        //---------------------------------------------------------------------------------------//

        // END CONFIGURATION OPTIONS

        //---------------------------------------------------------------------------------------//

        /**
         * The upload function called to start processing the whole upload process
         *
         * @return false An array of all succesfully uploaded files if any, with various information on each file
         */
        public function upload()
        {
            global $_POST, $HTTP_POST_FILES;

            if (!empty($HTTP_POST_FILES)) {
                foreach ($HTTP_POST_FILES as $filename => $value) {
                    if (!empty($value['size'])) {
                        $this->file[$filename]['name'] = $value['name'];

                        $this->file[$filename]['type'] = $value['type'];

                        $this->file[$filename]['size'] = $value['size'];

                        $this->file[$filename]['tmp_name'] = $value['tmp_name'];

                        $result = $this->process_file($filename);

                        if ($result) {
                            $files[$filename]['realname'] = $this->file[$filename]['name'];

                            $files[$filename]['filename'] = $this->file[$filename]['basename'] . $this->file[$filename]['extension'];

                            $files[$filename]['basename'] = $this->file[$filename]['basename'];

                            $files[$filename]['extension'] = $this->file[$filename]['extension'];

                            $files[$filename]['fullpath'] = $this->file[$filename]['fullpath'];

                            $files[$filename]['baseurl'] = $this->file[$filename]['baseurl'];

                            $files[$filename]['fullurl'] = $this->file[$filename]['fullurl'];

                            $files[$filename]['type'] = $this->file[$filename]['type'];

                            $files[$filename]['size'] = $this->file[$filename]['size'];
                        }

                        unset($this->file[$filename]);
                    } elseif ($value['name']) {
                        $error = sprintf(_ULC_SIZE, $value['name'], $_POST['MAX_FILE_SIZE']);

                        $this->errors[] = $error;
                    }
                }

                return $files;
            }

            $this->errors[] = _ULC_FILE;

            return (false);
        }

        //---------------------------------------------------------------------------------------//

        /**
         * Figures out filename/extension and passes it to the copy function
         *
         * @param mixed $filename
         * @return bool TRUE/FALSE depending on success or not
         */
        public function process_file($filename)
        {
            global $_POST;

            // See if this file has options assigned, otherwise initialize default settings.

            if (!isset($this->file[$filename]['upload_dir'])) {
                switch (true) {
                    case isset($_POST[$filename]['upload_dir']):
                        $this->set_upload_dir($_POST[$filename]['upload_dir'], $filename);
                        break;
                    case isset($this->file['uploaded_files']['upload_dir']):
                        $this->set_upload_dir($this->file['uploaded_files']['upload_dir'], $filename);
                        break;
                    case isset($_POST['uploaded_files']['upload_dir']):
                        $this->set_upload_dir($_POST['uploaded_files']['upload_dir'], $filename);
                        break;
                    default:
                        $error          = sprintf(_ULC_NPATH, $this->file[$filename]['name']);
                        $this->errors[] = $error;

                        return (false);
                        break;
                }
            }

            if (@!is_dir($this->file[$filename]['upload_dir']) || @!is_writable($this->file[$filename]['upload_dir'])) {
                $error = sprintf(_ULC_UDIR, $this->file[$filename]['upload_dir']);

                $this->errors[] = $error;

                return (false);
            }

            if (!isset($this->file[$filename]['accepted'])) {
                switch (true) {
                    case isset($_POST[$filename]['accepted']):
                        $this->set_accepted($_POST[$filename]['accepted'], $filename);
                        break;
                    case isset($this->file['uploaded_files']['accepted']):
                        $this->set_accepted($this->file['uploaded_files']['accepted'], $filename);
                        break;
                    case isset($_POST['uploaded_files']['accepted']):
                        $this->set_accepted($_POST['uploaded_files']['accepted'], $filename);
                        break;
                    default:
                        $this->set_accepted($this->def_accepted, $filename);
                        break;
                }
            }

            if (!isset($this->file[$filename]['max_image_height'])) {
                switch (true) {
                    case isset($_POST[$filename]['max_image_height']):
                        $this->set_max_image_height($_POST[$filename]['max_image_height'], $filename);
                        break;
                    case isset($this->file['uploaded_files']['max_image_height']):
                        $this->set_max_image_height($this->file['uploaded_files']['max_image_height'], $filename);
                        break;
                    case isset($_POST['uploaded_files']['max_image_height']):
                        $this->set_max_image_height($_POST['uploaded_files']['max_image_height'], $filename);
                        break;
                    default:
                        $this->set_max_image_height($this->def_max_image_height, $filename);
                        break;
                }
            }

            if (!isset($this->file[$filename]['max_image_width'])) {
                switch (true) {
                    case isset($_POST[$filename]['max_image_width']):
                        $this->set_max_image_width($_POST[$filename]['max_image_width'], $filename);
                        break;
                    case isset($this->file['uploaded_files']['max_image_width']):
                        $this->set_max_image_width($this->file['uploaded_files']['max_image_width'], $filename);
                        break;
                    case isset($_POST['uploaded_files']['max_image_width']):
                        $this->set_max_image_width($_POST['uploaded_files']['max_image_width'], $filename);
                        break;
                    default:
                        $this->set_max_image_width($this->def_max_image_width, $filename);
                        break;
                }
            }

            if (!isset($this->file[$filename]['max_file_size'])) {
                switch (true) {
                    case isset($_POST[$filename]['max_file_size']):
                        $this->set_max_file_size($_POST[$filename]['max_file_size'], $filename);
                        break;
                    case isset($this->file['uploaded_files']['max_file_size']):
                        $this->set_max_file_size($this->file['uploaded_files']['max_file_size'], $filename);
                        break;
                    case isset($_POST['uploaded_files']['max_file_size']):
                        $this->set_max_file_size($_POST['uploaded_files']['max_file_size'], $filename);
                        break;
                    default:
                        $this->set_max_file_size($this->def_max_file_size, $filename);
                        break;
                }
            }

            if (!isset($this->file[$filename]['overwrite'])) {
                switch (true) {
                    case isset($_POST[$filename]['overwrite']):
                        $this->set_overwrite($_POST[$filename]['overwrite'], $filename);
                        break;
                    case isset($this->file['uploaded_files']['overwrite']):
                        $this->set_overwrite($this->file['uploaded_files']['overwrite'], $filename);
                        break;
                    case isset($_POST['uploaded_files']['overwrite']):
                        $this->set_overwrite($_POST['uploaded_files']['overwrite'], $filename);
                        break;
                    default:
                        $this->set_overwrite($this->def_overwrite, $filename);
                        break;
                }
            }

            if (!isset($this->file[$filename]['chmod'])) {
                switch (true) {
                    case isset($_POST[$filename]['chmod']):
                        $this->set_chmod($_POST[$filename]['chmod'], $filename);
                        break;
                    case isset($this->file['uploaded_files']['chmod']):
                        $this->set_chmod($this->file['uploaded_files']['chmod'], $filename);
                        break;
                    case isset($_POST['uploaded_files']['chmod']):
                        $this->set_chmod($_POST['uploaded_files']['chmod'], $filename);
                        break;
                    default:
                        $this->set_chmod($this->def_chmod, $filename);
                        break;
                }
            }

            if (!isset($this->file[$filename]['filecleanup'])) {
                switch (true) {
                    case isset($_POST[$filename]['filecleanup']):
                        $this->set_filecleanup($_POST[$filename]['filecleanup'], $filename);
                        break;
                    case isset($this->file['uploaded_files']['filecleanup']):
                        $this->set_filecleanup($this->file['uploaded_files']['filecleanup'], $filename);
                        break;
                    case isset($_POST['uploaded_files']['filecleanup']):
                        $this->set_filecleanup($_POST['uploaded_files']['filecleanup'], $filename);
                        break;
                }
            }

            if (!isset($this->file[$filename]['extension'])) {
                switch (true) {
                    case isset($_POST[$filename]['extension']):
                        $this->set_extension($_POST[$filename]['extension'], $filename);
                        break;
                    case isset($this->file['uploaded_files']['extension']):
                        $this->set_extension($this->file['uploaded_files']['extension'], $filename);
                        break;
                    case isset($_POST['uploaded_files']['extension']):
                        $this->set_extension($_POST['uploaded_files']['extension'], $filename);
                        break;
                }
            }

            if (!isset($this->file[$filename]['basename'])) {
                switch (true) {
                    case isset($_POST[$filename]['basename']):
                        $this->set_basename($_POST[$filename]['basename'], $filename);
                        break;
                    case isset($this->file['uploaded_files']['basename']):
                        $this->set_basename($this->file['uploaded_files']['basename'], $filename);
                        break;
                    case isset($_POST['uploaded_files']['basename']):
                        $this->set_basename($_POST['uploaded_files']['basename'], $filename);
                        break;
                }
            }

            //---------------------------------------------------------------------------------------//

            // Now start processing the file

            if (!empty($this->file[$filename]['max_filesize']) && ($this->file[$filename]['size'] > $this->file[$filename]['max_file_size'])) {
                $error = sprintf(_ULC_SIZE, $this->file[$filename]['name'], $this->file[$filename]['max_file_size']);

                $this->errors[] = $error;

                return (false);
            }

            if (preg_match("'^image/'i", $this->file[$filename]['type'])) {
                if (!$image = @getimagesize($this->file[$filename]['tmp_name'])) {
                    $this->errors[] = _ULC_NIMG;

                    //return(FALSE);
                }

                if (($this->file[$filename]['max_image_width'] && ($image[0] > $this->file[$filename]['max_image_width'])) || ($this->file[$filename]['max_image_height'] && ($image[1] > $this->file[$filename]['max_image_height']))) {
                    $error = sprintf(_ULC_IMG, $this->file[$filename]['max_image_width'], $this->file[$filename]['max_image_height']);

                    $this->errors[] = $error;

                    return (false);
                }

                if (!isset($this->file[$filename]['extension'])) {
                    switch ($image[2]) {
                        case 1:
                            $this->set_extension('.gif', $filename);
                            break;
                        case 2:
                            $this->set_extension('.jpg', $filename);
                            break;
                        case 3:
                            $this->set_extension('.png', $filename);
                            break;
                        case 4:
                            $this->set_extension('.swf', $filename);
                            break;
                        case 5:
                            $this->set_extension('.psd', $filename);
                            break;
                        case 6:
                            $this->set_extension('.bmp', $filename);
                            break;
                        case 7:
                            $this->set_extension('.tif', $filename);
                            break;
                        case 8:
                            $this->set_extension('.tif', $filename);
                            break;
                        case 9:
                            $this->set_extension('.jpc', $filename);
                            break;
                        case 10:
                            $this->set_extension('.jp2', $filename);
                            break;
                        case 11:
                            $this->set_extension('.jpx', $filename);
                            break;
                        case 12:
                            $this->set_extension('.jb2', $filename);
                            break;
                        case 13:
                            $this->set_extension('.swc', $filename);
                            break;
                        case 14:
                            $this->set_extension('.iff', $filename);
                            break;
                    }
                }
            }

            if (!isset($this->file[$filename]['extension'])) {
                $this->check_mimes($filename);
            }

            if (!isset($this->file[$filename]['extension']) && preg_match("'((\.[a-z0-9]{2,3})?(\.[a-z0-9]{2,7}))$'i", $this->file[$filename]['name'], $matches)) {
                $this->set_extension($matches[0], $filename);
            }

            if (!isset($this->file[$filename]['extension'])) {
                $this->errors[] = _ULC_EXT;

                return (false);
            }

            if (in_array('.*', $this->accepted[$filename], true) || in_array($this->file[$filename]['extension'], $this->accepted[$filename], true)) {
                if (!isset($this->file[$filename]['basename'])) {
                    $this->set_basename(mb_substr($this->file[$filename]['name'], 0, -mb_strlen($this->file[$filename]['extension'])), $filename);
                }

                return $this->save_file($filename);
            }

            foreach ($this->accepted[$filename] as $mimetype => $extension) {
                $accepted .= ' ' . $extension . ' ';
            }

            $error = sprintf(_ULC_ONLY, $accepted);

            $this->errors[] = $error;

            return (false);
        }

        //---------------------------------------------------------------------------------------//

        /**
         * Copies the actual file
         *
         * @param mixed $filename
         * @return bool TRUE/FALSE depending if saving file succeeds or not
         */
        public function save_file($filename)
        {
            if (function_exists('is_uploaded_file')) {
                if (!is_uploaded_file($this->file[$filename]['tmp_name'])) {
                    $error = sprintf(_ULC_COPY, $this->file[$filename]['upload_dir'] . $this->file[$filename]['basename'] . $this->file[$filename]['extension']);

                    $this->errors[] = $error;

                    return (false);
                }
            }

            if (function_exists('move_uploaded_file')) {
                $copymode = 'move_uploaded_file';
            } else {
                $copymode = 'copy';
            }

            switch ($this->file[$filename]['overwrite']) {
                case '1':
                    unset($suffix);
                    while (@file_exists($this->file[$filename]['upload_dir'] . $this->file[$filename]['basename'] . $suffix . $this->file[$filename]['extension'])) {
                        $i++;

                        $suffix = '_' . $i;
                    }
                    $this->set_basename($this->file[$filename]['basename'] . $suffix, $filename);
                    break;
                case '2':
                    if (@file_exists($this->file[$filename]['upload_dir'] . $this->file[$filename]['basename'] . $this->file[$filename]['extension']) && @!is_writable($this->file[$filename]['upload_dir'] . $this->file[$filename]['basename'] . $this->file[$filename]['extension'])) {
                        $error = sprintf(_ULC_EXISTW, $this->file[$filename]['upload_dir'] . $this->file[$filename]['basename'] . $this->file[$filename]['extension']);

                        $this->errors[] = $error;

                        return (false);
                    }
                    break;
                default:
                    if (@file_exists($this->file[$filename]['upload_dir'] . $this->file[$filename]['basename'] . $this->file[$filename]['extension'])) {
                        $error = sprintf(_ULC_EXISTS, $this->file[$filename]['upload_dir'] . $this->file[$filename]['basename'] . $this->file[$filename]['extension']);

                        $this->errors[] = $error;

                        return (false);
                    }
                    break;
            }

            $uploaded = $copymode($this->file[$filename]['tmp_name'], $this->file[$filename]['upload_dir'] . $this->file[$filename]['basename'] . $this->file[$filename]['extension']);

            if ($uploaded) {
                @chmod($this->file[$filename]['upload_dir'] . $this->file[$filename]['basename'] . $this->file[$filename]['extension'], $this->file[$filename]['chmod']);

                $this->setPaths($filename);

                if (!empty($this->file[$filename]['filecleanup']) && !preg_match("'^(audio|image|video)/'i", $this->file[$filename]['type'])) {
                    return $this->cleanup_file($filename);
                }

                return (true);
            }

            $error = sprintf(_ULC_COPY, $this->file[$filename]['upload_dir'] . $this->file[$filename]['basename'] . $this->file[$filename]['extension']);

            $this->errors[] = $error;

            return (false);
        }

        //---------------------------------------------------------------------------------------//

        /**
         * Convert Mac/PC/UNIX text files
         * If windows is auto-detected then conversion mode will be forced to win32
         *
         * @param mixed $filename
         * @return bool TRUE/FALSE depending on if conversion succeeded or not
         */
        public function cleanup_file($filename)
        {
            // chr(13)         : CR (\r)     : Mac

            // chr(10)         : LF (\n)     : Unix

            // chr(13)+chr(10) : CRLF (\r\n) : Win32

            if (@is_file($this->file[$filename]['upload_dir'] . $this->file[$filename]['basename'] . $this->file[$filename]['extension']) && @is_writable($this->file[$filename]['upload_dir'] . $this->file[$filename]['basename'] . $this->file[$filename]['extension'])) {
                $contents = implode('', file($this->file[$filename]['upload_dir'] . $this->file[$filename]['basename'] . $this->file[$filename]['extension']));

                if ('' == trim($contents)) {
                    $error = sprintf(_ULC_EMPTY, $this->file[$filename]['upload_dir'] . $this->file[$filename]['basename'] . $this->file[$filename]['extension']);

                    $this->errors[] = $error;

                    return (false);
                }

                switch (_OS) {
                        case 'W':
                            $replacement = "\r\n";
                            break;
                        case 'M':
                            $replacement = "\r";
                            break;
                        default:
                            $replacement = "\n";
                    }

                if (mb_strstr($contents, "\r\n") && (_OS != 'W')) {
                    $contents = str_replace("\r\n", $replacement, $contents);
                } elseif (mb_strstr($contents, "\r") && (_OS != 'M')) {
                    $contents = str_replace("\r", $replacement, $contents);
                } else {
                    return (true);
                }

                if ($fp = fopen($this->file[$filename]['upload_dir'] . $this->file[$filename]['basename'] . $this->file[$filename]['extension'], 'wb')) {
                    if (-1 == fwrite($fp, trim($contents))) {
                        $error = sprintf(_ULC_WRITE, $this->file[$filename]['upload_dir'] . $this->file[$filename]['basename'] . $this->file[$filename]['extension']);

                        $this->errors[] = $error;

                        fclose($fp);

                        return (false);
                    }

                    fclose($fp);

                    echo $contents;

                    return (true);
                }

                $error = sprintf(_ULC_OPEN, $this->file[$filename]['upload_dir'] . $this->file[$filename]['basename'] . $this->file[$filename]['extension']);

                $this->errors[] = $error;

                return (false);
            }

            $error = sprintf(_ULC_NEXIST, $this->file[$filename]['upload_dir'] . $this->file[$filename]['basename'] . $this->file[$filename]['extension']);

            $this->errors[] = $error;

            return (false);
        }

        //---------------------------------------------------------------------------------------//

        /**
         * Makes sure extensions are lowercase & preceeded with a period
         *
         * @param string $value Extension to test
         * @return string Correct extension if any
         */
        public function fixed_extension($value)
        {
            if (preg_match("'[a-z0-9.*]'i", $value)) {
                if ('.' != mb_substr($value, 0, 1)) {
                    $value = '.' . $value;
                }

                return mb_strtolower($value);
            }
        }

        //---------------------------------------------------------------------------------------//

        /**
         * Sets the real path, relative url, and full url of a uploaded file
         * Like this ../ ./ etc will get worked out to their full paths.
         *
         * @param type $filename The current filename we're treating
         */
        public function setPaths($filename)
        {
            $root_path = @realpath(XOOPS_ROOT_PATH);

            $root_path = str_replace('\\', '/', $root_path);

            $upload_dir = @realpath($this->file[$filename]['upload_dir']);

            $upload_dir = str_replace('\\', '/', $upload_dir);

            $base_path = preg_replace("'$root_path'i", '', $upload_dir . '/');

            $full_url = parse_url(XOOPS_URL);

            $this->file[$filename]['fullpath'] = $root_path . $base_path;

            $this->file[$filename]['baseurl'] = $full_url['path'] . $base_path;

            $this->file[$filename]['fullurl'] = XOOPS_URL . $base_path;
        }

        //---------------------------------------------------------------------------------------//

        /**
         * Tries to figure out the file extension by comparing mime types
         * Some mime types can have multiple extension types, if this is the case
         * then it will check if the files extension matches one in the array.
         *
         * Feel free to extend this function with custom mime types/extensions
         *
         * @param mixed $filename
         */
        public function check_mimes($filename)
        {
            $mimes = [
                'application/msword' => '.doc',
'application/octet-stream' => [
                    0 => '.php',
1 => '.ini',
2 => '.bin',
3 => '.lha',
4 => '.lzh',
5 => '.exe',
6 => '.class',
7 => '.so',
8 => '.dll',
                ],
'application/pdf' => '.pdf',
'application/postscript' => '.ps',
'application/vnd.ms-excel' => '.xls',
'application/vnd.ms-powerpoint' => '.ppt',
'application/x-gzip' => '.gz',
'application/x-gzip-compressed' => '.tar.gz',
'application/x-javascript' => '.js',
'application/x-latex' => '.latex',
'application/x-sh' => '.sh',
'application/x-shockwave-flash' => '.swf',
'application/x-stuffit' => '.sit',
'application/x-tar' => '.tar',
'application/x-tcl' => '.tcl',
'application/x-tex' => '.tex',
'application/x-texinfo' => '.texinfo',
'application/x-troff-man' => '.man',
'application/x-ustar' => '.ustar',
'application/zip' => '.zip',
'application/x-zip-compressed' => '.zip',
'audio/basic' => '.au',
'audio/midi' => '.mid',
'audio/mpeg' => '.mp3',
'audio/x-aiff' => '.aiff',
'audio/x-pn-realaudio' => '.ram',
'audio/x-pn-realaudio-plugin' => '.rpm',
'audio/x-realaudio' => '.ra',
'audio/x-wav' => '.wav',
'audio/wav' => '.wav',
'image/bmp' => '.bmp',
'image/gif' => '.gif',
'image/ief' => '.ief',
'image/jpeg' => '.jpg',
'image/pjpeg' => '.jpg',
'image/png' => '.png',
'image/x-png' => '.png',
'image/tiff' => '.tiff',
'image/vnd.wap.wbmp' => '.wbmp',
'image/x-portable-anymap' => '.pnm',
'image/x-portable-bitmap' => '.pbm',
'image/x-portable-graymap' => '.pgm',
'image/x-portable-pixmap' => '.ppm',
'image/x-xbitmap' => '.xbm',
'image/x-xpixmap' => '.xpm',
'model/vrml' => '.vrml',
'text/css' => '.css',
'text/html' => '.html',
'text/plain' => [
                    0 => '.txt',
1 => '.php',
                ],
'text/richtext' => '.rtf',
'text/rtf' => '.rtf',
'text/sgml' => '.sgml',
'text/vnd.wap.wml' => '.wml',
'text/vnd.wap.wmlscript' => '.wmls',
'text/xml' => [
                    0 => '.xml',
1 => '.xsl',
2 => '.html',
3 => '.htm',
4 => '.shtml',
                ],
'video/mpeg' => '.mpeg',
'video/quicktime' => [
                    0 => '.mov',
1 => '.qt',
                ],
'video/x-msvideo' => '.avi',
'video/x-sgi-movie' => '.movie',
            ];

            foreach ($mimes as $type => $extension) {
                if ($this->file[$filename]['type'] == $type) {
                    if (is_array($extension)) {
                        foreach ($extension as $ext) {
                            if (preg_match("'" . preg_quote($ext) . "$'i", $this->file[$filename]['name'])) {
                                $this->set_extension($ext, $filename);

                                break;
                            }
                        }
                    } else {
                        $this->set_extension($extension, $filename);

                        break;
                    }
                }
            }
        }

        //---------------------------------------------------------------------------------------//

        /**
         * Returns all errors if any
         *
         * @param mixed $style
         * @param int   $html If TRUE, then errors are in html format, otherwise in plaintext
         * @return string HTML/TEXT formated errors if any
         */
        public function errors($style = 0, $html = 1)
        {
            $size = count($this->errors);

            if ($size > 0) {
                for ($i = 0; $i < $size; $i++) {
                    $errors .= ($i + 1) . ': ' . $this->errors[$i];

                    if (1 == $html) {
                        $errors .= '<br>';
                    } else {
                        $errors .= "\n";
                    }
                }

                if (0 == $style) {
                    return $errors;
                }

                echo $errors;
            } else {
                return (false);
            }
        }

        //---------------------------------------------------------------------------------------//

        /**
         * Description
         *
         * @param mixed $style
         * @param mixed $field
         * @return type description
         */
        public function render($style = 0, $field = '')
        {
            switch (true) {
                case (('' == $field) && !empty($this->file)):
                    $render = $this->file;
                    break;
                case (('' != $field) && ('uploaded_files' != $field) && isset($this->file[$field])):
                    $render[$field] = $this->file[$field];
                    break;
                case (('' != $field) && ('uploaded_files' != $field) && !isset($this->file[$field])):
                    $this->file[$field] = '';
                    $render[$field] = $this->file[$field];
                    break;
                default:
                    return;
            }

            $size = count($render);

            foreach ($render as $filename => $options) {
                if ($render[$filename]['max_file_size'] > $this->max_file_size) {
                    $this->max_file_size = $render[$filename]['max_file_size'];
                }

                if ((1 == $size) || ('uploaded_files' != $filename)) {
                    $ret .= '<input type="file" class="file" id="' . $filename . '" name="' . $filename . '">';
                }

                if (is_array($options)) {
                    foreach ($options as $option => $value) {
                        $ret .= '<input type="hidden" id="' . $filename . '[' . $option . ']" name="' . $filename . '[' . $option . ']" value="' . $value . '">';
                    }
                }
            }

            if (!empty($this->max_file_size)) {
                $max = '<input type="hidden" id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" value="' . $this->max_file_size . '">';
            }

            if (0 == $style) {
                return ($max . $ret);
            }

            echo($max . $ret);
        }

        // ------------------------------------------------------------------------- //
    } // END CLASS FILEUPLOAD

    // ------------------------------------------------------------------------- //
}
