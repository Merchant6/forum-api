<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'user_id',
        'title',
        'content',
        'slug',
        'up_votes',
        'down_votes'
    ];

    /**
     * Belongs To User::class
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Belongs To Category::class
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Has Many relation with Thread::class
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function threads()
    {
        return $this->hasMany(Thread::class);
    }

    /**
     * Has Many relation with Reply::class
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    /**
     * Returns this model's data with all related data like threads and replies
     * @return object
     */
    public function getAllRecordsWithRelations()
    {
        $posts = $this->with(['user:id,username'])
        ->with(['threads' => function ($q) {
            $q->select('id', 'post_id', 'user_id', 'content');
            $q->with(['user:id,username']);
            $q->with(['replies:id,post_id,thread_id,user_id,content', 'replies.user:id,username']);
        }])
        ->paginate();
        // get(['id', 'user_id', 'category_id', 'title', 'content', 'slug']);

        return $posts;
    }

    /**
     * Return data of a specific record with all related data like threads and replies
     * @param string $id
     * @return object
     */
    public function getSingleRecordWithRelations(string $id)
    {
        $post = $this->where('id', $id)
        ->with(['user:id,username'])
        ->with(['threads' => function ($q) {
            $q->select('id', 'post_id', 'user_id', 'content');
            $q->with(['user:id,username']);
            $q->with(['replies:id,post_id,thread_id,user_id,content', 'replies.user:id,username']);
        }])
        ->get(['id', 'user_id', 'category_id', 'title', 'content', 'slug']);

        return $post;
    }
}
