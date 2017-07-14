<?php

namespace App\Http\Controllers;

use App\Question;
use App\Helper\AirTable;

use Illuminate\Http\Request;
use App\Helper\Web;
use Validator;
use Flash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function faq(Request $request)
    {
        $question = new Question;

        if (!$request->isMethod('post')) {
            return View('faq', compact('question'));
        }

        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'mobile' => 'required|phone:AN,mobile',
            'text' => 'required'
        ];

        $question->name = $request->get('name');
        $question->email = $request->get('email');
        $question->mobile = $request->get('mobile');
        $question->text = $request->get('text');

        if (Web::validationCheck($request ,$rules)) {
            Web::validation($request, $rules);

            return response()->view('faq', compact('question'))->setStatusCode(400);;
        }

        AirTable::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'mobile' => $request->get('mobile'),
            'text' => $request->get('text'),
        ]);

        $question->save();
    
        Flash::success('Your question has been sent !');
        return redirect('/');
    }
}
