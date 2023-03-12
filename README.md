# shopier-payment-system
# Demo: https://arzea.online/shopier/premium
# Shopier ile web sitelerinize bir bakiye yükleme sistemi sunun.
# Favicon ayarları "settings.php" dosyası üzerinden yapılabilir.
# Veritabanı ayarları "connect.php" dosyasından yapılabilir.
# Sistem, kullanıcının bakiye işlemleri için veritabanı işlemleri gerçekleştirir. Aşağıdaki dosyalarda, tablo ve sütun adlarını sisteminize göre ayarlamanız gerekir.
# "-index.php"
# "-return_url_page.php"
# Shopier ile bağlantı kurabilmek için aşağıdaki dosyalarda düzenlemeler yapın.
# "-index.php > satır 20 ve satır 21"
# "-.env > satır 1 ve satır 2"
# Güvenlik nedeniyle, yüklenecek bakiyenin miktarı sql'de kısa bir süre tutulur. Sistemin sorunsuz çalışması için kullanıcı verilerini içeren tabloya aşağıdaki kodu eklemeniz gerekir.
# "ALTER TABLE `table_name` ADD `postbalance` INT(15) NOT NULL DEFAULT '0';"

# Shopier ayarları
Bir Shopier hesabı oluşturduktan sonra Entegrasyonlar> Modül Yönetimi'ne gidin ve alan adınızı ekleyin. Ardından Modül Ayarlarına gidin ve dönüş URL'nizi ayarlayın. Bağlantıyı return_url_page dosyasına ayarlamanız gerekir. Ardından API bilgilerinizi kopyalayın ve dosyalarda değişiklik yapın.
