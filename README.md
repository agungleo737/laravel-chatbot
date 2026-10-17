# Portfolio Chatbot Backend

Backend API for the chatbot integrated into my personal portfolio website. The chatbot allows visitors to ask questions about my background, technical skills, and projects.

This project works alongside the portfolio frontend to provide an interactive way for visitors to learn more about me.

## Related Project

- **Frontend:** [portofolio-web](https://github.com/agungleo737/portofolio-web)
- **Live Portfolio:** [View Website](https://portofolio-web-two-theta.vercel.app/)
- **Backend:** This repository

## Features

- Backend service for the portfolio chatbot.
- Processes questions submitted by visitors through the portfolio website.
- Provides responses related to my profile, skills, and projects.
- Integrates with the portfolio frontend through backend requests.

## Tech Stack

- **PHP**
- **Laravel**
- **Composer**
- **JavaScript / Vite** for frontend assets used by the Laravel application

## Requirements

Before running the project, make sure you have:

- PHP compatible with the version specified in `composer.json`
- Composer
- Node.js and npm
- A supported database, if required by the current application configuration
- API credentials, if the chatbot uses an external AI service

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/agungleo737/laravel-chatbot.git
cd laravel-chatbot
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Configure Environment Variables

Create a local environment file:

```bash
cp .env.example .env
```

On Windows, you can also copy `.env.example` to `.env` manually.

Configure the environment variables according to your local setup. Add any required database settings or AI service credentials if applicable.

**Never commit your actual `.env` file or API keys to GitHub.**

### 4. Generate the Application Key

```bash
php artisan key:generate
```

### 5. Configure the Database

If the application uses a database, configure the database connection in `.env`, then run:

```bash
php artisan migrate
```

Run this step only if the application's database migrations are required.

### 6. Install Frontend Dependencies

```bash
npm install
```

### 7. Start the Development Server

```bash
php artisan serve
```

The Laravel development server is usually available at:

`http://127.0.0.1:8000`

If the application requires frontend assets during local development, run the Vite development server in a separate terminal:

```bash
npm run dev
```

## Integration

The backend is intended to work with the portfolio frontend. The frontend sends chatbot questions to the backend, and the backend returns responses for display in the chat interface.

Configure the frontend and backend URLs according to their deployment environments. Ensure that cross-origin requests are configured appropriately if they run on different domains.

The exact API endpoint, request format, and response structure depend on the implementation in this repository.

## Project Structure

```text
laravel-chatbot/
├── app/          # Application logic
├── bootstrap/    # Laravel application bootstrap
├── chatbot/      # Chatbot-related components
├── config/       # Application configuration
├── database/     # Database migrations and seeders
├── public/       # Public assets and entry point
├── resources/    # Views and frontend resources
├── routes/       # Application routes
├── tests/        # Automated tests
├── .env.example  # Environment configuration template
├── artisan       # Laravel command-line interface
├── composer.json # PHP dependencies
├── package.json  # JavaScript dependencies
└── README.md
```

## Security

- Keep API keys and credentials private.
- Do not commit `.env` files containing secrets.
- Validate incoming chatbot requests.
- Apply appropriate rate limiting to public API endpoints.
- Configure CORS to allow only the intended frontend origins in production.

## Author

**Agung Leo**

- GitHub: [@agungleo737](https://github.com/agungleo737)
- Portfolio: [portofolio-web-two-theta.vercel.app](https://portofolio-web-two-theta.vercel.app/)

## License

No license has been specified for this project yet.
