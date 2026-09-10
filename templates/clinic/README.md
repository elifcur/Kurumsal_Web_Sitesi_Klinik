# Klinik Şablonu (`clinic`)

Klinikler, poliklinikler ve sağlık merkezleri için hazır kurumsal site. Bu proje Statamic/October gibi hazır bir CMS kurmaz; Laravel + MySQL/MariaDB üzerinde kendi admin panelimiz ve kendi kurumsal site şablonlarımızla çalışır.

## Sektör içeriği
- **Bölümler / Tedaviler**: çekirdek `Service` modeli (`services_label` = "Tedaviler"). Tedavi detayında "Bu tedavi için randevu al" → `/randevu?tedavi={slug}`.
- **Doktorlar**: çekirdek `TeamMember` modeli. Kartlarda `Uzmanlık` özelliği pill olarak gösterilir; `Deneyim`, `Diller` detayda listelenir. Doktor detayında "Randevu Al" → `/randevu?doktor={slug}`.
- **Randevu talepleri** (`appointments` tablosu): ad, e-posta, telefon, bölüm, doktor, tercih edilen tarih ve saat aralığı (`sabah` / `öğlen` / `akşam`), mesaj, durum (`pending` / `confirmed` / `cancelled`), iç not, IP.
  - Public: `/randevu` (GET form, POST kayıt). Her talep ayrıca Mesajlar'a "Randevu Talebi" türünde düşer ve bildirim e-postası gönderilir.
  - Ana sayfa hero'sundaki "Hızlı Randevu" mini formu aynı endpoint'e gönderir.
  - Admin: **Klinik > Randevular** — durum filtresi, doktor filtresi, durum/not güncelleme, silme. Dashboard'da bekleyen randevu sayısı.

## Kullanılan çekirdek özellikler
Tedaviler (services), Blog ("Sağlık Rehberi"), Ekip (doktorlar), Hasta Yorumları, SSS, Partnerler (anlaşmalı kurumlar). Ürünler kapalı.

## Şablon ayarları (Site Ayarları > Klinik Ayarları)
`appointments_label`, `appointment_intro`, `appointment_success`, `emergency_phone` (üst çubuk + footer'da 7/24 acil hat), `working_hours_note`, `insurance_partners_title`, `team_home_title`, `team_home_intro`.

## Tasarım
Beyaz zemin + açık cyan/sky yüzeyler (`mist`), rounded-2xl kartlar, Plus Jakarta Sans başlık + Inter gövde. Açık hero (sol metin, sağda hızlı randevu kartı), altında anlaşmalı kurum logo şeridi, güven şeridi, bölüm kartları, neden biz + istatistikler, doktorlar, hasta yorumları, SSS, blog ve koyu randevu CTA bandı.

## Demo içerik
`DemoSeeder`: 8 bölüm, 6 doktor, 4 hasta yorumu, 5 SSS, 6 sigorta partneri, "Sağlık Rehberi" kategorisinde 4 yazı ve farklı durumlarda 6 randevu talebi.
