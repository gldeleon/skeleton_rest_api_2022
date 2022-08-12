<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Controllers\ResponseController as RC;

class PostController extends RC
{
    public function index(){
        $posts =  Post::all();
        return $this->sendResponse($posts, 'ok');
    }

    public function store(Request $request){
        try{
            $post = new Post();
            $post->title = $request->title;
            $post->body = $request->body;
            if($post->save()){                
                return $this->sendResponse(['id'=>$post->id], 'Post created successfully');
            }
        }catch(\Exception $e){            
            return $this->sendError('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id){
        try{
            $post = Post::findOrFail($id);
            $post->title = $request->title;
            $post->body = $request->body;
            if($post->save()){                
                return $this->sendResponse(['id'=>$post->id], 'Post update successfully');
            }
        } catch(\Exception $e){
            return $this->sendError('error', $e->getMessage());
        }
    }

    public function destroy($id){
        try{
            $post = Post::findOrFail($id);
            if($post->delete()){                
                return $this->sendResponse(['id'=>$post->id], 'Post deleted successfully');
            }
        } catch(\Exception $e){
            return $this->sendError('error', $e->getMessage());
        }
    }
}
