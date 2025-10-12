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
'user_id','content','media_path','media_type','status','scheduled_for','visibility','owner_urn','external_post_urn','retries','last_error'
];


protected $casts = [
'scheduled_for' => 'datetime',
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
Bus::dispatch(new PublishLinkedInPostJob($this));
}


// For Filament "Publish Now" button
public function publishToLinkedIn(): void
{
$this->scheduled_for = now();
$this->save();
$this->queueForPublish();
}
}