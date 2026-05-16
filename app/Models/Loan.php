<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
//use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'equipment_id', 'loan_date', 'return_date', 'status'])]

class Loan extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function checkin()
    {
        $this->status = 'checked_in';
        $this->return_date = now();
        
        // Update equipment status
        $this->equipment->update(['status' => 'available']);
        
        return $this->save();
    }

    protected $casts = [
        'loan_date' => 'date',
        'return_date' => 'date',
    ];
}
