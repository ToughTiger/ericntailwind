<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Bus;
use App\Jobs\PublishLinkedInPostJob;

class LinkedInPost extends Model
{
use HasFactory;


protected $fillable = [
    'user_id',
    'content',
    'media_path',
    'media_type',       // e.g. text|image|article
    'status',           // draft|queued|published|failed
    'scheduled_for',
    'visibility',       // PUBLIC|CONNECTIONS, etc.
    'owner_urn',        // if you store a per-post override; otherwise use user's linkedin_urn
    'external_post_urn',
    'retries',
    'last_error',
];

protected $casts = [
    'scheduled_for' => 'datetime',
    'retries'       => 'integer',
];


public function user(): BelongsTo
{
return $this->belongsTo(User::class);
}


public function scopeDue($query)
{
return $query->whereIn('status', ['draft','queued','failed'])
->whereNotNull('scheduled_for')
->where('scheduled_for', '<=', now());
}


public function queueForPublish(): void
{
    $this->update(['status' => 'queued']);
    PublishLinkedInPostJob::dispatch($this->id)->onQueue('linkedin');
}


// For Filament "Publish Now" button
public function publishToLinkedIn(): void
{
    $this->forceFill(['scheduled_for' => now()])->save();
    $this->queueForPublish();
}
}