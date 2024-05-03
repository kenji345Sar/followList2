<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserRelation;


class UserRelationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = 10; // 表示するユーザーIDを設定
    
        // ユーザー10がフォローしている人たちのデータを取得
        $following = UserRelation::where('user_id', $userId)
                        ->get(['target_user_id', 'is_following', 'is_blocking']);
    
        // ユーザー10をフォローしている人たちのデータを取得
        $followers = UserRelation::where('target_user_id', $userId)
                        ->get(['user_id', 'is_following', 'is_blocking']);
    
        $relations = [];
        // ユーザーがフォローしている人たちに関するデータを処理するループ
        foreach ($following as $follow) {
            // 初期化と同時にユーザーがフォローしている人たちの関係を設定
            $relations[$follow->target_user_id] = [
                'follow' => $follow->is_following,  // ユーザーが他のユーザーをフォローしているかどうか
                'follower' => false,  // この段階ではフォロワー情報は不明なのでfalse
                'mutual' => false,  // 相互フォローの情報もこの段階では不明なのでfalse
                'blocking' => $follow->is_blocking,  // ユーザーが他のユーザーをブロックしているかどうか
                'blocked_by' => false  // この段階では誰にブロックされているかは不明なのでfalse
            ];
        }

        // ユーザーをフォローしている人たちに関するデータを処理するループ
        foreach ($followers as $follower) {
            // フォロワー情報が$relationsにまだ設定されていない場合、初期設定を行う
            if (!isset($relations[$follower->user_id])) {
                $relations[$follower->user_id] = [
                    'follow' => false,  // フォロー情報はこの段階では不明なのでfalse
                    'follower' => false,  // 初期設定でfalseを指定（この後で真偽値が設定される）
                    'mutual' => false,  // 相互フォロー情報はこの段階では不明なのでfalse
                    'blocking' => false,  // ブロッキング情報はこの段階では不明なのでfalse
                    'blocked_by' => false  // ブロックされている情報はこの段階では不明なのでfalse
                ];
            }
            // フォロワーによるフォロワー情報設定
            $relations[$follower->user_id]['follower'] = $follower->is_following && !$follower->is_blocking;
             // $relations配列にフォロー情報があればそれを使い、なければfalseを設定
            $relations[$follower->user_id]['follow'] = $relations[$follower->user_id]['follow'] ?? false;
            // 互いにフォローし合っていれば相互フォローとしてtrueを設定
            $relations[$follower->user_id]['mutual'] = $relations[$follower->user_id]['follow'] && $follower->is_following;
            // フォロワーによるブロック情報を設定
            $relations[$follower->user_id]['blocked_by'] = $follower->is_blocking && !$follower->is_following;
        }

    
        // 配列をキー（ID）で昇順にソート
        ksort($relations);
    
        return view('user_relations.index', compact('relations'));
    }
    
    
    
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
