# php-class-directory-file-zip-unzip
PHP classes making everyday coding easier and comfortable. Static functions for simple access of common uses

## Required
PHP >= 5.6
Extension: [ZipArchive](https://www.php.net/manual/en/class.ziparchive.php)

## Start
Check string is json string
```
DirFile::isJson($input);
```
Check path exist
```
DirFile::pathExists($path);
```
Get info of path
```
DirFile::getInfo($path);
```
Get content from file
```
DirFile::getContentFromFile($path);
```
Get content and convert to json
```
DirFile::getContentJsonFromFile($path);
```
Put content into a file
```
DirFile::putContentIntoFile($path);
```
Delete a file
```
DirFile::deleteFile($path);
```
Get list File in Directory
```
Option:
GLOB_MARK - Adds a slash to each item returned
GLOB_NOSORT - Return files as they appear in the directory (unsorted)
GLOB_NOCHECK - Returns the search pattern if no match were found
GLOB_NOESCAPE - Backslashes do not quote metacharacters
GLOB_BRACE - Expands {a,b,c} to match 'a', 'b', or 'c'
GLOB_ONLYDIR - Return only directories which match the pattern
GLOB_ERR - (added in PHP 5.1) Stop on errors (errors are ignored by default)

DirFile::getListFileInDir($path, $option);
```
Delete directory
```
DirFile::deleteDir($path);
```
Copy file or directory to new location
```
DirFile::copyDir($srcPath, $dstPath);
```
Search file name in Directory
```
$exact : true is find match with name. False is dind relative with name

DirFile::searchFileInDirectory($path, $searchName, $exact);
```
Unzip file
```
DirFile::unzip($zipFilePath, $desDir);
```
Zip file or directory
```
DirFile::zip($pathDir, $dstAfterZip);
```
