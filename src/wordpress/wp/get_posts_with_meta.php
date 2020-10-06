<?php

namespace ex\wp;

trait wp_get_posts_with_meta {

    
    public static function wp_get_posts_with_meta($args)
    {

        $posts = get_posts($args);

        foreach($posts as $key => $post)
        {
            // Post
            $result[$key] = (array) $post;

            // Meta
            $result[$key] = array_merge($result[$key], get_post_meta($post->ID));

            // Attachment
            $attachment = get_post_meta($result[$key]['_thumbnail_id'][0]);
            $attachment['_wp_attachment_metadata'] = unserialize($attachment['_wp_attachment_metadata'][0]);
            $result[$key] = array_merge($result[$key], $attachment);

            // Attachment SRC
            $src['_wp_attachment_src'] = wp_get_attachment_image_src($result[$key]['_thumbnail_id'][0]);
            $result[$key] = array_merge($result[$key], $src);
            
        }

        return $result;
    }

}