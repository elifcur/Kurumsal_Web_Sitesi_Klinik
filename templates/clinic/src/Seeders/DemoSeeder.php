<?php

namespace Templates\Clinic\Seeders;

use App\Models\Category;
use App\Models\Faq;
use App\Models\Partner;
use App\Models\Post;
use App\Models\Service;
use App\Models\Tag;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\User;
use App\Services\Settings;
use Illuminate\Database\Seeder;
use Templates\Clinic\Models\Appointment;

/**
 * Klinik sitesini kurulumdan hemen sonra dolu gösteren demo içerik.
 * Idempotent: randevu kaydı varsa hiçbir şey oluşturulmaz (demo logo/hero ayarları da bu blokta set edilir).
 */
class DemoSeeder extends Seeder
{
    public function run(): void
    {
        if (Appointment::query()->exists()) {
            return;
        }

        $settings = app(Settings::class);
        $settings->set('logo', '/images/templates/clinic/logo.png', 'general');
        $settings->set('hero_image', '/images/templates/clinic/hero.jpg', 'home');
        $settings->set('hero_card_title', '', 'template'); // demo fotoğrafında kart zaten var
        if (! setting('phone')) {
            $settings->set('phone', '+90 212 000 00 00', 'general');
        }

        $services = collect([
            ['title' => 'Kardiyoloji', 'icon' => 'heart', 'excerpt' => 'EKG, efor testi, ekokardiyografi ve ritim holter ile kalp sağlığınızı düzenli takip ediyoruz.', 'content' => '<p>Kardiyoloji bölümümüzde hipertansiyon, koroner arter hastalığı, kalp yetmezliği ve ritim bozukluklarının tanı ve tedavisi yapılmaktadır.</p><p>EKG, efor testi, ekokardiyografi, 24 saatlik ritim ve tansiyon holteri gibi tetkikler aynı gün içinde tamamlanır; sonuçlar uzman hekimimiz tarafından sizinle birlikte değerlendirilir.</p><ul><li>Kalp check-up programları</li><li>Kalp yetmezliği takibi</li><li>Ritim bozukluğu tanı ve tedavisi</li></ul>'],
            ['title' => 'Çocuk Sağlığı ve Hastalıkları', 'icon' => 'sparkles', 'excerpt' => 'Yenidoğandan ergenliğe sağlıklı çocuk izlemi, aşı takibi ve akut hastalık tedavisi.', 'content' => '<p>Çocuk sağlığı bölümümüzde yenidoğan muayenesi, büyüme-gelişme takibi, aşı programı, alerji ve enfeksiyon hastalıklarının tedavisi yapılmaktadır.</p><p>Çocuklara özel bekleme alanımız ve güler yüzlü ekibimizle muayeneyi korkusuz bir deneyime dönüştürüyoruz.</p>'],
            ['title' => 'Ortopedi ve Travmatoloji', 'icon' => 'puzzle', 'excerpt' => 'Eklem, omurga ve spor yaralanmalarında cerrahi ve cerrahi dışı çözümler.', 'content' => '<p>Ortopedi bölümümüzde diz ve kalça protezi, artroskopik cerrahi, menisküs ve bağ yaralanmaları, omurga sorunları ve kırık tedavileri uygulanmaktadır.</p><p>Spor yaralanmalarında hızlı tanı ve fizik tedavi ekibimizle koordineli rehabilitasyon planı sunuyoruz.</p>'],
            ['title' => 'Dermatoloji', 'icon' => 'shield', 'excerpt' => 'Cilt hastalıkları, akne, saç dökülmesi ve medikal estetik uygulamalarında uzman yaklaşım.', 'content' => '<p>Dermatoloji bölümümüz egzama, sedef, akne, rozase, mantar ve ben takibi gibi cilt hastalıklarının tanı ve tedavisini üstlenir.</p><p>Dermatoskopik ben haritalama ile cilt kanseri taraması yapılır; saç dökülmesi ve tırnak hastalıklarında kişiye özel tedavi planlanır.</p>'],
            ['title' => 'Diş Sağlığı', 'icon' => 'tooth', 'excerpt' => 'İmplant, ortodonti, estetik diş hekimliği ve çocuk diş sağlığında ağrısız, konforlu tedavi.', 'content' => '<p>Ağız ve diş sağlığı bölümümüzde dijital röntgen, panoramik görüntüleme ve steril üniteler ile hizmet veriyoruz.</p><p>İmplant, zirkonyum kaplama, şeffaf plak ortodontisi, diş beyazlatma, kanal tedavisi ve çocuk diş hekimliği hizmetleri tek merkezde sunulmaktadır.</p><ul><li>İmplant ve protez</li><li>Ortodonti (tel ve şeffaf plak)</li><li>Estetik diş hekimliği</li></ul>'],
            ['title' => 'Göz Hastalıkları', 'icon' => 'eye', 'excerpt' => 'Katarakt, retina, glokom tanı-tedavisi ve lazer ile gözlükten kurtulma.', 'content' => '<p>Göz bölümümüzde tam donanımlı muayene üniteleri, OCT ve görme alanı cihazları ile katarakt, glokom, retina hastalıkları ve göz tansiyonu takibi yapılmaktadır.</p><p>Çocuklarda göz tembelliği taraması, yetişkinlerde ise gözlükten kurtulma seçenekleri hakkında detaylı bilgi alabilirsiniz.</p>'],
            ['title' => 'Kadın Hastalıkları ve Doğum', 'icon' => 'users', 'excerpt' => 'Gebelik takibi, jinekolojik muayene, smear ve menopoz dönemi danışmanlığı.', 'content' => '<p>Kadın hastalıkları ve doğum bölümümüzde rutin jinekolojik muayene, smear ve HPV testi, ultrason eşliğinde gebelik takibi, doğum planlaması ve menopoz danışmanlığı hizmetleri sunulmaktadır.</p><p>Gebelik boyunca her kontrolde aynı hekimle görüşür, tüm tarama testlerinizi kliniğimizde yaptırabilirsiniz.</p>'],
            ['title' => 'Fizik Tedavi ve Rehabilitasyon', 'icon' => 'check-circle', 'excerpt' => 'Bel-boyun ağrıları, ameliyat sonrası rehabilitasyon ve manuel terapi programları.', 'content' => '<p>Fizik tedavi bölümümüzde bel fıtığı, boyun düzleşmesi, omuz sıkışması, kireçlenme ve ameliyat sonrası rehabilitasyon için kişiye özel programlar uygulanır.</p><p>Manuel terapi, kuru iğneleme, ESWT ve egzersiz terapisi deneyimli fizyoterapistlerimiz eşliğinde yürütülür.</p>'],
        ])->map(fn (array $service, int $i) => Service::factory()->create($service + ['sort_order' => $i, 'is_featured' => true, 'image' => '/images/templates/clinic/service-'.($i + 1).'.jpg']));

        $doctors = collect([
            ['name' => 'Uzm. Dr. Ayşe Yılmaz', 'title' => 'Kardiyoloji Uzmanı', 'excerpt' => 'Koroner arter hastalığı ve kalp yetmezliği takibinde 16 yıllık deneyim.', 'attributes' => [['label' => 'Uzmanlık', 'value' => 'Kardiyoloji'], ['label' => 'Deneyim', 'value' => '16 yıl'], ['label' => 'Diller', 'value' => 'Türkçe, İngilizce']]],
            ['name' => 'Op. Dr. Mehmet Demir', 'title' => 'Ortopedi ve Travmatoloji Uzmanı', 'excerpt' => 'Artroskopik cerrahi ve diz protezi alanında uzmanlaşmış ortopedi hekimi.', 'attributes' => [['label' => 'Uzmanlık', 'value' => 'Ortopedi ve Travmatoloji'], ['label' => 'Deneyim', 'value' => '14 yıl'], ['label' => 'Diller', 'value' => 'Türkçe, İngilizce, Almanca']]],
            ['name' => 'Dt. Zeynep Kaya', 'title' => 'Diş Hekimi · İmplantoloji', 'excerpt' => 'İmplant ve estetik diş hekimliği alanında sertifikalı, ağrısız tedavi odaklı.', 'attributes' => [['label' => 'Uzmanlık', 'value' => 'Diş Sağlığı, İmplantoloji'], ['label' => 'Deneyim', 'value' => '11 yıl'], ['label' => 'Diller', 'value' => 'Türkçe, İngilizce']]],
            ['name' => 'Uzm. Dr. Emre Şahin', 'title' => 'Dermatoloji Uzmanı', 'excerpt' => 'Akne, egzama ve dermatoskopik ben takibi konusunda deneyimli dermatolog.', 'attributes' => [['label' => 'Uzmanlık', 'value' => 'Dermatoloji'], ['label' => 'Deneyim', 'value' => '9 yıl'], ['label' => 'Diller', 'value' => 'Türkçe, İngilizce']]],
            ['name' => 'Op. Dr. Elif Arslan', 'title' => 'Göz Hastalıkları Uzmanı', 'excerpt' => 'Katarakt ve refraktif cerrahi alanında 3.000\'in üzerinde başarılı operasyon.', 'attributes' => [['label' => 'Uzmanlık', 'value' => 'Göz Hastalıkları'], ['label' => 'Deneyim', 'value' => '12 yıl'], ['label' => 'Diller', 'value' => 'Türkçe, Fransızca']]],
            ['name' => 'Uzm. Dr. Burak Çelik', 'title' => 'Çocuk Sağlığı ve Hastalıkları Uzmanı', 'excerpt' => 'Yenidoğan izlemi ve çocuk alerjisi konusunda deneyimli, çocukların sevgilisi.', 'attributes' => [['label' => 'Uzmanlık', 'value' => 'Çocuk Sağlığı ve Hastalıkları'], ['label' => 'Deneyim', 'value' => '10 yıl'], ['label' => 'Diller', 'value' => 'Türkçe, İngilizce']]],
        ])->map(fn (array $doctor, int $i) => TeamMember::factory()->create($doctor + ['sort_order' => $i, 'is_featured' => $i < 4, 'socials' => []]));

        foreach ([
            ['Hülya D.', 'Kardiyoloji hastası', 'Efor testi ve ekokardiyografi aynı gün yapıldı, sonuçları doktorum tek tek açıkladı. Kendimi hiç bu kadar güvende hissetmemiştim.'],
            ['Kerem B.', 'İmplant tedavisi', 'Diş hekimi korkumu yendim diyebilirim. Her aşamada bilgilendirildim ve gerçekten ağrı hissetmedim.'],
            ['Merve S.', 'Gebelik takibi', 'Hamileliğim boyunca aynı doktorla görüştüm, tüm sorularıma sabırla cevap verdi. Doğumumuz da harika geçti.'],
            ['Ahmet Y.', 'Fizik tedavi hastası', 'Bel fıtığım için ameliyat önerilmişti. Buradaki fizik tedavi programıyla ameliyatsız iyileştim, teşekkürler.'],
        ] as $i => [$name, $title, $content]) {
            Testimonial::factory()->create(['name' => $name, 'title' => $title, 'content' => $content, 'rating' => 5, 'sort_order' => $i]);
        }

        foreach ([
            ['Online randevu talebim ne zaman kesinleşir?', 'Talebiniz alındıktan sonra hasta danışmanımız mesai saatleri içinde en geç 2 saat içinde sizi arayarak uygun saati birlikte belirler.'],
            ['Anlaşmalı olduğunuz sigorta şirketleri hangileri?', 'Allianz, Axa, Anadolu Sigorta, Türkiye Sigorta, Mapfre ve Sompo ile tamamlayıcı ve özel sağlık sigortası anlaşmamız bulunmaktadır. Güncel liste için bizi arayabilirsiniz.'],
            ['SGK ile anlaşmanız var mı?', 'Kliniğimiz özel sağlık kuruluşudur; SGK anlaşması bulunmamaktadır. Tamamlayıcı sağlık sigortanız varsa fark ödemeden muayene olabilirsiniz.'],
            ['Tetkik sonuçlarımı nasıl alabilirim?', 'Laboratuvar ve görüntüleme sonuçlarınız hazır olduğunda SMS ile bilgilendirilirsiniz; sonuçlarınızı danışmadan ya da e-posta ile alabilirsiniz.'],
            ['Otopark hizmetiniz var mı?', 'Evet, hastalarımız için kapalı otoparkımız ücretsizdir. Engelli ve hamile hastalarımız için ayrılmış alanlar mevcuttur.'],
        ] as $i => [$question, $answer]) {
            Faq::factory()->create(['question' => $question, 'answer' => $answer, 'sort_order' => $i]);
        }

        foreach (['Allianz', 'Axa Sigorta', 'Anadolu Sigorta', 'Türkiye Sigorta', 'Mapfre', 'Sompo Sigorta'] as $i => $name) {
            Partner::factory()->create(['name' => $name, 'url' => null, 'sort_order' => $i]);
        }

        $category = Category::factory()->post()->create(['name' => 'Sağlık Rehberi']);
        $tags = collect(['önleyici sağlık', 'beslenme', 'çocuk sağlığı', 'kalp sağlığı'])->map(fn ($n) => Tag::factory()->create(['name' => $n]));
        $author = User::query()->first();

        foreach ([
            ['Kalp Sağlığınızı Korumak İçin 8 Günlük Alışkanlık', 'Tuz tüketiminden uyku düzenine, kalbinizi yormadan yaşamak için uzmanlarımızın önerileri.'],
            ['Çocuklarda Aşı Takvimi: Hangi Aşı Ne Zaman?', 'Doğumdan ergenliğe kadar uygulanan rutin aşıların dönemleri ve sık sorulan sorular.'],
            ['Diş Beyazlatma Güvenli mi? Bilmeniz Gerekenler', 'Klinik ve ev tipi beyazlatma yöntemlerinin farkları, kalıcılık süreleri ve dikkat edilmesi gerekenler.'],
            ['Bel Ağrısında Ne Zaman Doktora Gitmeli?', 'Hangi belirtiler ciddiye alınmalı, hangi durumlarda evde dinlenme yeterli? Fizik tedavi uzmanımız anlatıyor.'],
        ] as [$title, $excerpt]) {
            $post = Post::factory()->create(['title' => $title, 'excerpt' => $excerpt, 'user_id' => $author?->id, 'category_id' => $category->id]);
            $post->tags()->sync($tags->random(2)->pluck('id'));
        }

        foreach ([
            ['Selin Aydın', '0532 111 22 33', 0, 0, 2, 'sabah', 'pending', 'Göğüs ağrısı şikayetim var, mümkünse bu hafta içinde.'],
            ['Murat Koç', '0533 222 33 44', 4, 2, 3, 'öğlen', 'confirmed', 'İmplant için ön görüşme.'],
            ['Deniz Aksoy', '0535 333 44 55', 3, 3, 5, 'akşam', 'pending', null],
            ['Fatma Öztürk', '0536 444 55 66', 6, null, 7, 'sabah', 'confirmed', 'Gebelik kontrolü, 12. hafta.'],
            ['Can Erdoğan', '0537 555 66 77', 2, 1, 1, 'öğlen', 'cancelled', 'Diz ağrısı, spor yaralanması.'],
            ['Nazlı Kurt', '0538 666 77 88', 1, 5, 4, 'sabah', 'pending', 'Çocuğum için rutin aşı kontrolü.'],
        ] as [$name, $phone, $serviceIndex, $doctorIndex, $daysAhead, $time, $status, $note]) {
            Appointment::factory()->create([
                'name' => $name,
                'phone' => $phone,
                'service_id' => $services[$serviceIndex]->id,
                'team_member_id' => $doctorIndex === null ? null : $doctors[$doctorIndex]->id,
                'preferred_date' => now()->addDays($daysAhead)->toDateString(),
                'preferred_time' => $time,
                'status' => $status,
                'message' => $note,
                'notes' => $status === 'confirmed' ? 'Hasta arandı, randevu teyit edildi.' : ($status === 'cancelled' ? 'Hasta talebi üzerine iptal edildi.' : null),
            ]);
        }
    }
}
