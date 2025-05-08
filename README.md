# FloraFinder - Plant Identification & Community Platform

FloraFinder is a Laravel-based web application that helps users identify Malaysian plants using AI technology. It features plant identification via image upload, a community forum, conservation tracking, and educational resources.

## Features

- **Plant Identification**: Upload images of plants to identify them using the PlantNet API
- **Plant Database**: Access information about Malaysian native and endemic plant species
- **Community Forum**: Discuss plant-related topics with other enthusiasts
- **User Authentication**: Secure registration and login system
- **Responsive Design**: Works on desktop and mobile devices
- **Dark Mode Support**: User-configurable appearance settings

## Tech Stack

- **Backend**: Laravel 12
- **Frontend**: Vue.js 3 with Inertia.js
- **UI Framework**: Tailwind CSS with custom components
- **API Integration**: Saloon PHP HTTP client library
- **Database**: SQLite (default), supports MySQL/PostgreSQL
- **Authentication**: Laravel's built-in authentication
- **Queue System**: Database queue for background jobs
- **Testing**: PHPUnit with Pest

## Possible Future Features

### 🌿 Core Features

#### 1. Plant Collection & Personal Herbarium
- Save identified plants to a personalized digital collection
- Add notes, custom tags, and location information for each plant
- Export your collection as PDF or share with the community
- Track rare plant sightings over time
- Group plants by taxonomy, location, or custom categories

#### 2. Community Verification System
- Submit uncertain identifications for review by community experts
- Reputation system for trusted plant identifiers
- "Verified ID" badges for high-confidence community-checked identifications
- Expert botanist verification for particularly rare or difficult species
- Achievement system for accurate identifications

#### 3. Offline Mode & Mobile Support
- Cache basic identification data for common Malaysian plants
- Save plant photos for later identification when connectivity returns
- Reduced-size model for basic identification in offline environments
- Background sync when connectivity is restored
- Progressive Web App support for installation on mobile devices

### 🌐 Geographic & Educational Features

#### 4. Interactive Plant Map
- Visualize where plants have been identified on an interactive map
- Filter by species, rarity, or conservation status
- Heatmaps showing biodiversity hotspots in Malaysia
- Protected location sharing for endangered species
- Trail recommendations for finding specific plant types

#### 5. Seasonal Alerts & Phenology Tracking
- Calendar showing flowering/fruiting times for Malaysian plants
- Notifications when favorite plants are likely blooming
- Seasonal guides to what's currently visible in Malaysian forests
- Contribute phenology data to scientific research
- Track climate change effects on flowering times over years

#### 6. AR Plant Recognition & Learning Tools
- Point camera at previously identified plants to see labels in AR
- Educational AR overlays showing plant parts and characteristics
- Virtual garden planning tools with Malaysian native species
- Interactive learning modules about plant identification
- "Plant this in my garden" visualization tool

### 🔬 Advanced Features

#### 7. Conservation Dashboard
- Conservation status indicators for all identified species
- Educational content about threatened Malaysian flora
- Volunteer opportunities for local conservation projects
- Rare plant sighting reporting (with optional location privacy)
- Tracking of invasive species spread

#### 8. AI-Powered Plant Health Diagnosis
- Disease and pest identification for common garden plants
- Nutrient deficiency analysis based on leaf symptoms
- Growth stage assessment and care recommendations
- Treatment suggestions with local Malaysian alternatives
- Integration with gardening calendar for timely care

#### 9. Guided Botanical Tours
- Self-guided tour routes in popular Malaysian nature areas
- Lists of plants to look for on each trail with identification tips
- Achievement system for finding specific plant collections
- Audio guides explaining ecological relationships
- Community-created botanical walking tours

#### 10. Educational Integration
- Classroom mode for teachers leading nature walks
- Assignment creation tools for botany classes
- Data collection features for student research projects
- Shared collections for educational groups
- Curriculum materials about Malaysian plant biodiversity

## Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js and NPM
- SQLite or other database system
- PlantNet API key (for plant identification features)

## Installation

1. Clone the repository:

```bash
git clone https://github.com/anasdaniel/FloraFinder.git
cd FloraFinder
```

2. Install PHP dependencies:

```bash
composer install
```

3. Install JavaScript dependencies:

```bash
npm install
```

4. Create your environment file:

```bash
cp .env.example .env
```

5. Generate an application key:

```bash
php artisan key:generate
```

6. Configure your database in the `.env` file:

```
DB_CONNECTION=sqlite
# Or use MySQL/PostgreSQL by configuring the appropriate settings:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=
```

7. Set up your PlantNet API key in the `.env` file:

```
PLANTNET_API_KEY=your_plantnet_api_key_here
PLANTNET_PROJECT=all
```

8. Run database migrations:

```bash
php artisan migrate
```

9. Build frontend assets:

```bash
npm run build
```

## Development

To start the development server:

```bash
# Start Laravel development server
php artisan serve

# Build and watch for frontend changes
npm run dev
```

For an even faster development workflow, you can use the built-in dev script that starts multiple services concurrently:

```bash
composer dev
```

This will run:

- Laravel development server
- Queue worker
- Laravel Pail (log viewer)
- Vite development server

### Server-Side Rendering (SSR)

To enable server-side rendering:

```bash
composer dev:ssr
```

## Testing

Run tests with Pest:

```bash
./vendor/bin/pest
```

## Deployment

This application follows standard Laravel deployment practices. Here's a checklist for deployment:

1. Set appropriate production values in `.env`
2. Set `APP_ENV=production` and `APP_DEBUG=false`
3. Configure your web server (Nginx/Apache) to point to the `public` directory
4. Make sure storage and bootstrap/cache directories are writable
5. Set up your queue worker as a system service
6. Configure SSL for secure HTTPS connections

## Key Directories

- **app/** - Core application code
- **app/Http/Integrations/** - PlantNet API integration with Saloon
- **resources/js/pages/** - Vue components for each page
- **resources/js/components/** - Reusable Vue components
- **resources/js/layouts/** - Application layout components
- **routes/** - Application routes definition
- **config/** - Configuration files

## API Integration

The application integrates with the PlantNet API for plant identification. When a user uploads a plant image, the request is processed through Saloon connector classes that handle the API communication.

## License

[MIT License](LICENSE.md)

## Credits

- Created with Laravel 12 and Vue.js
- Plant identification powered by PlantNet API
- Icons from Lucide and Heroicons
- Demo plant images from Unsplash
