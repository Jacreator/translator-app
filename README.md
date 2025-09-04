# About Translator API

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- Simple, fast routing engine - Clean RESTful API endpoints for translation management
- Powerful dependency injection container - Service layer architecture with automatic dependency resolution
- Robust background job processing - Queue-based translation processing for optimal performance
- Expressive, intuitive database ORM - Eloquent models with relationships and advanced querying
- Database agnostic schema migrations - Version-controlled database structure with proper indexing

## Key Features

- Accept multilingual text input via RESTful API endpoints
- Queue-based translation processing using Redis for high performance
- OpenAI GPT integration for accurate language translation
- JSON-based content storage with status tracking
- Comprehensive validation and error handling
- Real-time translation status monitoring

## Environment Setup

## 1. Clone the repository

git clone [https://github.com/Jacreator/translator-app.git](https://github.com/Jacreator/translator-app.git)

## 1b. Change directory to project root directory

cd translation-app

## 2. Install dependencies

composer install

## 3. Copy environment file

cp .env.example .env

## 4. Configure .env file with your settings

- Database credentials
- Redis configuration  
- OpenAI API key

## 5. Generate application key

php artisan key:generate

## 6. Run migrations

php artisan migrate

## 7. Start Redis server

redis-server

## 8. Run Swagger generate

php artisan l5-swagger:generate

## 9. Start queue worker

php artisan queue:work

## 10. Start development server

php artisan serve

## Test

php artisan test

## Documentation

This application documentation is based on Swagger documentation. and can be found [Translators API documentation](http://localhost:8000/api/docs). with the assumption it's been run locally. using php artisan command.

## Security Vulnerabilities

If you discover a security vulnerability within Translator-app, please send an e-mail to James Adakole via [jambone.james82@gmail.com](mailto:jambone.james82@gmail.com). All security vulnerabilities will be promptly addressed.

## License

The Translator-app is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
