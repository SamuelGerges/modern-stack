<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'due_date',
        'status',
    ];

    public function scopeStatus($query, $status)
    {
        return $status ? $query->where('status',$status) : $query;
    }
    public function scopeDueFrom($query, $date)
    {
        return $date ? $query->whereDate('due_date','>=',$date) : $query;
    }
    public function scopeDueTo($query, $date)
    {
        return $date ? $query->whereDate('due_date','<=',$date) : $query;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
