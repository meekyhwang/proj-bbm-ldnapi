<?php

namespace App\Models;
// use Illuminate\Support\Facades\DB;

class Post
{
    /**
     * Fetch posts
     *
     * @return mixed
     */
    protected function getPostsByPostType($post_type ='')
    {
        $posts = app('db')->select("SELECT * FROM wp_posts WHERE post_type = {$post_type}");
        // $posts = DB::table('wp_posts')->where('post_type', $post_type)->get();
        return $posts;
    }

    /**
     * Fetch post by post id
     *
     * @return mixed
     */
    protected function getPostsByPostId($post_id = '')
    {
        $posts = DB::table('wp_posts')->where('ID', $post_id)->get();
        return $posts;
    }

    protected function getFieldsByPostId($post_id ='')
    {
        $fields = DB::table('wp_postmeta')
        ->select('meta_key', 'meta_value')
        ->where(
            ['post_id', '=',$post_id ],
            ['meta_key', 'NOT LIKE', '_%' ],
            ['meta_key', 'NOT LIKE', 'field_%']
            )->get();
            return $fields;
    }
}
