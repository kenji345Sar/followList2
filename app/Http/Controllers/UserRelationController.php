<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserRelation;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRelationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = 10; // 表示するユーザーIDを設定
    
        // フォローしているユーザーのデータを取得
        $following = UserRelation::where('user_id', $userId)
                            ->get(['target_user_id', 'is_following', 'is_blocking'])
                            ->keyBy('target_user_id');
    
        // フォロワーのデータを取得
        $followers = UserRelation::where('target_user_id', $userId)
                            ->get(['user_id', 'is_following', 'is_blocking'])
                            ->keyBy('user_id');

        // 全ユーザーIDの一覧を取得
        $allUserIds = $following->keys()->merge($followers->keys())->unique();
        
        // 全データを統合
        $allRelations = $allUserIds->mapWithKeys(function ($id) use ($following, $followers) {
            $isFollowing = $following->has($id) && $following[$id]->is_following;
            $isFollower = $followers->has($id) && $followers[$id]->is_following;
            $isMutual = $isFollowing && $isFollower;
            $isBlocking = $following->has($id) ? $following[$id]->is_blocking : false;
            $isBlockedBy = $followers->has($id) ? $followers[$id]->is_blocking : false;
    
            return [$id => [
                'follow' => $isFollowing ? '○' : '',
                'follower' => $isFollower ? '○' : '',
                'mutual' => $isMutual ? '○' : '',
                'blocking' => $isBlocking ? '○' : '',
                'blocked_by' => $isBlockedBy ? '○' : ''
            ]];
        })->sortKeys();  // IDでソート
    
        // ページネーションの設定
        $perPage = 3;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $allRelations->slice(($currentPage - 1) * $perPage, $perPage)->all();
    
        // LengthAwarePaginatorを使ってページネーションを適用
        $paginatedItems = new LengthAwarePaginator(
            $currentItems, 
            $allRelations->count(), 
            $perPage, 
            $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            ]
        );
    
        return view('user_relations.index', ['relations' => $paginatedItems]);
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
