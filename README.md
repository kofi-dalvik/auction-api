## Simple Setup
- Clone  this repository and setup database connection configurations
- Run the command ```php artisan auction:setup```  which will **run migrations**, **extract demo images for items**, and **seed database with test data**.
- Login with **user1** or **user2** demo users.
- Optionaly change the ```QUEUE_CONNECTION``` to ```database``` and run ```php artisan queue:work``` to start the queue.
- ```php-zip``` extension is required.