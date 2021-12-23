<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use Carbon\Carbon;
class CronJobController extends Controller
{
    public function index(){
        $questions = Question::where('status', 'In Progress')->whereHas('latestReply')->with('latestReply')->get();
        
        foreach($questions as $q){
            $t1 = Carbon::parse($q->latestReply->created_at);
            $t2 = Carbon::now();
            $hours = $t1->diffInHours($t2);
            if($q->latestReply->direction == 1 && $hours >=24){
                $q->status = "Answered";
                $q->update();
            }
        }
    }
}
