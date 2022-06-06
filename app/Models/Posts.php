<?php

namespace App\Models;

use App\DTO\Post\NewPostDto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\WithData;

/**
 * @method static find(int $id)
 */
class Posts extends Model
{
    use HasFactory;
    use WithData;

    protected string $dataClass = NewPostDto::class;

    protected $fillable = [
        'header',
        'content',
        'image_src',
        'user_id'
    ];

    public function user(){

        return $this->belongsTo(User::class);
    }
}
