# Twitter Feeder API
Kullanicilarin feedlerini gorebilecekleri Twitter API Web servisi.

### Kullanilan Teknolojiler
- Laravel 8
- PHP 7.4
- Mysql
- mockapi.io
https://mockapi.io/projects/5fc3ca34e5c28f0016f54de7
- Tymon JWT 
- Telescope
- PHPUnit
- Insomnia Documenter
- PHP Codesniffer, PHP Code beautifier


### Onemli Notlar:
- Twitter API'ini kullanmak sancili bir surecmis. API basvuru yontemiyle kullanima aciliyor. Ben de basvurdum ve kabul edilmedi.
Bu yuzden Twitter API'ini kullanamadim. Bunun yerine **mockapi.io** servisini kullandim. Gercek API'yi olabildigince simule etmeye calistim.

- SMS icin Laravel ile calisan Nexmo servisini kullandim. Ancak ucretsiz versiyonunda sadece benim telefonuma SMS atiyor. Virtual number alinmasi gerekiyor.
Email icin ise atilan emaili log dosyasina yaziyorum. Gidilmesi gereken linki ve aktivasyon kodunu orada bulabilirsiniz.

- Ayni kullanici birden fazla login oldugunda onceki token lar expire olmuyor. Bunun icin Login asamasinda jwt token'i parse edip, Redis gibi bi memory de tuttugum tokenlari kiyaslayabilirdim.
Ama kullandigim jwt paketinde jwt token'i parse edilemiyormus. Onun icin ekstra kod yazilip bir sekilde kontrol yapilabilir.
### Kurulum
``` shell
composer install
php artisan migrate (veritabanini bilgilerinizi .env'ye girdikten sonra)
php artisan jwt:secret
php artisan serve
```

### Dokumantasyon
API'nin calisma mantigini buradan okuyabilirsiniz.
https://twitter-feeder-api.surge.sh/
