<?php

namespace Templates\Clinic\Models;

use App\Models\Service;
use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Templates\Clinic\Database\Factories\AppointmentFactory;

#[UseFactory(AppointmentFactory::class)]
#[Fillable(['name', 'email', 'phone', 'service_id', 'team_member_id', 'preferred_date', 'preferred_time', 'message', 'status', 'notes', 'ip'])]
class Appointment extends Model
{
    use HasFactory;

    public const STATUSES = ['pending' => 'Bekliyor', 'confirmed' => 'Onaylandı', 'cancelled' => 'İptal Edildi'];

    public const TIMES = ['sabah' => 'Sabah (09:00 – 12:00)', 'öğlen' => 'Öğlen (12:00 – 15:00)', 'akşam' => 'Akşam (15:00 – 20:00)'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'preferred_date' => 'date',
        ];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(TeamMember::class, 'team_member_id');
    }

    #[Scope]
    protected function pending(Builder $query): void
    {
        $query->where('status', 'pending');
    }

    #[Scope]
    protected function status(Builder $query, string $status): void
    {
        $query->where('status', $status);
    }

    #[Scope]
    protected function ordered(Builder $query): void
    {
        $query->orderByRaw("case when status = 'pending' then 0 else 1 end")->orderBy('preferred_date')->orderByDesc('id');
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    /** Admin panel badge class for the current status. */
    public function statusBadge(): string
    {
        return self::badgeFor((string) $this->status);
    }

    public static function badgeFor(string $status): string
    {
        return match ($status) {
            'confirmed' => 'badge-green',
            'cancelled' => 'badge-red',
            default => 'badge-yellow',
        };
    }

    public function timeLabel(): ?string
    {
        return $this->preferred_time ? (self::TIMES[$this->preferred_time] ?? $this->preferred_time) : null;
    }
}
