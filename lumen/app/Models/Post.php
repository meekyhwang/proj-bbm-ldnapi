<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class Post
{
    /**
     * Fetch posts
     *
     * @return mixed
     */
    protected function getPostsByPostType($post_type ='')
    {
        // $posts = app('db')->select("SELECT * FROM wp_posts WHERE post_type = '{$post_type}'");
        $posts = DB::table('wp_posts')->where('post_type', $post_type)->get();
        return $posts;
    }

    /**
     * Fetch post by post id
     *
     * @return mixed
     */
    protected function getPostsByPostId($post_id = '', $post_type = 'template')
    {
        $posts = DB::table('wp_posts')
        ->where([
            ['post_type', '=', $post_type],
            ['ID','=', $post_id]])
        ->first();

        $postArray =  (Array)$posts;
        $meta = $this->getFieldsByPostId($post_id);
        $metaArray = [ ];
        foreach($meta as $meta_item){
            if($meta_item->meta_key == 'template_list' || $meta_item->meta_key == 'repeat'){
                $metaArray[$meta_item->meta_key] = unserialize($meta_item->meta_value);
            }else{
                $metaArray[$meta_item->meta_key] = $meta_item->meta_value;
            }
        }

        $postArray['meta'] = $metaArray;

        $return = new Collection($postArray);
        return $return;
    }

    protected function getFieldsByPostId($post_id ='')
    {
        $fields = DB::table('wp_postmeta')
        ->select('meta_key', 'meta_value')
        ->whereRaw('post_id="'.$post_id.'" AND meta_key NOT REGEXP "^(field_|_).*"')
        ->get();

            return $fields;
    }
}
