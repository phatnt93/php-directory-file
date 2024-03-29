<?php 

/** 
* DirFile
* 
* Made by phatnt93
* 02/04/2020
* 
* @license MIT License
* @author phatnt <thanhphat.uit@gmail.com>
* @github https://github.com/phatnt93/php-directory-file
* @version 1.0.1
* 
*/

class DirFile{
    
    function __construct(){

    }

    // Check string is json
    public static function isJson($string) {
        json_decode($string);
         return (json_last_error() == JSON_ERROR_NONE);
    }

    // Check file exists
    public static function pathExists($path){
        return file_exists($path);
    }
    
    // Get information of file
    public static function getInfo($path){
        if(self::pathExists($path) == false){
            return false;
        }
        return pathinfo($path);
    }

    //////////
    // FILE //
    //////////
    
    // Get content from the file
    public static function getContentFromFile($path){
        if(self::pathExists($path) == false){
            return false;
        }
        return file_get_contents($path);
    }

    // Get JSON content from the file
    public static function getContentJsonFromFile($path){
        $content = self::getContentFromFile($path);
        if($content == false || self::isJson($content) == false){
            return false;
        }
        return json_decode($content);
    }

    // Put content into the file
    public static function putContentIntoFile($path, $content = ''){
        if(self::pathExists($path) == false){
            return false;
        }
        if(file_put_contents($path, $content) == false){
            return false;
        }
        return true;
    }

    // Delete file
    public static function deleteFile($path){
        if(self::pathExists($path) == false){
            return false;
        }
        return unlink($path);
    }

    // Copy file
    public static function copyFile($srcPath, $dstDir){
        try {
            if(!file_exists($srcPath)){
                throw_error_message('error');
            }
            $srcBaseName = pathinfo($srcPath, PATHINFO_BASENAME);
            if(!file_exists($dstDir)){
                if(!mkdir($dstDir, 0777, true)){
                    throw_error_message('error');
                }
            }
            $dstPath = $dstDir . DIRECTORY_SEPARATOR . $srcBaseName;
            if(!copy($srcPath, $dstPath)){
                throw_error_message('error');
            }
            return true;
        } catch (\Exception $e){
            
        }
        return false;
    }

    // Move file
    public static function moveFile($srcPath, $dstDir){
        try {
            if(!file_exists($srcPath)){
                throw_error_message('error');
            }
            $srcBaseName = pathinfo($srcPath, PATHINFO_BASENAME);
            if(!file_exists($dstDir)){
                if(!mkdir($dstDir, 0777, true)){
                    throw_error_message('error');
                }
            }
            $dstPath = $dstDir . DIRECTORY_SEPARATOR . $srcBaseName;
            if(!rename($srcPath, $dstPath)){
                throw_error_message('error');
            }
            return true;
        } catch (\Exception $e){
            
        }
        return false;
    }

    /**
     * Delete file after time
     *
     * @param string $path
     * @param integer $expire
     * @return bool
     */
    public static function deleteFileAfterTime($path = '', $expire = 3600){
        if (!file_exists($path)) {
            return true;
        }
        $now = time();
        $fileTime = filemtime($path);
        if (($now - $fileTime) > $expire) {
            self::deleteFile($path);
        }
		return true;
    }

    /**
     * Delete files in directory
     *
     * @param string $dirPath
     * @param integer $expire
     * @return void
     */
    public static function deleteFilesAfterTime($dirPath = '', $expire = 3600){
        $filePaths = glob($dirPath);
        foreach ($filePaths as $kfp => $vfp) {
            self::deleteFileAfterTime($vfp, $expire);
        }
		return true;
    }

    ///////////////
    // DIRECTORY //
    ///////////////
    
    // Get list file in dir
    public static function getListFileInDir($pathDir, $options = null){
        if($options == null){
            $list = glob($pathDir);
        }else{
            $list = glob($pathDir, $options);
        }
        if($list == false){
            $list = [];
        }
        return $list;
    }
    
    // Delete dir
    public static function deleteDir($pathDir) {
        if (!file_exists($pathDir)) {
            return true;
        }
        if (!is_dir($pathDir)) {
            return unlink($pathDir);
        }
        foreach (scandir($pathDir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            if (!self::deleteDir($pathDir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }
        return rmdir($pathDir);
    }

    /**
     * Delete directory after time
     *
     * @param string $pathDir
     * @param integer $expire
     * @return bool
     */
	public static function deleteDirAfterTime($pathDir = '', $expire = 3600){
        if (!file_exists($pathDir)) {
            return true;
        }
        $now = time();
        $fileTime = filemtime($pathDir);
        if (($now - $fileTime) > $expire) {
            self::deleteDir($pathDir);
        }
		return true;
    }

    // Copy dir
    public static function copyDir($src, $dst) { 
        $dir = opendir($src); 
        @mkdir($dst); 
        while(false !== ( $file = readdir($dir)) ) { 
            if (( $file != '.' ) && ( $file != '..' )) { 
                if ( is_dir($src . '/' . $file) ) { 
                    self::copyDir($src . '/' . $file,$dst . '/' . $file); 
                } 
                else { 
                    copy($src . '/' . $file,$dst . '/' . $file); 
                } 
            } 
        } 
        closedir($dir); 
    }
    
    // Search File In Directory
    function searchFileInDirectory($path = '', $searchName = '', $exact = true, &$output = []){
        $list = scandir($path);
        foreach ($list as $key => $item) {
            if ($item != '.' && $item != '..') {
                if (is_dir($path . '/' . $item)) {
                    self::searchFileInDirectory($path . '/' . $item, $searchName, $exact, $output);
                }else{
                    if ($exact == true) {
                        if ($item == $searchName) {
                            $output[] = $path . '/' . $item;
                        }
                    }else{
                        if (strpos($item, $searchName) !== false) {
                            $output[] = $path . '/' . $item;
                        }
                    }
                }
            }
        }
        return $output;
    }

    /////////////////
    // Zip & Unzip //
    /////////////////
    /**
     * Must be install php-zip
     */

    public static function unzip($zipFilePath = '', $desDir = ''){
        $zip = new \ZipArchive();
        $res = $zip->open($zipFilePath);
        if ($res === TRUE) {
            $zip->extractTo($desDir);
            $zip->close();
            return true;
        } else {
            return false;
        }
    }

    public static function zip($pathDir, $pathZipFile = null){
        if(self::pathExists($pathDir) == false){
            return false;
        }
        if($pathZipFile == null || $pathZipFile == ''){
            return false;
        }
        // Get real path for our folder
        $rootPath = realpath($pathDir);
        // Initialize archive object
        $zip = new \ZipArchive();
        $zip->open($pathZipFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        // Create recursive directory iterator
        /** @var SplFileInfo[] $files */
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($rootPath),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );
        foreach ($files as $name => $file){
            // Skip directories (they would be added automatically)
            if (!$file->isDir())
            {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);
                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            }
        }
        // Zip archive will be created only after closing object
        $zip->close();
        return true;
    }

}
