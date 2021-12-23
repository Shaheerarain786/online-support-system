<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Question;
use App\Models\Answer;
use Validator;

use App\Http\Resources\Question as ProductResource;

class SupportTeamController extends BaseController
{
    public function index(Request $request){
        
        if($request->has('status')){
            $questions = Question::where('status',$request->status)->orderBy('id', 'DESC')->with('answers')->get();
            
        }else{
            $questions = Question::orderBy('id', 'DESC')->with('answers')->get();
        
        }
        if(count($questions)){
            return $this->sendResponse($questions, 'Questions Found');    
        }
        return $this->sendResponse(NULL, 'Questions Not Found');
    }
    public function singleQuestion($id){
        
        $question = Question::where('id', $id)->with('answers')->first();
        if($question){
            return $this->sendResponse($question, 'Question found!');    
        }
    }
    public function reply(Request $request){
        $validator = Validator::make($request->all(), [
            'answer'        => 'required',
            'question_id'   => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        Answer::create([
            'question_id'=> $request->question_id,
            'content'   => $request->answer,
            'direction' => 1
        ]);
        $question = Question::where('id', $request->question_id)->first();
        $question->status = 'In Progress';
        $question->update();
        return $this->sendResponse('', 'Your messages submitted successfully!');
    }
    public function changeStatus(Request $request){
        $validator = Validator::make($request->all(), [
            'status'        => 'required',
            'question_id'   => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $question = Question::where('id', $request->question_id)->first();
        $question->status = $request->status;
        $question->update();
        return $this->sendResponse('', 'Question Status submitted Successfully!');
    }
    public function customerQuestions(Request $request){
        $validator = Validator::make($request->all(), [
            'term'        => 'required',
            
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $term = $request->term;
        $questions = Question::whereHas('customer', function($q) use ($term){
            $q->where('name', 'like', '%'.$term.'%');
        })->orderBy('id', 'DESC')->with('answers')->get();
        if(count($questions)){
            return $this->sendResponse($questions, 'Questions Found');    
        }
        return $this->sendResponse(NULL, 'Questions Not Found');
    }
}
