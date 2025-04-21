<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LinkedInPost extends Model
{
    protected $fillable = [
        'user_id',
        'content',
        'media_path',
        'media_type',
        'status',
        'linkedin_post_id',
        'scheduled_for',
    ];

    protected $casts = [
        'scheduled_for' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function publishToLinkedIn()
    {
        try {
            $linkedInService = app(\App\Services\LinkedInService::class);
            
            $response = $linkedInService->postContent(
                $this->user,
                $this->content,
                $this->media_path,
                $this->media_type
            );

            $this->update([
                'status' => 'published',
                'linkedin_post_id' => $response['id'],
            ]);

            return true;
        } catch (\Exception $e) {
            $this->update(['status' => 'failed']);
            throw $e;
        }
    }
}