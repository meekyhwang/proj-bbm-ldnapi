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
            if($meta_item->meta_key == 'template_list'){
                $metaArray[$meta_item->meta_key] = $this->resolveTemplateBundles(unserialize($meta_item->meta_value));
            }elseif($meta_item->meta_key == 'repeat'){
	            $metaArray[$meta_item->meta_key] = unserialize($meta_item->meta_value);
            }else{
	            $metaArray[$meta_item->meta_key] = $meta_item->meta_value;
            }
        }
        $postArray['meta'] = $this->resolveTemplateMeta($metaArray);

        $return = new Collection($postArray);
        return $return;
    }

	/**
	 * @param string $post_id
	 *
	 * @return mixed
	 */
    protected function getFieldsByPostId($post_id ='',$meta_key='')
    {
	    $additional_where ='';
    	if($meta_key !== ''){
    	    $additional_where = ' AND meta_key = "'.$meta_key.'"';
	    }

        $fields = DB::table('wp_postmeta')
        ->select('meta_key', 'meta_value')
        ->whereRaw('post_id="'.$post_id.'" AND meta_key NOT REGEXP "^(field_|_).*"' . $additional_where)
        ->get();

	    return $fields;
    }

	/**
	 * @param string $post_id
	 *
	 * @return mixed
	 */
    protected function verifyPostType($post_ids)
    {

	    $post_types = DB::table('wp_posts')
		    ->select('ID','post_type')
		    ->whereIn('ID', $post_ids)
		    ->get();

	    return $post_types;
    }

	/**
	 * @param $template_lists
	 *
	 * @return array
	 */
    protected function resolveTemplateBundles($template_lists)
    {
    	$final_template_list = [ ];
    	$post_types = $this->verifyPostType($template_lists);
		foreach($post_types as $post){
			if('template_bundle' == $post->post_type){
				$template_bundle_list = $this->getFieldsByPostId($post->ID, 'template_list');
				foreach(unserialize($template_bundle_list[0]->meta_value) as $template){
                    $final_template_list[$template] = $this->getPostsByPostId($template, 'template');
                }
			}else{
				$final_template_list[$post->ID] = $this->getPostsByPostId($post->ID, 'template');
			}
		}

		return $final_template_list;
    }

    protected function resolveTemplateMeta($metaArray){
        $cleaned_meta = [ ];
        if(!empty($metaArray)){

            foreach($metaArray as $key => $value){
                if($key=='components'){
                    continue;
                }else{
                    preg_match('/^(components_0_)(.*)/',$key, $results);
                    if(!empty($results)){
                        if(!empty($results[2])){
                            $cleaned_meta[$results[2]] = $value;
                        }
                    }else{
                        $cleaned_meta[$key] = $value;
                    }
                }
            }
        }
        return $cleaned_meta;
    }
}
