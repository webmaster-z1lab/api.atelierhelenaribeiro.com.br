<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Modules\Message\Models\Message;
use Modules\Project\Models\Comment;
use Modules\Project\Models\Project;
use Modules\Project\Models\Step;
use Modules\Project\Models\Task;
use Modules\User\Models\User;

/**
 * Class File
 *
 * @package App\Models
 * @property-read string                     id
 * @property string                          name
 * @property string                          extension
 * @property int                             size_in_bytes
 * @property string                          size
 * @property string                          path
 * @property string                          icon
 * @property string                          visibility
 * @property-read string                     url
 * @property \Modules\Project\Models\Comment comment
 * @property \Modules\Message\Models\Message message
 * @property \Modules\Project\Models\Project project
 * @property \Modules\Project\Models\Step    step
 * @property \Modules\Project\Models\Task    task
 * @property-read \Carbon\Carbon             created_at
 * @property-read \Carbon\Carbon             updated_at
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\App\Models\File newModelQuery()
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\App\Models\File newQuery()
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\App\Models\File query()
 * @mixin \Eloquent
 */
class File extends Model
{
    protected $fillable = [
        'name',
        'extension',
        'path',
        'size_in_bytes',
        'visibility'
    ];

    protected $attributes = [
        'visibility' => User::ADMIN
    ];

    /**
     * @return string
     */
    public function getUrlAttribute()
    {
        return \Storage::url($this->attributes['path']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function step()
    {
        return $this->belongsTo(Step::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function message()
    {
        return $this->belongsTo(Message::class);
    }
}
