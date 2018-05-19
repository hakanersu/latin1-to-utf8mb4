# Latin-1 to utf8mb4

Charsetlerle ilgili problemlerinizi çözecek ana tek bir çözüm yöntemi maalesef mevcut değil. Bu konuda birbirinden farklı birçok problem ve bu problemleri çözmek için birçok yol mevcut.

Bu ufak kod bütünü deneme amaçlıdır her ne kadar işini yapsada daha güzel çözümlere bulunmakta. 

Basitçe aşağıdakiler uygulanıyor.

* Veritabanı  utf8mb4 karakter setine getiriliyor.
* Veritabanındaki tüm tablolar alınıp karakter setleri güncelleniyor.
* Tablodaki sutunlar alınarak istenilen alanlarda verilen karakterler yer değiştiriliyor.

```php
use Xuma\Fixer\Database;
use Xuma\Fixer\Query;

include "vendor/autoload.php";

$db = new Database('veritabani_adi', 'root', '');
$query = new Query($db);

$query->setTypes(['varchar', 'text', 'mediumtext'])
    ->setIgnoredFields(['uuid','ipaddress','sessionid', 'email','password'])
    ->setIgnoredTables(['activity_log'])
    ->setParts(['â€¢' => '•', 'â€œ' => '“', 'â€' => '”', 'â€˜' => '‘', 'â€™' => '’', 'Ý¾' => 'İ', 'Ý' => 'İ', 'Ä°' => 'İ', 'Ã' => 'İ', 'â€¹' => 'İ', '&Yacute;' => 'İ', 'ý' => 'ı', 'Ä±' => 'ı', 'Â±' => 'ı', 'Ã½' => 'ı', 'Ã›' => 'ı', 'â€º' => 'ı', '&yacute;' => 'ı', 'Þ' => 'Ş', 'Åž' => 'Ş', 'Ã…Å¸' => 'Ş', 'Ã¥Ã¿' => 'Ş', '&THORN;' => 'Ş', 'þ' => 'ş', 'Å?' => 'ş', 'ÅŸ' => 'ş', '&thorn;' => 'ş', 'Ð' => 'Ğ', 'Äž' => 'Ğ', 'ð' => 'ğ', 'Ä?' => 'ğ', 'ÄŸ' => 'ğ', '&eth;' => 'ğ', 'Ã‡' => 'Ç', 'Ã?' => 'Ç', '&Ccedil;' => 'Ç', 'Ã§' => 'ç', '&ccedil;' => 'ç', 'Ã–' => 'Ö', '&Ouml;' => 'Ö', 'Ã¶' => 'ö', '&ouml;' => 'ö', 'Ãœ' => 'Ü', '&Uuml;' => 'Ü', 'ÃƒÂ¼' => 'ü', 'Ã£Â¼' => 'ü', 'Ã¼' => 'ü', '&uuml;' => 'ü'])
    ->setDatabaseCharset()
    ->setTableCharsets()
    ->updateColumns();
```

Eğer sadece spesifik tabloların düzeltilmesini istiyorsak;
```php

$query->setTypes(['varchar', 'text', 'mediumtext'])
    ->setIgnoredFields(['uuid','ipaddress','sessionid', 'email','password'])
    ->tables(['clients'])
    ->setParts(['â€¢' => '•', 'â€œ' => '“', 'â€' => '”', 'â€˜' => '‘', 'â€™' => '’', 'Ý¾' => 'İ', 'Ý' => 'İ', 'Ä°' => 'İ', 'Ã' => 'İ', 'â€¹' => 'İ', '&Yacute;' => 'İ', 'ý' => 'ı', 'Ä±' => 'ı', 'Â±' => 'ı', 'Ã½' => 'ı', 'Ã›' => 'ı', 'â€º' => 'ı', '&yacute;' => 'ı', 'Þ' => 'Ş', 'Åž' => 'Ş', 'Ã…Å¸' => 'Ş', 'Ã¥Ã¿' => 'Ş', '&THORN;' => 'Ş', 'þ' => 'ş', 'Å?' => 'ş', 'ÅŸ' => 'ş', '&thorn;' => 'ş', 'Ð' => 'Ğ', 'Äž' => 'Ğ', 'ð' => 'ğ', 'Ä?' => 'ğ', 'ÄŸ' => 'ğ', '&eth;' => 'ğ', 'Ã‡' => 'Ç', 'Ã?' => 'Ç', '&Ccedil;' => 'Ç', 'Ã§' => 'ç', '&ccedil;' => 'ç', 'Ã–' => 'Ö', '&Ouml;' => 'Ö', 'Ã¶' => 'ö', '&ouml;' => 'ö', 'Ãœ' => 'Ü', '&Uuml;' => 'Ü', 'ÃƒÂ¼' => 'ü', 'Ã£Â¼' => 'ü', 'Ã¼' => 'ü', '&uuml;' => 'ü'])
    ->setDatabaseCharset()
    ->setTableCharsets()
    ->updateColumns();
   ```
 