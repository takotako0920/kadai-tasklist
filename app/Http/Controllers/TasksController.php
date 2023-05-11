<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\Task;
use App\Models\User;

class TasksController extends Controller
{
    // getでtasks/にアクセスされた場合の「一覧表示処理」
    public function index()
    {
        $data = [];
        if (\Auth::check()){
            //認証済みユーザの取得
            $user = \Auth::user();
            
            //タスクを取得
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
            
            // メッセージ一覧ビューでそれを表示
            return view('tasks.index', $data);
        }
        else{
            return view('tasks.index');
        }
    }

    // getでtasks/createにアクセスされた場合の「新規登録画面表示処理」
    public function create()
    {
        $task = new Task;
        
        return view('tasks.create',['task' => $task,]);
    }

    // postでtasks/にアクセスされた場合の「新規登録処理」
    public function store(Request $request)
    {
        //バリデーション
        $request->validate([
            'status'=>'required|max:10',
            'content'=>'required|max:225',
        ]);
        
        
        // メッセージを作成
        $request->user()->tasks()->create([
            'status'=>$request->status,
            'content'=>$request->content,
        ]);
        
        /*    
        $task = new Task;
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();
        */
        
        // トップページへリダイレクトさせる
        return redirect('/');
    }

    // getでtasks/（任意のid）にアクセスされた場合の「取得表示処理」
    public function show($id)
    {
        if (\Auth::check()) { // 認証済みの場合
            $user = \Auth::user();
        
            $task = Task::findOrFail($id);
        
            if ($user->id == $task->user_id) {
                // ログインユーザーとタスクの所有者が一致する場合のみ表示
                return view('tasks.show', ['task' => $task]);
            } 
            else {
            return redirect('/');
            }
        }
        else {
            return redirect('/');
        }
    }

    // getでtasks/（任意のid）/editにアクセスされた場合の「更新画面表示処理」
    public function edit($id)
    {
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);

        if (\Auth::user()->id == $task->user_id) {
            // ログインユーザーとタスクの所有者が一致する場合のみ編集ページを表示
            return view('tasks.edit', [
                'task' => $task,
            ]);
        } 
        else {
        return redirect('/');
        }
    }

    // putまたはpatchでtasks/（任意のid）にアクセスされた場合の「更新処理」
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id); // $task変数を追加
        
        if (\Auth::user()->id == $task->user_id) {
        // ログインユーザーとタスクの所有者が一致する場合のみ更新処理を実行
        // 以下、更新処理のコードを追加してください
        
        //バリデーション
        $request->validate([
            'status'=>'required|max:10',
            'content'=>'required|max:225',
        ]);
        
        // メッセージを更新
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();
        
        // トップページへリダイレクトさせる
        return redirect('/');
        }
        else {
        return redirect('/');
        }
    }

    // deleteでtasks/（任意のid）にアクセスされた場合の「削除処理」
    public function destroy($id)
    {
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);
        
        if (\Auth::user()->id == $task->user_id) {
        // ログインユーザーとタスクの所有者が一致する場合のみ削除処理を実行
        // 以下、削除処理のコードを追加してください
        
        // メッセージを削除
        $task->delete();
        
        // トップページへリダイレクトさせる
        return redirect('/');
        }
        else {
        return redirect('/');
        }
    }
   
}