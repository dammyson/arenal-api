<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AudienceBranch extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'audience_branch';
    
    protected $fillable = [ "brand_id", "audience_id", "branch_id"];
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
