<?php

namespace App\Models;

use App\Enum\TicketStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property int $id
 * @property string $title
 * @property string $content
 * @property TicketStatusEnum $status
 * @property Customer|null $customer
 * @property Carbon|null $manager_replied_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Ticket extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'customer_id',
        'title',
        'content',
        'status',
        'manager_replied_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => TicketStatusEnum::class,
            'manager_replied_at' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function scopeLastDay(Builder $query): Builder
    {
        return $query->where('created_at', '>=', now()->subDay());
    }

    public function scopeLastWeek(Builder $query): Builder
    {
        return $query->where('created_at', '>=', now()->subWeek());
    }

    public function scopeLastMonth(Builder $query): Builder
    {
        return $query->where('created_at', '>=', now()->subMonth());
    }

    public function scopeByStatus(Builder $query, ?TicketStatusEnum $status): Builder
    {
        if ($status) {
            return $query->where('status', $status);
        }

        return $query;
    }

    public function markAsInNew(): void
    {
        $this->update([
            'status' => TicketStatusEnum::New,
        ]);
    }

    public function markAsInProgress(): void
    {
        $this->update([
            'status' => TicketStatusEnum::InProgress,
        ]);
    }

    public function markAsProcessed(): void
    {
        $this->update([
            'status' => TicketStatusEnum::Processed,
            'manager_replied_at' => Carbon::now(),
        ]);
    }

    public function isNew(): bool
    {
        return $this->status === TicketStatusEnum::New;
    }

    public function isInProgress(): bool
    {
        return $this->status === TicketStatusEnum::InProgress;
    }

    public function isProcessed(): bool
    {
        return $this->status === TicketStatusEnum::Processed;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('ticket_attachments')
            ->useDisk('public')
            ->acceptsMimeTypes([
                'image/jpeg',
                'image/png',
                'application/pdf',
                'text/plain',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ]);
    }
}
