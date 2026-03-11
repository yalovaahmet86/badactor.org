Aşağıya **GitHub tarzı profesyonel bir README** hazırladım.
Bunu hem **GitHub repo README.md** olarak hem de **badactor.org ana sayfa açıklaması** olarak kullanabilirsin.

Not: “BadActor” kavramı güvenlik dünyasında genellikle **sistemi tarayan veya saldırı yapan kötü niyetli kullanıcıları engelleme** fikrini ifade eder; örneğin bazı projelerde fail2ban benzeri bir sistem olarak kullanılır. ([GitHub][1])

---

# BadActor Tekirdağ Challenge

Modern web uygulamalarında güvenlik, sadece firewall veya sunucu ayarları ile sağlanmaz.
**BadActor Tekirdağ Challenge**, PHP ile yazılmış küçük bir güvenlik / algoritma challenge projesidir.

Bu proje, kullanıcıdan gelen veriler üzerinden **hash üretimi, doğrulama ve brute-force mantığını anlamaya yönelik** bir eğitim uygulaması olarak hazırlanmıştır.

Proje aşağıdaki adreslerde yayınlanacaktır:

* [https://badactor.org](https://badactor.org)
* [https://badactor.org/tekirdag/](https://badactor.org/tekirdag/)

---

# Amaç

Bu proje bir **mini CTF (Capture The Flag) tarzı challenge uygulamasıdır**.

Kullanıcıdan bir **kullanıcı adı** alınır ve sistem belirli bir algoritmaya göre günlük bir **challenge kodu üretir**.

Kullanıcının amacı:

1. Kullanıcı adı girerek challenge başlatmak
2. Algoritmayı analiz etmek
3. Doğru **8 karakterlik challenge kodunu** üretmek

Doğru kod girildiğinde kullanıcı challenge’ı tamamlar.

---

# Nasıl Çalışır

Sistem her kullanıcı için günlük bir kod üretir.

Kod üretim algoritması:

```
SHA256(username + secret + current_date)
```

Üretilen hash'in ilk **8 karakteri** challenge kodu olarak kullanılır.

Örnek:

```
username = phantom
secret = SECRET_KEY
date = 2026-03-12
```

hash:

```
SHA256("phantom|SECRET_KEY|2026-03-12")
```

çıktı:

```
A9F1C3B8...
```

challenge kodu:

```
A9F1C3B8
```

---

# Özellikler

Proje aşağıdaki özellikleri içerir:

### Günlük Challenge

Her gün farklı bir challenge kodu oluşur.

### Session Tabanlı Deneme Sayacı

Her kullanıcı için:

* kaç deneme yaptığı
* challenge başarı durumu

session ile tutulur.

### İpucu Sistemi

Kullanıcı ipucu istediğinde:

* kod uzunluğu
* ilk karakterler
* tarih bilgisi

gibi bilgiler gösterilir.

### Basit CTF Mantığı

Amaç:

* brute force
* hash analizi
* algoritma çözme

gibi temel güvenlik konseptlerini öğretmektir.

---

# Kurulum

Projeyi çalıştırmak için:

```
PHP 8+
Apache / Nginx
```

Yükleme adımları:

```
git clone https://github.com/yourrepo/badactor-challenge
```

veya

```
index.php dosyasını sunucuya yükleyin
```

sonra:

```
https://badactor.org/tekirdag/
```

adresinden erişilebilir.

---

# Dosya Yapısı

```
/tekirdag
 ├─ index.php
 └─ README.md
```

Basit bir yapı tercih edilmiştir.

Amaç:

* anlaşılır
* hızlı
* minimal

bir challenge ortamı sunmaktır.

---

# Geliştirme Fikirleri

Proje aşağıdaki özelliklerle geliştirilebilir:

### Leaderboard

En hızlı çözen kullanıcılar.

### Rate Limit

Brute force saldırılarını sınırlamak.

### IP Loglama

Şüpheli aktiviteleri tespit etmek.

### Admin Panel

Challenge yönetimi.

### API

Challenge üretimi için endpoint.

---

# Güvenlik Notu

Bu proje **öğrenme ve eğlence amaçlıdır**.

Gerçek üretim sistemlerinde:

* rate limiting
* captcha
* WAF
* intrusion detection

gibi ek güvenlik katmanları kullanılmalıdır.

---

# İlham

“Bad Actor” kavramı genellikle güvenlik dünyasında
**sistemi tarayan veya saldırmaya çalışan kullanıcıları tespit etmek ve engellemek** anlamında kullanılır. ([GitHub][1])

Bu proje bu fikirden esinlenerek hazırlanmış bir **mini güvenlik challenge’ıdır**.

---

# Lisans

MIT License

---

# Yayın

Bu proje aşağıdaki adreslerde yayınlanacaktır:

* [https://badactor.org](https://badactor.org)
* [https://badactor.org/tekirdag/](https://badactor.org/tekirdag/)

---

İstersen sana ayrıca:

* **çok profesyonel SEO README**
* **Google index alacak landing page**
* **hack / CTF temalı landing design**

da hazırlayabilirim.
O zaman site **gerçek bir hacker challenge sitesi gibi** görünür.

[1]: https://github.com/jaredfolkins/badactor?utm_source=chatgpt.com "GitHub - jaredfolkins/badactor: BadActor.org An in-memory application ..."
