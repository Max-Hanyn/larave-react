<?php

namespace App\Models;

use App\Http\Middleware\TrustHosts;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'fistName',
        'lastName',
        'address',
        'avatar'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {

        return $this->belongsTo(Roles::class);
    }

    public function posts()
    {
        return $this->hasMany(Posts::class);
    }

    # встановлюєм відношення багато до багатьох, мої друзі
    public function friendsOfMine()
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id')->withTimestamps();
    }

    # встановлюєм відношення багато до багатьох, друг
    public function friendOf()
    {
        return $this->belongsToMany(User::class, 'friendships', 'friend_id', 'user_id')->withTimestamps();
    }

    # получити всіх друзів
    public function friends(): Collection
    {
        return $this->friendsOfMine()->wherePivot('status', Friendship::USER_FRIEND_CONFIRMED_STATUS)->get()
            ->merge($this->friendOf()->wherePivot('status', Friendship::USER_FRIEND_CONFIRMED_STATUS)->get());
    }

    # запити в друзі
    public function friendRequests(): Collection
    {
        return $this->friendsOfMine()->wherePivot('status', Friendship::USER_FRIEND_PENDING_STATUS)->get();
    }

    # запит на очікування друга
    public function friendRequestsPending(): Collection
    {
        return $this->friendOf()->wherePivot('status', Friendship::USER_FRIEND_PENDING_STATUS)->get();
    }

    # є запит на добавлення в друзі
    public function hasFriendRequestPending(int $userId): bool
    {
        return (bool)$this->friendRequestsPending()->where('id', $userId)->count();
    }

    # получив запит на дружбу
    public function hasFriendRequestReceived(int $userId): bool
    {
        return (bool)$this->friendRequests()->where('id', $userId)->count();
    }

    # добвити друга
    public function addFriend(int $userId): void
    {
        $this->friendsOfMine()->attach($userId);
    }

    # видалити з друзів
    public function deleteFriend(int $userId): void
    {
        $this->friendOf()->detach($userId);
        $this->friendsOfMine()->detach($userId);
    }

    # приняти запит на дружбу
    public function acceptFriendRequest(int $userId): void
    {
        $this->friendRequests()->where('id', $userId)->first()->pivot->update([
            'status' => Friendship::USER_FRIEND_CONFIRMED_STATUS
        ]);
    }

    # користувач уже в друзьях
    public function isFriendWith(int $userId): bool
    {
        return (bool)$this->friends()->where('id', $userId)->count();
    }


}
