## Q-Server  
A simple jobs queue for easy projects like a home server. The most minimal system requirements is only php 7.4+. A queue can use different drivers to store information. Currently only works with files. I would be glad for your help in connecting drivers

## Why?
For servers with limited resources and simple projects. I am using this queue for my home server. When I need to give the server a long task like a long processing or downloading a file and go about my business.

## Features
* Lightweight and ready to go out of the box
* The job gets queued even if the worker is not currently running. The worker will do the job as soon as it is started
* Minimum setup: Only php is needed to work. Keeping a worker running is also easy without special tools: you can add a lock file parameter and write the task to cron.

## Not for big projects!
Do not use this project for large projects. It's not designed to handle a lot of tasks, workers and servers

## Minimal system requirements  
* PHP 7.4+  

it is all ;)

## Quick start
1. Run `git clone git@github.com:Catsys/q-server.git` in work dir or download [zip file](https://github.com/Catsys/q-server/archive/refs/heads/master.zip) and unzip in work dir.
2. Go to work dir and run worker `php q-server.php worker`. The worker must always be running. You can use [supervisor](http://supervisord.org/), [crontab](https://en.wikipedia.org/wiki/Cron) or other to keep things running
3. Run job from your project like `cd /path/to/q-server-project/ && php q-server.php put --cmd='php /path/to/your-project/script.php'`

## How it works
The main idea is that full-fledged bash commands are sent to the queue. This makes it more flexible given that it's designed to run on a single server.

### Run listener
`php q-server.php worker`

### Put command in queue
`php q-server.php put --cmd='cd path/to/project/ && php command.php' --comment='run my command'`. It return unique job process ID. Soon it will be possible to control the process by this id. But right now it only talks about successfully placing the job in the queue


## Commands
| Command  | Description                                                                                                               |
|----------|---------------------------------------------------------------------------------------------------------------------------|
| `put`    | put job in queue                                                                                                          |  
| `worker` | run listener. Listens to the queue and executes the jobs. Your task is to maintain the constant performance of the worker |  
| `stop`   | stop all workers                                                                                                          |  
| `list`   | show a list of all jobs in the queue                                                                                      |  
| `help`   | run help command                                                                                                          |  

## "put" command parameters:
| Parameter     | Default             | Description                    |
|---------------|---------------------|--------------------------------|
| `cmd`         | not required        | command for run on             |
| `comment`     | null                | just comment for list command  |
| `id`          | generated by script | command id. optional           |
| `delay`       | 0                   | delay before run in seconds    |
| `tries`       | 1                   | tries for run before kill job  |
| `tries_delay` | 180 (3 min)         | delay between tries in seconds |
 
## "worker" command parameters:
| Parameter            | Default | Description                                                                          |
|----------------------|---------|--------------------------------------------------------------------------------------|
| `single&#x2011;mode` | `false` | Prevent second instance from starting                                                |
| `silent&#x2011;mode` | `false` | All output send to log file.                                                         |
| `sleep`              | 3       | delay between job searches in seconds. Reduces the number of requests to the storage |

    
## How to run in crontab
1. Run `crontab -e`
2. insert `* * * * * cd PATH_TO_WORKER && php q-server.php worker --single-mode=true` where PATH_TO_WORKER replaced to worker dir in your server.

## TODO
* Mysql driver
* More drivers for the driver god
* Once run job in worker by id
* Kill job by id
* Status command
