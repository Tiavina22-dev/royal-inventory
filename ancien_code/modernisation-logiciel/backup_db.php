<?php
###############################################################################
#############BACKUP ALL FILE NEEDED###############
##########COPY 3 WAMP FILE IMPORTANT CONFIG #############
/*
copy('C:/wamp64/bin/apache/apache2.4.33/conf/httpd.conf', 'D:/BACKUP/Wamp_File/1_httpd.conf/'.date('Y-m-d_H-i-s').'_httpd.conf');

copy('C:/wamp64/bin/apache/apache2.4.33/conf/extra/httpd-vhosts.conf', 'D:/BACKUP/Wamp_File/2_httpd_vhosts.conf/'.date('Y-m-d_H-i-s').'_httpd-vhosts.conf');

copy('C:/wamp64/alias/phpmyadmin.conf', 'D:/BACKUP/Wamp_File/3_phpmyadmin.conf/'.date('Y-m-d_H-i-s').'_phpmyadmin.conf');
*/
################Backup DATABASE to E:\Database_Backup\########
$database_name='D:\\BACKUP\\Database\\Database_'.date('Y-m-d_H-i-s').'.sql';
 exec('C:\\wamp64\\bin\\mysql\\mysql5.7.21\\bin\\mysqldump.exe -uroot gs> '.$database_name);
############Create Dynamic Folder#####################
 /* Creates the directory if it does not exist */
 /*
$path_to_directory = 'D:/BACKUP/PHP_Code/Backup_'.date('Y-m-d_H-i-s');
if (!file_exists($path_to_directory) && !is_dir($path_to_directory)) {
    mkdir($path_to_directory, 0777, true);
}
 xcopy('C:/wamp64/www/GS',$path_to_directory);
 */
 ##############Backup PHP File################
/**
 * Copy a file, or recursively copy a folder and its contents
 * @author      Aidan Lister <aidan@php.net>
 * @version     1.0.1
 * @link        http://aidanlister.com/2004/04/recursively-copying-directories-in-php/
 * @param       string   $source    Source path
 * @param       string   $dest      Destination path
 * @param       int      $permissions New folder creation permissions
 * @return      bool     Returns true on success, false on failure
 */
function xcopy($source, $dest, $permissions = 0755)
{
    // Check for symlinks
    if (is_link($source)) {
        return symlink(readlink($source), $dest);
    }

    // Simple copy for a file
    if (is_file($source)) {
        return copy($source, $dest);
    }

    // Make destination directory
    if (!is_dir($dest)) {
        mkdir($dest, $permissions);
    }

    // Loop through the folder
    $dir = dir($source);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..') {
            continue;
        }

        // Deep copy directories
        xcopy("$source/$entry", "$dest/$entry", $permissions);
    }

    // Clean up
    $dir->close();
    return true;
}
###############################################################################
      //$msg="Goodbye! See you soon";
      //durer de vie cookies 5s
      //setcookie("msg",$msg, time()+5);
      header("location: home_char.php"); 
?>