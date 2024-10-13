## Installation Steps

This task is completed using Laravel. So, regular laravel installation steps are compatible to run this project locally. 

For better illustration, we can follow steps described below.

## Pulling Codebase into Local

Clone this repository at our local machine then navigate to directory.

## Preparing Local Environment

Without thinking much, it's better to use docker. For running this project **[Docker Application](https://github.com/aschmelyun/docker-compose-laravel)** can be used. 
We can also get fully functional configured docker setup from `docker-setup` branch.

First of all we need **[Docker Desktop](https://www.docker.com/products/docker-desktop/)** to be installed. Download and Install as per our OS.

Go to cloned repository's directory and switch to `docker-setup` branch and pull from origin. Then delete `.git` file from the directory to avoid VCS confusion. Go to `/src` directory, delete and clean the directory.

Again clone the repository into `/src` directory. Run: `git clone git@github.com:wadudrahman/techzu_task.git .`

## Build and Run Docker

Now we need to build our containers and run the docker. For that, go back to a level up of `/src` and run `docker compose build --no-cache`, make sure your Docker Desktop application is running.

Once the build is complete, run `docker compose up -d`. Our application is now running at `localhost:25080`.

## Deploy Laravel Application

Now we need to complete our laravel setup to run the application fully. Copy `.env.example` into `.env`. Then run below commands one by one:

`docker compose run --rm composer install` 
`docker compose run --rm artisan key:generate`
`docker compose run --rm artisan migrate --seed`

Congrats, we have successfully run our application at `localhost:25080`.

## Testing E-mail

For automate testing, we need to configure supervisor for this project in our local machine. By default, it's scheduled to send out mails to guests 15 minute prior of the event.
But to test the feature, we can simply run `docker compose run --rm artisan send:event-reminder` and check the inbox at `localhost:8025`.
