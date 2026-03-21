# Fair-Explorer

A WordPress plugin that provides a comprehensive repository browser for exploring and managing WordPress plugins, themes, and TYPO3 extensions from the FAIR ecosystem.

## Features

- 🔍 **Package Search & Browse** - Search and explore WordPress plugins, themes, and TYPO3 extensions with detailed information
- 🎨 **Theme Discovery** - Browse and preview WordPress themes with live demos
- 🔌 **Plugin Management** - Discover and explore WordPress plugins
- 🧩 **TYPO3 Extensions** - Browse and search TYPO3 extensions from the extensions API
- 🛒 **Cart Functionality** - Add packages to cart with persistent storage via cookies
- 🎮 **WordPress Playground Integration** - Generate blueprint URLs for instant WordPress demos
- ✨ **Interactive UI** - Modern lightbox galleries, floating cart button, and smooth animations
- 📱 **Responsive Design** - Mobile-friendly interface with SCSS-powered styling
- ♿ **Accessibility** - ARIA-compliant components with keyboard navigation support
- 🔌 **REST API** - REST API endpoints for external integrations
- 🎨 **Theme Override Support** - Template hierarchy allows theme customization

## Installation

1. Download or clone this repository
2. Place the `fair-explorer` folder in your `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Create pages with slugs `plugins`, `themes`, and optionally set up TYPO3 extension routes for the archive pages

## Template Hierarchy & Customization

Fair-Explorer supports WordPress template hierarchy, allowing themes to override plugin templates:

### Template Override Order

1. **Child Theme** (highest priority): `wp-content/themes/child-theme/fair-explorer/[template-file]`
2. **Parent Theme**: `wp-content/themes/active-theme/fair-explorer/[template-file]`  
3. **Plugin Default** (fallback): `wp-content/plugins/fair-explorer/includes/views/[template-file]`

### Available Templates

You can override these template files in your theme:

**Plugins:**
- `plugins/plugins-search-form.php` - Plugin search form
- `plugins/archive/plugins.php` - Plugins listing page
- `plugins/archive/plugin.php` - Individual plugin card in the archive
- `plugins/single/plugin.php` - Individual plugin display

**Themes:**
- `themes/themes-search-form.php` - Theme search form
- `themes/archive/themes.php` - Themes listing page
- `themes/archive/theme.php` - Individual theme card in the archive
- `themes/single/theme.php` - Individual theme display

**TYPO3 Extensions:**
- `extensions/extensions-search-form.php` - Extension search form
- `extensions/archive/extensions.php` - Extensions listing page
- `extensions/archive/extension.php` - Individual extension card in the archive
- `extensions/single/extension.php` - Individual extension display

### Example Theme Override

To customize the themes listing in your theme:

1. Create folder: `wp-content/themes/your-theme/fair-explorer/themes/archive/`
2. Copy: `wp-content/plugins/fair-explorer/includes/views/themes/archive/themes.php`
3. Paste to: `wp-content/themes/your-theme/fair-explorer/themes/archive/themes.php`
4. Customize as needed

Each overridable template receives an `$args` array with all the data it needs. The type-specific templates (e.g. `plugins/archive/plugins.php`) delegate to shared partials internally, but when you override a template, you have full control and can write completely custom markup.

Your customizations will be preserved during plugin updates.

## Configuration

### Required Constants

Add these constants to your `wp-config.php` file:

```php
// Root path for URL structure (optional, defaults to empty)
define( 'AE_ROOT', 'packages/' )
```

### Required Pages

Create WordPress pages with these exact slugs:
- **plugins** - For the plugins archive and individual plugin pages
- **themes** - For the themes archive and individual theme pages

TYPO3 extensions use the hardcoded root `packages/typo3`, so the archive is served at `packages/typo3/extensions/`. Ensure a WordPress page exists at that path for the content to render on.


## URL Structure

With the default configuration, your URLs will be:

**Plugins:**
- Archive: `yoursite.com/packages/plugins/`
- Individual: `yoursite.com/packages/plugins/plugin-name/`

**Themes:**
- Archive: `yoursite.com/packages/themes/`
- Individual: `yoursite.com/packages/themes/theme-name/`

**TYPO3 Extensions:**
- Archive: `yoursite.com/packages/typo3/extensions/`
- Individual: `yoursite.com/packages/typo3/extensions/extension-name/`

**REST API:**
- Playground Blueprint: `yoursite.com/wp-json/fair-explorer/v1/playground/blueprint`

## REST API Endpoints

### WordPress Playground Blueprint Generator

**Endpoint:** `GET /wp-json/fair-explorer/v1/playground/blueprint`

Generate WordPress Playground blueprints for instant theme/plugin demos.

#### Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `theme` | string | No | Theme download URL (.zip file, HTTPS only) |
| `plugin` | string | No | Plugin download URL (.zip file, HTTPS only) |
| `landing_page` | string | No | Landing page path (default: `/`) |
| `activate` | boolean | No | Auto-activate asset (default: `true`) |
| `import_starter_content` | boolean | No | Import theme starter content (default: `true`) |

#### Usage Examples

**Theme Blueprint:**
```bash
GET /wp-json/fair-explorer/v1/playground/blueprint?theme=https://example.com/theme.zip
```

**Plugin Blueprint:**
```bash
GET /wp-json/fair-explorer/v1/playground/blueprint?plugin=https://example.com/plugin.zip
```

**Combined Theme + Plugin:**
```bash
GET /wp-json/fair-explorer/v1/playground/blueprint?theme=https://example.com/theme.zip&plugin=https://example.com/plugin.zip&landing_page=/demo
```

#### Response Format

Returns a JSON blueprint compatible with WordPress Playground:

```json
{
  "$schema": "https://playground.wordpress.net/blueprint-schema.json",
  "landingPage": "/",
  "features": {
    "networking": true
  },
  "steps": [
    {
      "step": "installTheme",
      "themeData": {
        "resource": "url",
        "url": "https://example.com/theme.zip"
      },
      "options": {
        "activate": true,
        "importStarterContent": true
      }
    }
  ]
}
```

#### Security & Validation

- ✅ **HTTPS Only**: Only secure URLs accepted
- ✅ **File Type Validation**: Only `.zip` files allowed
- ✅ **Domain Filtering**: Blocks localhost and internal IPs
- ✅ **Input Sanitization**: All parameters properly sanitized
- ✅ **CORS Headers**: Cross-origin requests enabled

## Development

### Code Structure

```
fair-explorer/
├── includes/
│   ├── controller/              # MVC Controllers
│   │   ├── class-main.php          # Main plugin controller
│   │   ├── class-packages.php      # Unified packages controller (plugins, themes & extensions)
│   │   ├── class-typo3.php         # TYPO3 extensions API fetcher
│   │   └── class-playground.php    # WordPress Playground API
│   ├── model/                   # Data Models
│   │   ├── class-singleton.php     # Base singleton pattern
│   │   ├── class-assetinfo.php     # Base asset model (shared properties & methods)
│   │   ├── class-plugininfo.php    # Plugin data model
│   │   ├── class-themeinfo.php     # Theme data model
│   │   └── class-extensioninfo.php # TYPO3 extension data model
│   ├── views/                   # Template Files (Plugin Defaults)
│   │   ├── plugins/                # Plugin templates (overridable)
│   │   │   ├── archive/
│   │   │   │   ├── plugins.php
│   │   │   │   └── plugin.php
│   │   │   ├── single/
│   │   │   │   └── plugin.php
│   │   │   └── plugins-search-form.php
│   │   ├── themes/                 # Theme templates (overridable)
│   │   │   ├── archive/
│   │   │   │   ├── themes.php
│   │   │   │   └── theme.php
│   │   │   ├── single/
│   │   │   │   └── theme.php
│   │   │   └── themes-search-form.php
│   │   ├── extensions/             # TYPO3 extension templates (overridable)
│   │   │   ├── archive/
│   │   │   │   ├── extensions.php
│   │   │   │   └── extension.php
│   │   │   ├── single/
│   │   │   │   └── extension.php
│   │   │   └── extensions-search-form.php
│   │   └── shared/                 # Shared partials (internal, not overridable)
│   │       ├── archive-list.php       # Generic archive listing
│   │       ├── archive-card.php       # Generic archive card
│   │       ├── search-form.php        # Generic search form
│   │       └── single.php             # Generic single view
│   └── autoload.php             # PSR-4 autoloader
├── assets/
│   ├── js/
│   │   └── fair-explorer.js     # Main JavaScript (ES6 classes)
│   ├── scss/
│   │   ├── fair-explorer.scss   # Main SCSS file
│   │   ├── _cart.scss           # Cart styling
│   │   ├── _lightbox.scss       # Lightbox component
│   │   └── _search.scss         # Search components
│   └── css/
│       └── fair-explorer.css    # Compiled CSS
└── composer.json                # PHP dependencies and scripts
```


### JavaScript Classes

The frontend uses modern ES6 classes:

- **AeCart** - Cart functionality with cookie persistence
- **AeLightbox** - Image lightbox with keyboard navigation
- **AeDetails** - Collapsible details/summary components
- **FallingText** - Animation effects

### Development Setup

1. **Install PHP dependencies:**
   ```bash
   composer install
   ```

2. **Code Quality Tools:**
   ```bash
   # Format code
   composer run format
   
   # Lint code
   composer run lint
   
   # Fix linting issues
   composer run lint:fix
   ```

3. **SCSS Compilation:**
   ```bash
   # Install Node dependencies
   npm install
   
   # Compile SCSS
   npm run sass
   
   # Watch for changes
   npm run sass:watch
   ```

### Coding Standards

This project follows WordPress Coding Standards with:
- **PHP_CodeSniffer** with WordPress rules
- **PHPCS** for code formatting
- **SCSS** for modular styling
- **ES6** JavaScript with jQuery integration

## Cart System

The cart system allows users to collect packages for later reference:

- **Persistent Storage** - Uses cookies to maintain selections across sessions
- **Toggle Behavior** - Add/remove packages with single button click
- **Floating UI** - Minimalist floating cart button with popup
- **Accessibility** - Full keyboard navigation and screen reader support
- **Universal Support** - Works with both themes and plugins seamlessly

## Troubleshooting

### Template Issues

**Templates Not Loading:**
1. Check file permissions on theme `fair-explorer` folder
2. Ensure template files follow exact naming convention
3. Clear any caching plugins

**Template Hierarchy Not Working:**
1. Verify theme has `fair-explorer` folder in correct location
2. Check file names match exactly (case-sensitive)
3. Ensure WordPress functions `get_template_directory()` and `get_stylesheet_directory()` work

### Rewrite Rules Not Working

1. Go to **Settings > Permalinks** and click "Save Changes"
2. Ensure your pages have the correct slugs (`plugins`, `themes`, etc.)
3. Check that `AE_ROOT` constant matches your URL structure
4. Verify `Packages` class instances are properly initialized

### Cart Not Persisting

- Ensure cookies are enabled in browser
- Check for JavaScript errors in browser console
- Verify jQuery is loaded properly

### Styling Issues

- Check if CSS file is properly enqueued
- Compile SCSS if using development version
- Clear any caching plugins
- Verify theme compatibility

### Package Loading Issues

**Packages Not Displaying:**
- Check WordPress API functions are available (`themes_api`, `plugins_api`)
- Verify network connectivity to package repositories
- Check for PHP errors in WordPress error logs

**Architecture Issues:**
- Ensure `Packages` class is autoloaded correctly
- Verify singleton instances are properly created
- Check factory methods return valid instances

### REST API Issues

**Playground Blueprint Endpoint Not Working:**
- Verify REST API is enabled (`/wp-json/` accessible)
- Check WordPress version (requires 4.7+)
- Ensure plugin is activated and REST routes are registered

**Blueprint Validation Errors:**
- URLs must be HTTPS only
- Only `.zip` files are accepted
- At least one theme or plugin URL must be provided
- Landing page must start with `/`

**CORS Issues:**
- REST endpoint includes CORS headers automatically
- For custom domains, verify server CORS configuration

## Contributing

1. Fork the repository
2. Create a feature branch
3. Follow WordPress coding standards
4. Run linting and formatting tools
5. Submit a pull request

## License

This project is licensed under the GPL v2 or later - see the WordPress plugin header for details.

## Support

For support and bug reports, please use the GitHub issue tracker or contact the FAIR team.
