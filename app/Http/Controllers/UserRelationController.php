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
    
        // ユーザーがフォローしている人たちのデータを取得し、加工
        $following = UserRelation::where('user_id', $userId)
                        ->get(['target_user_id', 'is_following', 'is_blocking'])
                        ->keyBy('target_user_id')
                        ->map(function ($item) {
                            return [
                                'follow' => $item->is_following,
                                'follower' => false,
                                'mutual' => false,
                                'blocking' => $item->is_blocking,
                                'blocked_by' => false  // 初期状態では不明
                            ];
                        });

        // ユーザーをフォローしている人たちのデータを取得し、加工
        $followers = UserRelation::where('target_user_id', $userId)
                        ->get(['user_id', 'is_following', 'is_blocking'])
                        ->each(function ($item) use ($following) {
                            // 既存のデータの更新、または新しいデータの追加
                            $relation = $following->get($item->user_id, [
                                'follow' => false,
                                'follower' => $item->is_following && !$item->is_blocking,
                                'mutual' => false,
                                'blocking' => false,
                                'blocked_by' => false
                            ]);

                            $relation['follower'] = $item->is_following && !$item->is_blocking;
                            $relation['mutual'] = $relation['follow'] && $item->is_following;
                            $relation['blocked_by'] = $item->is_blocking && !$item->is_following;

                            $following->put($item->user_id, $relation);
                        });

        // 配列をキー（ID）で昇順にソート
        $following = $following->sortKeys();

        return view('user_relations.index', ['relations' => $following]);


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
