<?php

namespace Tests\Feature\Templates;

use App\Mail\ContactMessageReceived;
use App\Models\Service;
use App\Models\TeamMember;
use App\Services\Settings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Templates\Clinic\Models\Appointment;
use Templates\Clinic\Seeders\DemoSeeder;
use Tests\TestCase;

class ClinicTemplateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if ($this->activeTemplateKey() !== 'clinic') {
            $this->markTestSkipped('Bu test yalnızca clinic şablonu aktifken çalışır (APP_TEMPLATE=clinic).');
        }

        // Şablon varlıkları henüz derlenmemiş olsa da (npm run build) görünümler test edilebilsin.
        $this->withoutVite();
    }

    public function test_home_doctor_and_treatment_pages_render(): void
    {
        $service = Service::factory()->create(['title' => 'Kardiyoloji', 'is_featured' => true]);
        Service::factory()->inactive()->create(['title' => 'Gizli Bölüm']);
        $doctor = TeamMember::factory()->create(['name' => 'Uzm. Dr. Ayşe Yılmaz', 'is_featured' => true, 'attributes' => [['label' => 'Uzmanlık', 'value' => 'Kardiyoloji']]]);

        $this->get('/')->assertOk()
            ->assertSee('Online Randevu')
            ->assertSee('Randevu Oluştur')
            ->assertSee('Sağlığınız İçin Buradayız')
            ->assertSee('Modern')
            ->assertSee('Tıbbi Teknoloji')
            ->assertSee('Kardiyoloji')
            ->assertDontSee('Gizli Bölüm')
            ->assertSee('Uzm. Dr. Ayşe Yılmaz')
            ->assertSee(route('appointments.create'));

        $this->get($service->url())->assertOk()
            ->assertSee('Bu tedavi için randevu al')
            ->assertSee(route('appointments.create', ['tedavi' => $service->slug]), false);

        $this->get($doctor->url())->assertOk()
            ->assertSee('Randevu Al')
            ->assertSee(route('appointments.create', ['doktor' => $doctor->slug]), false);

        $this->get('/ekibimiz')->assertOk()->assertSee('Uzm. Dr. Ayşe Yılmaz');

        $this->get('/randevu?doktor='.$doctor->slug.'&tedavi='.$service->slug)->assertOk()
            ->assertSee('Seçili doktor')
            ->assertSee('Seçili bölüm')
            ->assertSee('Kardiyoloji');

        $this->get('/sitemap.xml')->assertOk()->assertSee(route('appointments.create'));
    }

    public function test_appointment_request_creates_appointment_and_message_and_notifies(): void
    {
        Mail::fake();
        app(Settings::class)->set('email', 'klinik@test.com');
        $service = Service::factory()->create(['title' => 'Diş Sağlığı']);
        $doctor = TeamMember::factory()->create(['name' => 'Dt. Zeynep Kaya']);

        $this->from('/randevu')->post(route('appointments.store'), [
            'name' => 'Ali Veli',
            'email' => 'ali@example.com',
            'phone' => '0555 111 22 33',
            'service_id' => $service->id,
            'team_member_id' => $doctor->id,
            'preferred_date' => now()->addDays(3)->toDateString(),
            'preferred_time' => 'sabah',
            'message' => 'Diş ağrım var.',
            'kvkk' => 1,
        ])->assertRedirect(route('appointments.create'))->assertSessionHas('success');

        $this->assertDatabaseHas('appointments', ['name' => 'Ali Veli', 'service_id' => $service->id, 'team_member_id' => $doctor->id, 'status' => 'pending', 'preferred_time' => 'sabah']);
        $this->assertDatabaseHas('contact_messages', ['type' => 'appointment', 'email' => 'ali@example.com', 'subject' => 'Randevu Talebi: Diş Sağlığı']);
        Mail::assertSent(ContactMessageReceived::class);

        $this->get('/randevu')->assertOk();
    }

    public function test_appointment_request_validation_and_honeypot(): void
    {
        $this->from('/randevu')->post(route('appointments.store'), [])
            ->assertRedirect('/randevu')
            ->assertSessionHasErrors(['name', 'phone', 'preferred_date', 'kvkk']);

        $this->from('/randevu')->post(route('appointments.store'), [
            'name' => 'Ali', 'phone' => '1', 'preferred_date' => now()->subDay()->toDateString(), 'preferred_time' => 'gece', 'kvkk' => 1,
        ])->assertSessionHasErrors(['preferred_date', 'preferred_time']);

        $this->from('/')->post(route('appointments.store'), [
            'name' => 'Bot', 'phone' => '1', 'preferred_date' => now()->addDay()->toDateString(), 'kvkk' => 1, 'website' => 'spam',
        ])->assertRedirect('/')->assertSessionHasErrors('website');

        $this->assertDatabaseCount('appointments', 0);
    }

    public function test_admin_can_list_filter_update_and_delete_appointments(): void
    {
        $user = $this->editor();
        $pending = Appointment::factory()->create(['name' => 'Bekleyen Hasta']);
        $confirmed = Appointment::factory()->confirmed()->create(['name' => 'Onaylı Hasta']);

        $this->get(route('admin.appointments.index'))->assertRedirect(route('admin.login'));
        $this->actingAs($user)->get(route('admin.appointments.index'))->assertOk()->assertSee('Bekleyen Hasta')->assertSee('Onaylı Hasta');
        $this->actingAs($user)->get(route('admin.appointments.index', ['status' => 'confirmed']))->assertOk()->assertSee('Onaylı Hasta')->assertDontSee('Bekleyen Hasta');
        $this->actingAs($user)->get(route('admin.appointments.index', ['q' => 'Bekleyen']))->assertOk()->assertSee('Bekleyen Hasta')->assertDontSee('Onaylı Hasta');
        $this->actingAs($user)->get(route('admin.appointments.show', $pending))->assertOk()->assertSee('Bekleyen Hasta')->assertSee('Bekliyor');

        $this->actingAs($user)->put(route('admin.appointments.update', $pending), ['status' => 'confirmed', 'notes' => 'Saat 10:30 için teyit edildi.'])
            ->assertRedirect(route('admin.appointments.show', $pending));
        $this->assertSame('confirmed', $pending->fresh()->status);
        $this->assertSame('Saat 10:30 için teyit edildi.', $pending->fresh()->notes);

        $this->actingAs($user)->put(route('admin.appointments.update', $pending), ['status' => 'unknown'])->assertSessionHasErrors('status');

        $this->actingAs($user)->delete(route('admin.appointments.destroy', $confirmed))->assertRedirect(route('admin.appointments.index'));
        $this->assertModelMissing($confirmed);
    }

    public function test_demo_seeder_creates_clinic_content_and_is_idempotent(): void
    {
        $this->superAdmin();

        $this->seed(DemoSeeder::class);
        $this->seed(DemoSeeder::class);

        $this->assertDatabaseCount('services', 8);
        $this->assertDatabaseCount('team_members', 6);
        $this->assertDatabaseCount('testimonials', 4);
        $this->assertDatabaseCount('faqs', 5);
        $this->assertDatabaseCount('partners', 6);
        $this->assertDatabaseCount('posts', 4);
        $this->assertDatabaseCount('appointments', 6);
        $this->assertSame(3, Appointment::query()->pending()->count());
        $this->assertNotNull(TeamMember::query()->first()->attribute('Uzmanlık'));
        $this->assertSame('/images/templates/clinic/logo.png', setting('logo'));
        $this->assertSame('/images/templates/clinic/hero.jpg', setting('hero_image'));
        $this->assertSame(
            ['Kardiyoloji', 'Çocuk Sağlığı ve Hastalıkları', 'Ortopedi ve Travmatoloji', 'Dermatoloji'],
            Service::query()->ordered()->limit(4)->pluck('title')->all()
        );
        $this->assertSame('/images/templates/clinic/service-2.jpg', Service::query()->where('title', 'Çocuk Sağlığı ve Hastalıkları')->value('image'));
        $this->assertNull(Service::query()->where('title', 'Diş Sağlığı')->value('image'), 'Eşleşen fotoğrafı olmayan birimler ikon ile gösterilmeli');

        $this->get('/')->assertOk()->assertSee('Kardiyoloji')->assertSee('Uzm. Dr. Ayşe Yılmaz')->assertSee('Allianz')
            ->assertSee('/images/templates/clinic/hero.jpg')->assertSee('/images/templates/clinic/service-3.jpg');
        $this->get('/randevu')->assertOk()->assertSee('Diş Sağlığı');
    }

    public function test_products_section_is_disabled_for_this_template(): void
    {
        Appointment::factory()->count(2)->create();

        $this->get('/urunler')->assertNotFound();
        $this->actingAs($this->superAdmin())->get('/admin/products')->assertNotFound();
        $this->actingAs($this->superAdmin())->get(route('admin.dashboard'))->assertOk()->assertSee('Bekleyen Randevular')->assertSee('Klinik');
    }
}
