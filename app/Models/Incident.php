<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Incident extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'description', 'incident_type', 
        'impact', 'urgency', 'priority', 'category', 'status', 
        'corrective_action', 'assigned_department','photo', 'updated_by_user_id','last_edit_details'
    ];

    // Relationship: Incident belongs to a User (creator)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship: Last editor of the incident (updated_by_user_id)
    public function updatedByUser()
    {
        return $this->belongsTo(User::class, 'updated_by_user_id');
    }

    // Automatically set priority based on Impact & Urgency
    public static function calculatePriority($impact, $urgency)
    {
        $priorityMatrix = [
            'Low' => ['Low' => 'Low', 'Medium' => 'Low', 'High' => 'Medium'],
            'Medium' => ['Low' => 'Low', 'Medium' => 'Medium', 'High' => 'High'],
            'High' => ['Low' => 'Medium', 'Medium' => 'High', 'High' => 'High'],
        ];

        return $priorityMatrix[$impact][$urgency] ?? 'Low';
    }
}