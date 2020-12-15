# The housekeep

This functionality allows you to clean up any posts/images/metadata that you no longer need or want. Useful for keeping the server clean and not overloaded.

## actions

These are all the types of actions you can perform on the query passed through the interface.

## Development

This class and the actions are self-contained and isolated. Therefore, they can be reused on other plugins quite easily.

The class takes the following options to configure it:

```php
    $housekeep = new \ue\housekeep;

    $options = [
        'enabled' => true,
        'action' => 'bin_all',
        'query' => "[
            'post_type' => 'post',
        ]",
    ];

    $housekeep->set_options($options);

    $housekeep->run();
```

## Settings

1. `enabled` : Whether to run this housekeeping script or not.
1. `action` : This is the name of the action to run. Currently:
    1. `bin_all` : Soft-delete posts and images.
    1. `bin_posts` : Soft-delete posts, keep any images.
    1. `delete_all` : Permanently delete posts and images.
    1. `delete_posts` : Permamnently delete posts, keep images.
    1. `none` : Do nothing, keep posts and images. 
1. `query` : The `WP_QUERY` of the posts to select to perform the housekeeping on.


## Custom Options

Try to configure the `set_options()` method to conform to your data.