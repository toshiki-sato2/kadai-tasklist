<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task; 
use App\Models\User;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $tasks = [];
        if (\Auth::check()) { // 認証済みの場合
            // 認証済みユーザーを取得
            $user = \Auth::user();
            // ユーザーの投稿の一覧を作成日時の降順で取得
            // （後のChapterで他ユーザーの投稿も取得するように変更しますが、現時点ではこのユーザーの投稿のみ取得します）
            $get_tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
            $tasks = [
                'user' => $user,
                'tasks' => $get_tasks,
            ];
        }
        
        
        return view("dashboard",$tasks);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $task = new Task;
        //$task = "";
        return view("tasks.create", [
            "task" => $task],);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        
        $request->validate([
            "status" => "required|max:10",
            "content" => "required",
            ]);
            
        $request->user()->tasks()->create([
            "content" => $request->content,
            "status" => $request->status,
        ]);
        
        return redirect("/dashboard");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $task = Task::findOrFail($id);

        // 認証済みユーザー（閲覧者）がその投稿の所有者である場合は投稿を閲覧
        if (\Auth::id() === $task->user_id) {
            return view("tasks.show",[
                "task" => $task],);
        }
        
        
        return redirect("/dashboard");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $task = Task::findOrFail($id);
        
        // 認証済みユーザー（閲覧者）がその投稿の所有者である場合は投稿を閲覧
        if (\Auth::id() === $task->user_id) {
            return view("tasks.edit",[
                "task" => $task],);
        }
        
        
        return redirect("/dashboard");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //

        $request->validate([
            "status" => "required|max:10",
            "content" => "required",
            ]);
            
    
        $task = Task::findOrFail($id);
        
        // 認証済みユーザー（閲覧者）がその投稿の所有者である場合は投稿を更新
        if (\Auth::id() === $task->user_id) {
        $task->content = $request->content;
        $task->status = $request->status;            
        $task->save();
            return redirect("/")
                ->with('success','Update Successful');
        }
        
        return redirect("/")->with("update failed");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // idの値で投稿を検索して取得
        $task = Task::findOrFail($id);
        
        // 認証済みユーザー（閲覧者）がその投稿の所有者である場合は投稿を削除
        if (\Auth::id() === $task->user_id) {
            $task->delete();
            return redirect("/")
                ->with('success','Delete Successful');
        }

        // 前のURLへリダイレクトさせる
        return redirect("/")
            ->with('Delete Failed');
    }
}
