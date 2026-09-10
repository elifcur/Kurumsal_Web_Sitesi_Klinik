<?php

namespace Templates\Clinic;

use App\Models\Faq;
use App\Models\Partner;
use App\Models\Post;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Templates\Template as BaseTemplate;
use Templates\Clinic\Models\Appointment;

/**
 * Sağlık / klinik şablonu. Bölümler ve tedaviler çekirdek Service modelini,
 * doktorlar çekirdek TeamMember modelini kullanır; sektöre özel içerik
 * randevu talepleridir (appointments tablosu).
 */
class Template extends BaseTemplate
{
    public function key(): string
    {
        return 'clinic';
    }

    public function name(): string
    {
        return 'Klinik';
    }

    public function description(): string
    {
        return 'Klinikler, poliklinikler ve sağlık merkezleri için: bölümler/tedaviler, doktor kadrosu, online randevu talebi, anlaşmalı kurumlar.';
    }

    public function features(): array
    {
        return [
            'services' => true,
            'products' => false,
            'blog' => true,
            'team' => true,
            'testimonials' => true,
            'faqs' => true,
            'partners' => true,
        ];
    }

    public function defaultSettings(): array
    {
        return [
            'general' => [
                'site_name' => 'Özel Yaşam Sağlık',
                'site_tagline' => 'Sağlıklı yarınlar için',
                'primary_color' => '#0f6f85',
                'services_label' => 'Tıbbi Birimler',
                'blog_label' => 'Sağlık Rehberi',
            ],
            'seo' => [
                'meta_title' => 'Özel Yaşam Sağlık | Uzman Doktorlar, Online Randevu',
                'meta_description' => 'Kardiyoloji, diş, dermatoloji, ortopedi, göz, kadın doğum, çocuk sağlığı ve fizik tedavi bölümleriyle hizmet veren modern klinik. Online randevu alın.',
            ],
            'home' => [
                'hero_title' => 'Sağlığınız *Bizim Önceliğimiz*',
                'hero_subtitle' => 'Alanında uzman doktorlarımız, modern teknolojimiz ve hasta odaklı yaklaşımımızla yanınızdayız.',
                'hero_button_text' => 'Randevu Al',
                'hero_button_url' => '/randevu',
                'hero_button2_text' => 'Hizmetlerimizi İncele',
                'hero_button2_url' => '/hizmetler',
                'about_title' => 'Neden Özel Yaşam Sağlık?',
                'about_text' => "Hastalarımızı bir numara olarak değil, birey olarak görüyoruz. Tanıdan tedaviye kadar her adımda sizi bilgilendirir, kararları birlikte alırız.\nSon teknoloji görüntüleme ve laboratuvar altyapımız sayesinde tetkiklerinizi aynı gün içinde tamamlayabilirsiniz.",
                'services_intro' => 'Tek çatı altında, koordineli çalışan bölümlerimizle bütüncül sağlık hizmeti sunuyoruz.',
                'cta_title' => 'Randevunuzu *şimdi* oluşturun',
                'cta_text' => 'Formu doldurun, hasta danışmanımız sizi arayarak randevunuzu kesinleştirsin.',
                'cta_button_text' => 'Online Randevu Al',
                'cta_button_url' => '/randevu',
                'stats' => '25+|Uzman Doktor;40.000+|Mutlu Hasta;18|Yıllık Deneyim;8|Branş',
            ],
            'template' => [
                'hero_eyebrow' => 'Daha sağlıklı bir yaşam için',
                'hero_card_title' => 'Alanında Uzman',
                'hero_card_text' => 'Deneyimli Kadro',
                'services_home_title' => 'Sağlığınız İçin Buradayız',
                'online_appointment_text' => 'Uzman doktorlarımızdan kolayca randevu alın, sağlığınızı ertelemeyin.',
                'appointments_label' => 'Randevu Al',
                'appointment_intro' => 'Size uygun bölümü ve tarihi seçin; hasta danışmanımız en kısa sürede sizi arayarak randevunuzu kesinleştirsin.',
                'appointment_success' => 'Randevu talebiniz alındı. Hasta danışmanımız en kısa sürede sizinle iletişime geçecek.',
                'emergency_phone' => '0850 000 00 00',
                'working_hours_note' => 'Hafta içi 08:00–20:00 · Cumartesi 09:00–17:00',
                'insurance_partners_title' => 'Anlaşmalı Kurumlar',
                'team_home_title' => 'Doktorlarımız',
                'team_home_intro' => 'Alanında uzman, deneyimli ve güler yüzlü hekim kadromuzla tanışın.',
            ],
        ];
    }

    public function settingFields(): array
    {
        return [
            'page_header_image' => ['label' => 'İç sayfa bant görseli', 'type' => 'image', 'help' => 'Tıbbi birimler, doktorlar, blog ve iletişim gibi iç sayfaların üst bandında koyu overlay altında gösterilir; boşsa hero görseli kullanılır. 1920x640 önerilir.'],
            'hero_eyebrow' => ['label' => 'Hero üst yazısı', 'type' => 'text', 'help' => 'Başlığın üstünde küçük, aralıklı büyük harflerle gösterilir.', 'rules' => ['max:80']],
            'hero_card_title' => ['label' => 'Hero yüzen kart başlığı', 'type' => 'text', 'rules' => ['max:60']],
            'hero_card_text' => ['label' => 'Hero yüzen kart alt satırı', 'type' => 'text', 'rules' => ['max:60']],
            'services_home_title' => ['label' => 'Ana sayfa bölümler başlığı', 'type' => 'text', 'rules' => ['max:120']],
            'online_appointment_text' => ['label' => 'Online randevu kartı metni', 'type' => 'text', 'rules' => ['max:200']],
            'appointments_label' => ['label' => 'Randevu butonu / menü etiketi', 'type' => 'text', 'rules' => ['max:50']],
            'appointment_intro' => ['label' => 'Randevu sayfası açıklaması', 'type' => 'textarea', 'rules' => ['max:400']],
            'appointment_success' => ['label' => 'Randevu formu başarı mesajı', 'type' => 'text', 'rules' => ['max:255']],
            'emergency_phone' => ['label' => 'Acil hat numarası', 'type' => 'text', 'help' => 'Hero altındaki özellik şeridinde ve footer\'da "7/24 Hasta Destek Hattı" olarak gösterilir.', 'rules' => ['max:40']],
            'working_hours_note' => ['label' => 'Çalışma saatleri notu', 'type' => 'text', 'help' => 'Footer ve iletişim sayfasında gösterilen kısa not.', 'rules' => ['max:120']],
            'insurance_partners_title' => ['label' => 'Anlaşmalı kurumlar başlığı', 'type' => 'text', 'rules' => ['max:120']],
            'team_home_title' => ['label' => 'Ana sayfa doktorlar başlığı', 'type' => 'text', 'rules' => ['max:120']],
            'team_home_intro' => ['label' => 'Ana sayfa doktorlar açıklaması', 'type' => 'text', 'rules' => ['max:255']],
        ];
    }

    public function adminMenu(): array
    {
        return [
            ['label' => 'Randevular', 'route' => 'admin.appointments.index', 'pattern' => 'admin.appointments.*', 'icon' => 'calendar', 'permission' => 'manage-sector'],
        ];
    }

    public function publicMenu(): array
    {
        return [
            ['label' => setting('team_home_title', 'Doktorlarımız'), 'route' => 'team.index', 'pattern' => 'ekibimiz*'],
        ];
    }

    public function dashboardStats(): array
    {
        return [
            ['label' => 'Bekleyen Randevular', 'value' => Appointment::query()->pending()->count(), 'sub' => Appointment::query()->count().' toplam talep', 'route' => 'admin.appointments.index', 'icon' => 'calendar'],
        ];
    }

    public function sitemapEntries(): array
    {
        return [
            ['url' => route('appointments.create'), 'updated' => null, 'priority' => '0.9'],
        ];
    }

    public function messageTypes(): array
    {
        return ['appointment' => 'Randevu Talebi'];
    }

    public function homeData(): array
    {
        $services = Service::query()->active()->ordered()->get();

        return [
            'services' => $services->take(8),
            'team' => TeamMember::query()->active()->where('is_featured', true)->ordered()->limit(4)->get(),
            'testimonials' => Testimonial::query()->active()->ordered()->limit(3)->get(),
            'faqs' => Faq::query()->active()->ordered()->limit(6)->get(),
            'partners' => Partner::query()->active()->ordered()->get(),
            'posts' => setting('show_blog', '1') === '1' ? Post::query()->with('category')->published()->latestPublished()->limit(3)->get() : collect(),
            'stats' => site_stats(),
        ];
    }
}
