<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Tweet;
use Illuminate\Support\Str;


class TweetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $tweets = Tweet::getAllOrderByUpdated_at();
        return view('tweet.index',compact('tweets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('tweet.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // app/Http/Controllers/TweetController.php

public function store(Request $request)
{
    // バリデーション
    $validator = Validator::make($request->all(), [
        'tweet' => 'required | max:191',
        'description' => 'required',
    ]);


    // バリデーション:エラー
    if ($validator->fails()) {
        return redirect()
        ->route('tweet.create')
        ->withInput()
        ->withErrors($validator);
    }

    $tweet = new Tweet();

    $form = $request->all();
    
    if(isset($form['path'])){
        // ddd($form);
        // ddd($request->all());
        $file = $request->file('path');
        //拡張子取得
        $extension = $file->getClientOriginalExtension();
        //ファイルの名前作成
        $file_token = Str::random(32);
        $filename = $file_token . '.' . $extension;
        $form['path'] = $filename;
        $file->move('uploads/tweets', $filename);
        // ddd($margename);
        $request->merge(['path'=>$filename]);
        // ddd(merge($margename));
    }
    
    // create()は最初から用意されている関数
    // 戻り値は挿入されたレコードの情報
    $result = Tweet::create($request->all());

    $result->update(['path'=>$filename]);
    // ddd(Tweet::create($request->all()));
    // ルーティング「tweet.index」にリクエスト送信（一覧ページに移動）
    return redirect()->route('tweet.index');

}



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $tweet = Tweet::find($id);
        return view('tweet.show',compact('tweet'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
