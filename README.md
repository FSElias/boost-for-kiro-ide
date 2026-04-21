# Boost for Kiro IDE

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jcf/boost-for-kiro-ide.svg?style=flat-square)](https://packagist.org/packages/jcf/boost-for-kiro-ide)
[![Total Downloads](https://img.shields.io/packagist/dt/jcf/boost-for-kiro-ide.svg?style=flat-square)](https://packagist.org/packages/jcf/boost-for-kiro-ide)
[![License](https://img.shields.io/packagist/l/jcf/boost-for-kiro-ide.svg?style=flat-square)](https://packagist.org/packages/jcf/boost-for-kiro-ide)

Empowers Amazon's **Kiro IDE** within [Laravel Boost](https://github.com/laravel/boost). While Laravel Boost 2.4+ now natively configures Kiro IDE, **this package remains indispensable**. Kiro IDE does not currently understand MCP Prompts natively. This package acts as your "Hook Superpower," seamlessly translating all rich Boost MCP recommendations and commands into interactive **Agent Hooks** (`.kiro/hooks/*.hook`), enabling you to trigger them visually right from the Kiro interface.

## About Kiro IDE

Kiro IDE is an AI-powered integrated development environment from Amazon that supports the Model Context Protocol (MCP), allowing AI agents to interact with your Laravel project in a contextualized and efficient manner.

## About Laravel Boost

Laravel Boost accelerates AI-assisted development by providing the essential context and structure that AI needs to generate high-quality, framework-specific Laravel code. This package extends Boost to work seamlessly with Kiro IDE.

## Requirements

- PHP 8.2 or higher
- Laravel 11.x, 12.x or 13.x
- [Laravel Boost](https://github.com/laravel/boost) ^2.4
- Kiro IDE installed on your system

## Installation

You can install the package via Composer:

```bash
composer require jcf/boost-for-kiro-ide --dev
```

The package automatically registers Kiro IDE with Laravel Boost through Laravel's auto-discovery.

> **✨ Laravel Boost v2.4+ Native Support**  
> Starting with version 2.4, Laravel Boost creates the Kiro Agent and registers MCP capabilities out-of-the-box. The industry standard `AGENTS.md` file is now used natively, replacing the old `.kiro/steering/laravel-boost.md`. This is fantastic because it centralizes all AI guidelines in a single, unified file, cutting down on token waste and improving context retention. This package builds exactly on top of that official integration, focusing entirely on expanding your Hook arsenal.

## Usage

### 1. Install Laravel Boost (if not already installed)

If you don't have Laravel Boost installed yet, install it first:

```bash
composer require laravel/boost --dev
php artisan boost:install
```

### 2. Configure Kiro IDE

When running the `php artisan boost:install` command, Kiro IDE will appear as an available option natively in Boost. Select it to automatically configure:

- **MCP Configuration**: Created at `.kiro/settings/mcp.json`
- **AI Guidelines**: Added to the industry standard `AGENTS.md` in your project root.

### 3. Activate in Kiro IDE

In Kiro IDE:

1. Open the command palette (`Cmd+Shift+P` or `Ctrl+Shift+P`)
2. Search for "MCP: Reconnect All Servers" and press `Enter`
3. The `laravel-boost` MCP server will be automatically detected and connected

Kiro automatically loads your AI guidelines from `AGENTS.md` to provide contextualized, highly-efficient assistance for your Laravel application.

### 4. Extend with Skills (Optional)

After installation, you can enhance Kiro's capabilities by installing Skills:

```bash
php artisan boost:add-skill laravel/boost-skill-livewire
```

Skills provide specialized AI instructions for specific Laravel development tasks. See the [Skills System Support](#skills-system-support) section below for more details.

### 5. Boost Prompts as Hooks (Automatic)

Boost MCP prompts (like "Upgrade Laravel v13" or "Laravel Code Simplifier") are automatically converted to Kiro agent hooks during `boost:install` and `boost:update`. They appear in Kiro's **Agent Hooks** panel as user-triggered actions.

See [Prompt-to-Hook Conversion](#prompt-to-hook-conversion) for details.

### Complete Installation Flow Example

Here's a complete example of setting up a new Laravel project with Kiro IDE and Skills:

```bash
# 1. Create a new Laravel project
composer create-project laravel/laravel my-project
cd my-project

# 2. Install Laravel Boost and this package
composer require laravel/boost --dev
composer require jcf/boost-for-kiro-ide --dev

# 3. Run the Boost installation wizard
php artisan boost:install
# → Select "Kiro IDE" from the list
# → Configuration files are created automatically

# 4. (Optional) Install Skills to extend capabilities
php artisan boost:add-skill laravel/boost-skill-livewire
php artisan boost:add-skill laravel/boost-skill-testing

# 5. Open in Kiro IDE and reconnect MCP servers
# → Cmd+Shift+P (or Ctrl+Shift+P)
# → "MCP: Reconnect All Servers"
# → Start developing with AI assistance!
```

## Created File Structure

After installation, the following files will be created in your Laravel project:

```text
.kiro/
├── hooks/
│   ├── boost-prompt-laravel-code-simplifier.kiro.hook
│   ├── boost-prompt-upgrade-inertia-v3.kiro.hook
│   ├── boost-prompt-upgrade-laravel-v13.kiro.hook
│   └── boost-prompt-upgrade-livewire-v4.kiro.hook
└── settings/
    └── mcp.json           # MCP server configuration
AGENTS.md                  # Unified AI guidelines (created by Boost natively)
```

The hooks in `.kiro/hooks/` are automatically generated from Laravel Boost's MCP prompts (see [Prompt-to-Hook Conversion](#prompt-to-hook-conversion) below).

You can add these files to `.gitignore` if desired, as they can be regenerated by running `php artisan boost:install` or `php artisan boost:update`.

## Available MCP Tools

After installation, Kiro will have access to all Laravel Boost MCP tools, including:

- **Application Info**: Information about PHP, Laravel, packages, and Eloquent models
- **Browser Logs**: Browser logs and errors
- **Database Connections**: Database connection inspection
- **Database Query**: Execute database queries
- **Database Schema**: Read database schema
- **Get Config**: Get configuration values
- **Last Error**: Read the last error from logs
- **List Artisan Commands**: List available Artisan commands
- **List Routes**: List application routes
- **Read Log Entries**: Read log entries
- **Search Docs**: Search Laravel documentation
- **Tinker**: Execute arbitrary code in the application context
- And much more...



## Updating Guidelines

To keep your AI guidelines up to date with the latest versions of installed Laravel ecosystem packages, run:

```bash
php artisan boost:update
```

You can also automate this process by adding it to your Composer scripts:

```json
{
  "scripts": {
    "post-update-cmd": ["@php artisan boost:update --ansi"]
  }
}
```

## Skills System Support

Laravel Boost v2.0 introduces a powerful **Skills system** that extends AI-assisted development with specialized, reusable instructions. The boost-for-kiro-ide package provides **zero-configuration** support for Skills - they work automatically with Kiro IDE.

### What are Skills?

Skills are modular AI instructions (markdown files) that enhance Kiro's ability to perform specific Laravel development tasks. They provide specialized knowledge, workflow automation, and best practices for common patterns.

### Installing Skills

Install skills from GitHub repositories using the `boost:add-skill` command:

```bash
php artisan boost:add-skill owner/repository
```

**Example:**

```bash
php artisan boost:add-skill laravel/boost-skill-livewire
```

Skills are automatically:

- Downloaded and installed to `.ai/skills/`
- Loaded by Laravel Boost
- Made available to Kiro IDE through the MCP protocol
- **No additional configuration required!**

### How It Works

1. **Automatic Loading**: Laravel Boost loads skills from `.ai/skills/`
2. **MCP Integration**: Skills are delivered to Kiro through the Model Context Protocol
3. **Zero Configuration**: No Kiro-specific setup needed - it just works!
4. **Immediate Availability**: Skills are available in Kiro's AI context right away

### Creating Custom Skills

You can create custom skills for your team:

```bash
mkdir -p .ai/skills/my-custom-skill
touch .ai/skills/my-custom-skill/SKILL.md
```

Write your skill instructions in the `SKILL.md` file, and it's automatically available to Kiro.

For more information about the Skills system, see the [Laravel Boost documentation](https://github.com/laravel/boost).

## Prompt-to-Hook Conversion

Kiro IDE does not support MCP prompts. To make Boost's prompts available in Kiro, this package converts them into agent hooks — user-triggered actions that appear in Kiro's **Agent Hooks** panel.

This gives Kiro users access to the same upgrade guides and code assistance prompts that other MCP clients get natively, like "Upgrade Laravel v13" or "Laravel Code Simplifier".

Hooks are synced automatically when running `boost:install` or `boost:update`. Only prompts that are relevant to your project are included (e.g., the Inertia upgrade prompt only appears if your project uses Inertia).

You can also sync hooks manually:

```bash
php artisan boost:kiro-hooks
```

### Disabling Automatic Sync

If you prefer to manage hooks manually, you can disable the automatic sync:

```php
// config/boost.php
'agents' => [
    'kiro' => [
        'auto_sync_hooks' => false,
    ],
],
```

When disabled, hooks are only synced when you explicitly run `php artisan boost:kiro-hooks`.

## Compatibility

This package is designed to be compatible with all versions of Laravel Boost ^2.0. It uses the extension hooks provided by Laravel Boost to register the Kiro code environment.

### Tested Versions

- Laravel Boost: ^2.4
- Laravel: 11.x, 12.x, 13.x
- PHP: 8.2, 8.3, 8.4

## Automatic Detection

The package automatically detects Kiro IDE installations in the following locations:

**macOS:**

- `/Applications/Kiro.app`

**Linux:**

- `/opt/kiro`
- `/usr/local/bin/kiro`
- `~/.local/bin/kiro`

**Windows:**

- `%ProgramFiles%\Kiro`
- `%LOCALAPPDATA%\Programs\Kiro`

**Project Detection:**

- Presence of the `.kiro` directory in the project

## Testing

Run the tests with:

```bash
composer test
```

To run only static analysis:

```bash
composer lint
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information about what has changed recently.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Security

If you discover any security related issues, please email jotacfurtado@gmail.com instead of using the issue tracker.

## Credits

- [João C. Furtado](https://github.com/jotafurtado)
- [Laravel Boost](https://github.com/laravel/boost) - Original package that this extends
- Huge thanks to the community contributor who submitted PR-7, bringing the incredible Prompt-to-Hook translation feature to life.
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Related Links

- [Laravel Boost](https://github.com/laravel/boost) - Main package
- [Model Context Protocol](https://modelcontextprotocol.io/) - MCP specification
- [Laravel Documentation](https://laravel.com/docs) - Official Laravel documentation
- [Kiro IDE](https://aws.amazon.com/kiro) - Official Kiro IDE website
