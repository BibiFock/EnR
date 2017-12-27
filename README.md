# EnR

## Dependencies

In order to run this project, you'll need a database.
You can either install one on your laptop or use docker to start a mariadb container:

    docker-compose up

## Installation

Once you have a running database, clone the project and configure your environment:

    cp .env.example .env

Don't forget to edit the newly created `.env` file to edit your configuration if needed.
You can now install the project:

    make install

It will install all soft dependencies for both API & front.
Once done, it will confiure your database.

In order to run the application, you'll need a web server to serve the API.
Don't hesitate to use the PHP builtin web server:

    php -S localhost:6869 -t public

You'll also need to serve assets using npm:

    npm run start

You can now go to http://127.0.0.1:6868/!
