<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Feedback;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class FeedbackController extends Controller
{
    //
    function index()
    {

        $feedback = Feedback::with("users", "votes")->latest('id')->paginate(10);

        return view('dashboard', compact("feedback"));
    }

    function add()
    {
        return view("add_feedback");
    }

    function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => "required",
            'category' => "required",

        ]);

        $feeback = new Feedback();
        $feeback->user = Auth()->user()->id;
        $feeback->title = $request->title;
        $feeback->description = $request->description;
        $feeback->category = $request->category;
        if ($feeback->save()) {
            return redirect()->route('dashboard');
        } else {
            return redirect()->back()->with("error", "please try Again");
        }
    }

    function vote($id)
    {
        try {
            DB::beginTransaction();
            $vote = new Vote();
            $vote->user = Auth()->user()->id;
            $vote->feedback = $id;
            if ($vote->save()) {
                $feeback = Feedback::Find($id);
                $feeback->increment("vote", 1);
                if (!$feeback->save()) {
                    throw  new \Exception("Error Update Vote");

                }
                DB::commit();
                return redirect()->back();

            } else {
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back();
        }

    }

    function unvote($id)
    {
        try {
            DB::beginTransaction();
            $vote = Vote::where("feedback", $id)->where("user", Auth()->user()->id);
            if ($vote->delete()) {

                $feeback = Feedback::Find($id);
                $feeback->decrement("vote", 1);
                if (!$feeback->save()) {
                    throw  new \Exception("Error Update Vote");

                }
                DB::commit();
                return redirect()->back();

            } else {
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back();
        }

    }

    function comment_store(Request $request, $id)
    {
        $request->validate([
            "comment" => "required",
        ]);
        $comment = new Comment();
        $comment->user = Auth()->user()->id;
        $comment->feedback = $id;
        $comment->comment = $request->comment;
        $comment->mention = $request->mention;
        if ($comment->save()) {
            return redirect()->back()->with("success", "Comment Submit SuccessFully");
        } else {
            return redirect()->back()->with("error", "Someting Wrong");
        }

    }


    function my_feedback()
    {
        $feeback = Feedback::with("users", "votes")->where("user", Auth()->user()->id)->latest('id')->paginate(10);
        return view('my_feedback', compact("feeback"));
    }

    function getMentions()
    {
//        $term = $request->input('term');
        $users = User::where("id", "!=", Auth()->user()->id)->latest("id")->get();

        return response()->json($users);
    }

    function my_notification()
    {
        $notification = Comment::with("users")->with("feedbacks")->where("mention", Auth()->user()->id)->latest("id")->get();

        return view("my_notification", compact("notification"));
    }

    function view_feedback($id)
    {
        $feeback = Feedback::with("users", "votes")->where("id", $id)->latest('id')->paginate(10);
        return view('my_feedback', compact("feeback"));
    }

    function edit($id)
    {
        $feedback = Feedback::Find($id);
        return view("admin.edit_feedback", compact("feedback"));
    }

    function update(Request $request, $id)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => "required",
            'category' => "required",

        ]);

        $feeback = Feedback::Find($id);
        $feeback->title = $request->title;
        $feeback->description = $request->description;
        $feeback->category = $request->category;
        if ($feeback->save()) {
            return redirect()->route('admin.dashboard')->with("success", "Feedback Update Successfully");
        } else {
            return redirect()->back()->with("error", "please try Again");
        }
    }

    function delete($id)
    {
        $feedback = Feedback::Find($id);
        if ($feedback->delete()) {
            return redirect()->route('admin.dashboard')->with("success", "Feedback Delete Successfully");
        } else {
            return redirect()->back()->with("success", "please try Again");
        }
    }


}
