<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;


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

}
