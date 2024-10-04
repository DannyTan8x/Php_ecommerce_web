<?php
error_reporting(0);
echo 'hi';
# Coded by L0c4lh34rtz - IndoXploit

# \n -> linux
# \r\n -> windows
$list = explode("\n", file_get_contents($argv[1])); # change \n to \r\n if you're using windows
# ------------------- #

$hash = '$2y$10$WU35MnF6RPV3TD4QRErCaO3jcp3fuLc9vYy6XegaQeRbe.egOpS4e'; # hash here, NB: use single quote (') , don't use double quote (")

if(isset($argv[1])) {
    foreach($list as $wordlist) {
        print " [+]"; print (password_verify($wordlist, $hash)) ? "$hash -> $wordlist (OK)\n" : "$hash -> $wordlist (SALAH)\n";
    }
} else {
    echo "usage: php " .$argv[0] . " wordlist.txt\n";
}

?>