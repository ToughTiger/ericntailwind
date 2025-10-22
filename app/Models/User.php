<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Post;
use App\Models\CaseStudy;


class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasPanelShield;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'image_url',
        'bio',
        'social',
        'address',
        'city',
        'state',
        'country',
        'zipcode',
        'password',
        'is_admin',
        'linkedin_access_token',
        'linkedin_refresh_token',
        'linkedin_token_expires_at',
        'linkedin_user_id',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return str_ends_with($this->email, '@ericsolutions.com');
    }
    public function posts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Post::class);
    }

       public function caseStudies(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(related: CaseStudy::class);
    }

    public function linkedInPosts()
        {
        return $this->hasMany(\App\Models\LinkedInPost::class);
        }


        public function hasLinkedIn(): bool
        {
        return ! empty($this->linkedin_access_token) && ! empty($this->linkedin_urn);
        }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
