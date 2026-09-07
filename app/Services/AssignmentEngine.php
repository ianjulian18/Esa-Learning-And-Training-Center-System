<?php
namespace App\Services;

use App\Models\AssignmentRule;
use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Log;

class AssignmentEngine
{
    public static function evaluateRule(AssignmentRule $rule)
    {
        Log::info("Evaluating Assignment Rule: {$rule->id}");
        
        $query = User::whereHas('employmentHistories', function ($q) use ($rule) {
            $q->where('status', 'ACTIVE')
              ->where('principal_id', $rule->principal_id);
              
            if ($rule->department_id) {
                $q->where('department_id', $rule->department_id);
            }
            
            $positionIds = $rule->positions->pluck('id')->toArray();
            if (count($positionIds) > 0) {
                $q->whereIn('position_id', $positionIds);
            }
        });
        
        $users = $query->get();
        $count = 0;
        
        foreach ($users as $user) {
            $exists = Enrollment::where('user_id', $user->id)
                ->where('course_id', $rule->course_id)
                ->exists();
                
            if (!$exists) {
                Enrollment::create([
                    'user_id' => $user->id,
                    'course_id' => $rule->course_id,
                    'principal_id' => $rule->principal_id,
                    'assignment_rule_id' => $rule->id,
                    'status' => 'NOT STARTED'
                ]);
                $count++;
            }
        }
        
        Log::info("Rule {$rule->id} assigned to {$count} users.");
        return $count;
    }

    public static function evaluateUser(User $user)
    {
        $employment = $user->employmentHistories()->where('status', 'ACTIVE')->first();
        if (!$employment) return 0;
        
        $rules = AssignmentRule::where('status', 'ACTIVE')
            ->where('principal_id', $employment->principal_id)
            ->where(function ($q) use ($employment) {
                $q->whereNull('department_id')
                  ->orWhere('department_id', $employment->department_id);
            })
            ->whereHas('positions', function ($q) use ($employment) {
                $q->where('positions.id', $employment->position_id);
            })
            ->get();
            
        $count = 0;
        foreach ($rules as $rule) {
            $exists = Enrollment::where('user_id', $user->id)
                ->where('course_id', $rule->course_id)
                ->exists();
                
            if (!$exists) {
                Enrollment::create([
                    'user_id' => $user->id,
                    'course_id' => $rule->course_id,
                    'principal_id' => $rule->principal_id,
                    'assignment_rule_id' => $rule->id,
                    'status' => 'NOT STARTED'
                ]);
                $count++;
            }
        }
        
        return $count;
    }
}
