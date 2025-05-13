<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use DateTimeInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\NewAccessToken;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
        'phone',
        'status',
        'profile_image'
    ];

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
    ];

        /**
     * Create a new personal access token for the user.
     *
     * @param  string  $name
     * @param  array  $abilities
     * @param  \DateTimeInterface|null  $expiresAt
     * @return \Laravel\Sanctum\NewAccessToken
     */
    public function getOrCreateToken(string $name, array $abilities = ['*'], DateTimeInterface $expiresAt = null)
    {
        $existingToken = $this->tokens()
            ->where('abilities', json_encode($abilities))
            ->where('expires_at', '>', now())
            ->first();

        if ($existingToken) {
             // You cannot return the hashed token directly; you need to regenerate it.
            $plainTextToken = $this->generateTokenString();

            // Update the existing token with a new plain text value
            $existingToken->update([
                'token' => hash('sha256', $plainTextToken),
            ]);

            return new NewAccessToken($existingToken, $existingToken->getKey().'|'.$plainTextToken);
            //return new NewAccessToken($existingToken, $existingToken->getKey().'|'.$existingToken->token);
        }
        return $this->createToken($name, $abilities, $expiresAt);
    }

    protected $appends = ['profile_image_url'];

    public function getProfileImageUrlAttribute()
    {
        if ($this->profile_image) {
            return url(Storage::url($this->profile_image));
        }

        // fallback image or null
        return url('/images/default-avatar.png'); 
    }

}
