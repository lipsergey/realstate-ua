<?php
/*
	ZipFunctions
	Class for make and modify zip files and extract files from zip
 	First version class - Joshua Townsend, 2009, January
 	Modernization and upgrade class - Lipsits Sergey, 2009
	Version 1.5
*/

class zipfunct {

	var $datasec = array(); // array to store compressed data
	var $files = array(); // array of uncompressed files
	var $dirs = array(); // array of directories that have been created already
	var $ctrl_dir = array(); // central directory
	var $eof_ctrl_dir = "\x50\x4b\x05\x06\x00\x00\x00\x00"; //end of Central directory record
	var $old_offset = 0;
	var $basedir = ".";

	function create_dir($name) { // Adds a directory to the zip with the name $name

		$name = str_replace("\\", "/", $name);
        $name = iconv('windows-1251', 'cp866', $name);

		$fr = "\x50\x4b\x03\x04";
		$fr .= "\x0a\x00";	// version needed to extract
		$fr .= "\x00\x00";	// general purpose bit flag
		$fr .= "\x00\x00";	// compression method
		$fr .= "\x00\x00\x00\x00"; // last mod time and date

		$fr .= pack("V",0); // crc32
		$fr .= pack("V",0); //compressed filesize
		$fr .= pack("V",0); //uncompressed filesize
		$fr .= pack("v",strlen($name)); //length of pathname
		$fr .= pack("v", 0); //extra field length
		$fr .= $name;
		// end of "local file header" segment

		// no "file data" segment for path

		// "data descriptor" segment (optional but necessary if archive is not served as file)
		$fr .= pack("V",0); //crc32
		$fr .= pack("V",0); //compressed filesize
		$fr .= pack("V",0); //uncompressed filesize

		// add this entry to array
		$this->datasec[] = $fr;

		$new_offset = strlen(implode("", $this->datasec));

		// ext. file attributes mirrors MS-DOS directory attr byte, detailed
		// at http://support.microsoft.com/support/kb/articles/Q125/0/19.asp

		// now add to central record
		$cdrec = "\x50\x4b\x01\x02";
		$cdrec .="\x00\x00";	// version made by
		$cdrec .="\x0a\x00";	// version needed to extract
		$cdrec .="\x00\x00";	// general purpose bit flag
		$cdrec .="\x00\x00";	// compression method
		$cdrec .="\x00\x00\x00\x00"; // last mod time and date
		$cdrec .= pack("V",0); // crc32
		$cdrec .= pack("V",0); //compressed filesize
		$cdrec .= pack("V",0); //uncompressed filesize
		$cdrec .= pack("v", strlen($name) ); //length of filename
		$cdrec .= pack("v", 0 ); //extra field length
		$cdrec .= pack("v", 0 ); //file comment length
		$cdrec .= pack("v", 0 ); //disk number start
		$cdrec .= pack("v", 0 ); //internal file attributes
		$cdrec .= pack("V", 16 ); //external file attributes  - 'directory' bit set

		$cdrec .= pack("V", $this->old_offset); //relative offset of local header
		$this->old_offset = $new_offset;

		$cdrec .= $name;
		// optional extra field, file comment goes here
		// save to array
		$this->ctrl_dir[] = $cdrec;
		$this->dirs[] = $name;
	}


	function create_file($data, $name, $time = 0) // Adds a file to the path specified by $name with the contents $data
	{
		$name = str_replace("\\", "/", $name);
        $name = iconv('windows-1251', 'cp866', $name);

        $dtime    = dechex($this->unix2DosTime($time));

        $hexdtime = '\x' . $dtime[6] . $dtime[7]
                  . '\x' . $dtime[4] . $dtime[5]
                  . '\x' . $dtime[2] . $dtime[3]
                  . '\x' . $dtime[0] . $dtime[1];
        eval('$hexdtime = "' . $hexdtime . '";');

		$fr = "\x50\x4b\x03\x04";
		$fr .= "\x14\x00";	// version needed to extract
		$fr .= "\x00\x00";	// general purpose bit flag
		$fr .= "\x08\x00";	// compression method
//		$fr .= "\x00\x00\x00\x00"; // last mod time and date
        $fr   .= $hexdtime;             // last mod time and date

		$unc_len = strlen($data);
		$crc = crc32($data);
		$zdata = gzcompress($data);
		$zdata = substr($zdata, 2, -4); // fix crc bug
		$c_len = strlen($zdata);
		$fr .= pack("V",$crc); // crc32
		$fr .= pack("V",$c_len); //compressed filesize
		$fr .= pack("V",$unc_len); //uncompressed filesize
		$fr .= pack("v", strlen($name) ); //length of filename
		$fr .= pack("v", 0 ); //extra field length
		$fr .= $name;
		// end of "local file header" segment

		// "file data" segment
		$fr .= $zdata;

		// "data descriptor" segment (optional but necessary if archive is not served as file)
		$fr .= pack("V",$crc); // crc32
		$fr .= pack("V",$c_len); // compressed filesize
		$fr .= pack("V",$unc_len); // uncompressed filesize

		// add this entry to array
		$this->datasec[] = $fr;

		$new_offset = strlen(implode("", $this->datasec));

		// now add to central directory record
		$cdrec = "\x50\x4b\x01\x02";
		$cdrec .="\x00\x00";	// version made by
		$cdrec .="\x14\x00";	// version needed to extract
		$cdrec .="\x00\x00";	// general purpose bit flag
		$cdrec .="\x08\x00";	// compression method
//		$cdrec .="\x00\x00\x00\x00"; // last mod time & date
		$cdrec .=$hexdtime;
		$cdrec .= pack("V",$crc); // crc32
		$cdrec .= pack("V",$c_len); //compressed filesize
		$cdrec .= pack("V",$unc_len); //uncompressed filesize
		$cdrec .= pack("v", strlen($name) ); //length of filename
		$cdrec .= pack("v", 0 ); //extra field length
		$cdrec .= pack("v", 0 ); //file comment length
		$cdrec .= pack("v", 0 ); //disk number start
		$cdrec .= pack("v", 0 ); //internal file attributes
		$cdrec .= pack("V", 32 ); //external file attributes - 'archive' bit set

		$cdrec .= pack("V", $this->old_offset); //relative offset of local header
		$this->old_offset = $new_offset;

		$cdrec .= $name;
		// optional extra field, file comment goes here
		// save to central directory
		$this->ctrl_dir[] = $cdrec;
	}

	function ClearFilesArr() {		$this->files = array();
	}

	function read_zip($name)
	{
		// Clear current file
		$this->datasec = array();

		// File information
		$this->name = $name;
		$this->mtime = filemtime($name);
		$this->size = filesize($name);

		// Read file
		$fh = fopen($name, "rb");
		$filedata = fread($fh, $this->size);
		fclose($fh);

		// Break into sections
		$filesecta = explode("\x50\x4b\x05\x06", $filedata);

		// ZIP Comment
		$unpackeda = unpack('x16/v1length', $filesecta[1]);
		$this->comment = substr($filesecta[1], 18, $unpackeda['length']);
		$this->comment = str_replace(array("\r\n", "\r"), "\n", $this->comment); // CR + LF and CR -> LF

		// Cut entries from the central directory
		$filesecta = explode("\x50\x4b\x01\x02", $filedata);
		$filesecta = explode("\x50\x4b\x03\x04", $filesecta[0]);
		array_shift($filesecta); // Removes empty entry/signature

		foreach($filesecta as $filedata)
		{
			// CRC:crc, FD:file date, FT: file time, CM: compression method, GPF: general purpose flag, VN: version needed, CS: compressed size, UCS: uncompressed size, FNL: filename length
			$entrya = array();
			$entrya['error'] = "";

			$unpackeda = unpack("v1version/v1general_purpose/v1compress_method/v1file_time/v1file_date/V1crc/V1size_compressed/V1size_uncompressed/v1filename_length", $filedata);

			// Check for encryption
			$isencrypted = (($unpackeda['general_purpose'] & 0x0001) ? true : false);

			// Check for value block after compressed data
			if($unpackeda['general_purpose'] & 0x0008)
			{
				$unpackeda2 = unpack("V1crc/V1size_compressed/V1size_uncompressed", substr($filedata, -12));

				$unpackeda['crc'] = $unpackeda2['crc'];
				$unpackeda['size_compressed'] = $unpackeda2['size_uncompressed'];
				$unpackeda['size_uncompressed'] = $unpackeda2['size_uncompressed'];

				unset($unpackeda2);
			}

			$entrya['name'] = substr($filedata, 26, $unpackeda['filename_length']);

			if(substr($entrya['name'], -1) == "/") // skip directories
			{
				continue;
			}

			$entrya['dir'] = dirname($entrya['name']);
			$entrya['dir'] = ($entrya['dir'] == "." ? "" : $entrya['dir']);
			$entrya['name'] = basename($entrya['name']);
			$entrya['name'] = iconv('cp866', 'windows-1251', $entrya['name']);


			$filedata = substr($filedata, 26 + $unpackeda['filename_length']);

			if(strlen($filedata) != $unpackeda['size_compressed'])
			{
				$entrya['error'] = "Compressed size is not equal to the value given in header.";
			}

			if($isencrypted)
			{
				$entrya['error'] = "Encryption is not supported.";
			}
			else
			{
				switch($unpackeda['compress_method'])
				{
					case 0: // Stored
						// Not compressed, continue
					break;
					case 8: // Deflated
						$filedata = gzinflate($filedata);
					break;
					case 12: // BZIP2
						if(!extension_loaded("bz2"))
						{
							@dl((strtolower(substr(PHP_OS, 0, 3)) == "win") ? "php_bz2.dll" : "bz2.so");
						}

						if(extension_loaded("bz2"))
						{
							$filedata = bzdecompress($filedata);
						}
						else
						{
							$entrya['error'] = "Required BZIP2 Extension not available.";
						}
					break;
					default:
						$entrya['error'] = "Compression method ({$unpackeda['compress_method']}) not supported.";
				}

				if(!$entrya['error'])
				{
					if($filedata === false)
					{
						$entrya['error'] = "Decompression failed.";
					}
					elseif(strlen($filedata) != $unpackeda['size_uncompressed'])
					{
						$entrya['error'] = "File size is not equal to the value given in header.";
					}
					elseif(crc32($filedata) != $unpackeda['crc'])
					{
						$entrya['error'] = "CRC32 checksum is not equal to the value given in header.";
					}
				}

				$entrya['filemtime'] = mktime(($unpackeda['file_time']  & 0xf800) >> 11,($unpackeda['file_time']  & 0x07e0) >>  5, ($unpackeda['file_time']  & 0x001f) <<  1, ($unpackeda['file_date']  & 0x01e0) >>  5, ($unpackeda['file_date']  & 0x001f), (($unpackeda['file_date'] & 0xfe00) >>  9) + 1980);
				$entrya['data'] = $filedata;
			}

			$this->files[] = $entrya;
		}

		return $this->files;
	}

	function add_file($file, $dir = ".", $file_blacklist = array(), $ext_blacklist = array()) {
		$file = str_replace("\\", "/", $file);
		$dir = str_replace("\\", "/", $dir);

		if(strpos($file, "/") !== false)
		{
			$dira = explode("/", "{$dir}/{$file}");
			$file = array_shift($dira);
			$dir = implode("/", $dira);
			unset($dira);
		}

		while(substr($dir, 0, 2) == "./")
		{
			$dir = substr($dir, 2);
		}
		while(substr($file, 0, 2) == "./")
		{
			$file = substr($file, 2);
		}
		if(!in_array($dir, $this->dirs))
		{
			if($dir == ".")
			{
				$this->create_dir("./");
			}
			$this->dirs[] = $dir;
		}
		if(in_array($file, $file_blacklist))
		{
			return true;
		}
		foreach($ext_blacklist as $ext)
		{
			if(substr($file, -1 - strlen($ext)) == ".{$ext}")
			{
				return true;
			}
		}

		$filepath = (($dir && $dir != ".") ? "{$dir}/" : "").$file;
		if(is_dir("{$this->basedir}/{$filepath}"))
		{
			$dh = opendir("{$this->basedir}/{$filepath}");
			while(($subfile = readdir($dh)) !== false)
			{
				if($subfile != "." && $subfile != "..")
				{
					$this->add_file($subfile, $filepath, $file_blacklist, $ext_blacklist);
				}
			}
			closedir($dh);
		}
		else
		{
			$this->create_file(implode("", file("{$this->basedir}/{$filepath}")), $filepath);
		}

		return true;
	}


	function zipped_file() // return zipped file contents
	{
		$data = implode("", $this->datasec);
		$ctrldir = implode("", $this->ctrl_dir);

		return $data.
				$ctrldir.
				$this->eof_ctrl_dir.
				pack("v", sizeof($this->ctrl_dir)). // total number of entries "on this disk"
				pack("v", sizeof($this->ctrl_dir)). // total number of entries overall
				pack("V", strlen($ctrldir)). // size of central dir
				pack("V", strlen($data)). // offset to start of central dir
				"\x00\x00"; // .zip file comment length
	}

	function enable_modify() {
		foreach($this->files as $fileinzip) {
			if($fileinzip['dir']!="") {$fileinzip['dir'].="/";}
			$filename =$fileinzip['dir'].$fileinzip['name'];
			$data =$fileinzip['data'];
			$this->create_file($data,$filename);
		}
	}

	function load_zip($file) {
		$this->read_zip($file);
		$this->enable_modify();
	}


    //Для корректного времени создания файла
    function unix2DosTime($unixtime = 0) {
        $timearray = ($unixtime == 0) ? getdate() : getdate($unixtime);

        if ($timearray['year'] < 1980) {
        	$timearray['year']    = 1980;
        	$timearray['mon']     = 1;
        	$timearray['mday']    = 1;
        	$timearray['hours']   = 0;
        	$timearray['minutes'] = 0;
        	$timearray['seconds'] = 0;
        } // end if

        return (($timearray['year'] - 1980) << 25) | ($timearray['mon'] << 21) | ($timearray['mday'] << 16) |
                ($timearray['hours'] << 11) | ($timearray['minutes'] << 5) | ($timearray['seconds'] >> 1);
    }

	/*
		Для множественного добавления файлов с сервера
		@Структура массива данных: Имя файла => Путь к файлу в архиве
		@Тип обработки русских названий 1 - транслитерация, 0 - CP866 (без изменений)
	*/

	function addMassFiles($_FilesToAdd, $_RussLett = 1) {		global $instances;		if (!is_array($_FilesToAdd)) {return false;}
		foreach ($_FilesToAdd as $_FileName => $_PathesToFile) {			$thisFile = pathinfo($_FileName);
			if (preg_match("/([а-яА-Я])/", $thisFile["basename"])) {				if ($_RussLett == 1) {					$FileName = $instances["textfunct"]->translite(strtolower($thisFile["basename"]));
				}
				else {					$FileName = $thisFile["basename"];
				}
			}
			else {				$FileName = $thisFile["basename"];
			}

			$Ftime = filemtime($_PathesToFile["nowpath"] . $_FileName);
			$FData = file_get_contents($_PathesToFile["nowpath"] . $_FileName);

			if (isset($_PathesToFile["archpath"]) && $_PathesToFile["archpath"] != "") {
				$FileName = $_PathesToFile["archpath"]."/".$FileName;
			}

			$this->create_file($FData, $FileName, $Ftime);
		}
	}

}
?>
