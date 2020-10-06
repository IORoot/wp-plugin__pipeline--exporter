<?php

namespace ex;

//  ┌─────────────────────────────────────────────────────────────────────────┐
//  │                                                                         │░
//  │                                                                         │░
//  │                          Wordpress Interface                            │░
//  │                                                                         │░
//  │                                                                         │░
//  └─────────────────────────────────────────────────────────────────────────┘░
//   ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░

/**
 * This acts as an interface to Wordpress. 
 * 
 * This is to make it easier to replace wordpress for any other CMS if needed.
 * 
 */
trait wp 
{

    /**
     * Returns an array of post objects
     */
    use wp\wp_get_posts;

    /**
     * Returns an array of post objects with all metadata appended to eeach post.
     */
    use wp\wp_get_posts_with_meta;

    /**
     * Returns metadata for a post
     */
    use wp\wp_get_meta;
}