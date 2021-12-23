<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Question;
use App\Models\Answer;
use Validator;
use Auth;
use App\Http\Resources\Question as ProductResource;
class QuestionController extends BaseController
{
    public function index(Request $request){
        if($request->has('status')){
            $questions = Question::where(['customer_id'=> Auth::user()->id, 'status'=>$request->status])->orderBy('id', 'DESC')->with('answers')->get();
            
        }else{
            $questions = Question::where('customer_id', Auth::user()->id)->orderBy('id', 'DESC')->with('answers')->get();

        }
        if(count($questions)){
            return $this->sendResponse($questions, 'Questions Found');    
        }
        return $this->sendResponse(NULL, 'Questions Not Found');
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'question' => 'required',
            ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        Question::create([
            'content'       => $request->question,
            'customer_id'   => Auth::user()->id,
        ]);
        return $this->sendResponse('', 'Your Question submitted successfully!');
    }
    public function show($id){
        
        $question = Question::where(['id'=>$id, 'customer_id'=> Auth::user()->id])->with('answers')->first();
        if($question){
            return $this->sendResponse($question, 'Question found!');    
        }
        return $this->sendResponse(NULL, 'Question not found');
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
            'direction' => 2
        ]);
        return $this->sendResponse('', 'Your messages submitted successfully!');
    }
}
