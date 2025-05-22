# HRBorderlessApi

# Set Up Instruction
1. Pull the project from Github using the Github url Link
2. Run "Composer install" To install laravel packages, (Make sure you have composer installed on your system)
3. Set up the .env file with your database credentials
4. Run "php artisan migrate" to migrate table schemas
5. Run "php artisan passport:install" to set up the auth passport
6. Select "yes" on the two popup questions
7. Run "php artisan serve" to start up the server



# Brief explanation of your design choices 
1. Separation of Concerns
    Logic is distributed across the Controller, a Job class, and the Model.


2. Queueing File Processing
    Utilizing Laravel Queues for resume and cover letter storage and associated database updates.

3. Dynamic Filtering with Eloquent
    Using the Eloquent methods directly in the controller, or preferably through local scopes on the model.

4. Caching Public Job Listings
    Employing Cache::remember() with a 5-minute TTL for the main public job listings page.

5. Increments for Counters
    Using Eloquent's increment() method for counters 





# Any assumptions or improvements you would make
1. File storage: connecting a third party service for file storage like AWS or cloudinary to help manage our file storage.
