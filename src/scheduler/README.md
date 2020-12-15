# Scheduler

The scheduler class contains the functionality to generate any wordpress cronjobs to kick off the enabled tasks.

## Setup add_schedules

Within the plugin main php file, add an instantiator for the add_schedules class.

``` php
//  ┌─────────────────────────────────────────────────────────────────────────┐
//  │                         The Schedules Filter                            │
//  └─────────────────────────────────────────────────────────────────────────┘
new add_schedules;
```

## Methods

### set_options($options)

This will set the class parameters. Needs to pass an array in the following format :

```php
$scheduler = new scheduler;

$scheduler->set_options([
    'enabled' => true,
    'repeats' => '1hour',
    'starts'  => 1607601861,
    'hook'    => 'run_this_filter_hook',
    'params'  => [
        'param1', 'param2', 'param3'
    ]
]);
```

This is required before the `run()` or `remove_event()` methods.




### run()

The main process that will use the options set and create a new event based upon them.

Example:

```php
$scheduler = new scheduler;

$scheduler->set_options([
    'enabled' => true,
    'repeats' => '1hour',
    'starts'  => 1607601861,
    'hook'    => 'run_this_filter_hook',
    'params'  => [
        'param1', 'param2', 'param3'
    ]
]);

$scheduler->run();
```



### get_event()

Return the event created by the run method.

Example:

```php
$scheduler = new scheduler;

$scheduler->set_options([
    'enabled' => true,
    'repeats' => '1hour',
    'starts'  => 1607601861,
    'hook'    => 'run_this_filter_hook',
    'params'  => [
        'param1', 'param2', 'param3'
    ]
]);

$scheduler->run();
$event_unixtime = $scheduler->get_event();

print_r($event_unixtime, true);

---

[
    'next_scheduled' => 1607601861
]

```

### remove_event()

This allows you to delete an existing event already set.

Example:

```php
$scheduler = new scheduler;

$scheduler->set_options([
    'hook'    => 'run_this_filter_hook',
    'params'  => [
        'param1', 'param2', 'param3'
    ]
]);

$scheduler->remove_event();
$event_unixtime = $scheduler->get_event();

---

[
    'next_scheduled' => ''
]
```