<?php

use Xuma\Fixer\Database;
use Xuma\Fixer\Query;

include "vendor/autoload.php";

$db = new Database('dbadi', 'root', '');
$query = new Query($db);
// Hangi tablo sutun tiplerinde degisiklik yapilacak.
$query->types = ['varchar', 'text', 'mediumtext'];
// Hangi alanlar uzerinde degisiklik yapilmayacak.
$query->ignoredFiels = ['uuid','ipaddress','sessionid', 'email'];
// Degistirilecek karakter setleri.
$query->setParts(['â€¢' => '•', 'â€œ' => '“', 'â€' => '”', 'â€˜' => '‘', 'â€™' => '’', 'Ý¾' => 'İ', 'Ý' => 'İ', 'Ä°' => 'İ', 'Ã' => 'İ', 'â€¹' => 'İ', '&Yacute;' => 'İ', 'ý' => 'ı', 'Ä±' => 'ı', 'Â±' => 'ı', 'Ã½' => 'ı', 'Ã›' => 'ı', 'â€º' => 'ı', '&yacute;' => 'ı', 'Þ' => 'Ş', 'Åž' => 'Ş', 'Ã…Å¸' => 'Ş', 'Ã¥Ã¿' => 'Ş', '&THORN;' => 'Ş', 'þ' => 'ş', 'Å?' => 'ş', 'ÅŸ' => 'ş', '&thorn;' => 'ş', 'Ð' => 'Ğ', 'Äž' => 'Ğ', 'ð' => 'ğ', 'Ä?' => 'ğ', 'ÄŸ' => 'ğ', '&eth;' => 'ğ', 'Ã‡' => 'Ç', 'Ã?' => 'Ç', '&Ccedil;' => 'Ç', 'Ã§' => 'ç', '&ccedil;' => 'ç', 'Ã–' => 'Ö', '&Ouml;' => 'Ö', 'Ã¶' => 'ö', '&ouml;' => 'ö', 'Ãœ' => 'Ü', '&Uuml;' => 'Ü', 'ÃƒÂ¼' => 'ü', 'Ã£Â¼' => 'ü', 'Ã¼' => 'ü', '&uuml;' => 'ü']);

$query->setDatabaseCharset()
    ->setTableCharsets()
    ->updateColumns();