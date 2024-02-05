<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserTask extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }


    function getTimeDiff()
    {
        // Assuming $startTime and $endTime are the timestamps retrieved from the database
        $startTime = Carbon::parse($this->start_time);
        $endTime = Carbon::parse($this->end_time);

        // Calculate the time difference
        $timeDifference = $startTime->diff($endTime);

        // Access individual time components (e.g., hours, minutes, seconds)
        $hours = $timeDifference->h;       // Hours
        $minutes = $timeDifference->i;     // Minutes
        $seconds = $timeDifference->s;     // Seconds

        // Format the time difference as a string
       return  $timeDifferenceString = $timeDifference->format('%H:%I:%S');
    }

}
