## How to

In this project you can set notification via email or telegram messanger. All the info and how-to are available on the Channels page.

In order to run this project:

- make sure your docker are running;
- pull this repo to your local machine;
- navitage to the project folder;
- execute `./start.sh` - it will bring all the dependencies, and run queue workers;
- open http://localhost/ in your browser and register your account;
- add cities on Cities page
- open new terminal and navigate to project folder and run `./vendor/bin/sail shell`, then run `php artisan app:check-weather-command` (this command will schedule a notifications job, keep in mind, you need to trigger that command every time you wanna receive a notification, there is no cron setup)
