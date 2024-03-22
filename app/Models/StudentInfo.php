<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentInfo extends Model
{
    use HasFactory;

    // ? Table
    protected $table = 'studentinfo';
    protected $with = ['docinfo'];

    public $must_columns = [
        "name-of-county" => "county",
        "sub-counties" => "subcounty",
        "name-of-assessor" => "assessor",
        "name-of-school" => "school",
        "electricity" => "electricity",
        "internet" => "internet",
        "ict-teacher" => "ict",
        "learners-name" => "learner",
        "assessment-number" => "assessment",
        "year-of-birth" => "birth",
        "gender" => "gender",
        "name-of-parent-guardian" => "parent",
        "parent-guardian-phone-number" => "phonenumber",
        "visual-ability" => "visual",
        "reading-ability" => "reading",
        "physical-ability" => "physical",
    ];

    /**
     * Todo: Related to documennt
     */
    public function docinfo()
    {
        return $this->hasOne(SchoolDoc::class, 'id', 'doc');
    }
}
