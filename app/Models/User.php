<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Role;
use App\Models\Submission;
use App\Models\Departement;
use Database\Factories\UserFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;


#[Fillable(['name', 'email', 'password', 'departement_id', 'no_badge', 'role_id', 'avatars'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable 
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $with = ['departement', 'role'];

    public function departement(): BelongsTo
    {
        return $this->belongsTo(Departement::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    protected function initialName() :Attribute
    {
        return Attribute::make(
            get: function() {
                return collect(explode(' ', $this->name))
                    ->map(fn($word) => strtoupper(substr($word, 0, 1)))
                    ->join('');
            }
        );
    }
}
