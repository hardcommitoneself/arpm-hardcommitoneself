### ARPM No5

##### Explain this code

`It seems like this code base is specifically being used for task scheduling feature.`

1. `Schedule::command('app:example-command')`
   It sepcifies that you want to schedule a command to be executed. Here, the command is app:example-command, you can just call this command by running - `phhp artisan app:example-command`.

2. `->withoutOverlapping()`
   It ensures that if the scheduled command is still running when the next scheduled execution is due, the next one will not be started.
   It prevents overlapping executions of the command, which can be useful to avoid conflicts.
3. `->hourly()`
   It schedules the command to run once every hour.
4. `->onOneServer()`
   It indicates that the command should only run on one server in a multi-server setup. It is useful in cloud env or when using multi servers to ensure that the scheduledcommand is not executed multiple times across different servers.
5. `->runInBackground()`
   It allows the command to run in the background, meaning that the command execution will not block the scheduler from the running other tasks.

##### What is the difference between the Context and Cache Facades? Provide examples to illustrate your explanation.

The `Cache` facade providdes a simple interface for interacting with the caching system in Laravel. It allows you to store, retrieve, and manipulate cached data efficiently.

The `Context` facade is not a built-in Laravel feature like the Cache facade. It may refer to a custom implementation or third-party pacakge.

So I think the main difference...
It lies in their purpose and functionality. as the Cache facade is specifically designed for caching operations, while Context facade would depend on the context it is used in.

##### What's the difference between $query->update(), $model->update(), and $model->updateQuietly() in Laravel, and when would you use each?

- We should use `$query->update()` when we want to update multiple records at once without loading them into memory and do not need to trigger events.
- We should use `$model->update()` when we have a specific model instance and want to update it's attrs while also triggering any necessary events.
- We should use `$model->updateQuitely()` when we need to update a model instance but want to prevent any events from firing, ensuring that no additional processing occurs.
