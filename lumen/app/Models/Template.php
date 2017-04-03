<?php

namespace App\Models;
use App\Models\Post;

class Template extends Post
{
    /**
     * Fetch playlists
     *
     * @return mixed
     */
    public function getTemplates()
    {
        return $this->getPostsByPostType('template');
    }

	/**
	 * Fetch template by Id
	 *
	 * @return mixed
	 */
	public function getTemplateById($template_id='')
	{
		return $this->getPostsByPostId($template_id, 'template');
	}
}
