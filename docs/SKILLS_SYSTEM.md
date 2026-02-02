# Laravel Boost v2.0 Skills System

## Overview

Laravel Boost v2.0 introduces a powerful Skills system that allows developers to extend and customize AI-assisted development workflows. This document explains how the Skills system works with the Kiro IDE integration and what developers need to know.

**Validates: Requirements 5.1, 5.2, 5.4**

## What are Skills?

Skills are modular, reusable AI instructions packaged as markdown files that enhance the AI's ability to perform specific tasks in your Laravel project. They provide:

- **Specialized Knowledge**: Domain-specific instructions for common Laravel patterns
- **Workflow Automation**: Step-by-step guides for complex development tasks
- **Best Practices**: Curated approaches to solving common problems
- **Extensibility**: Ability to create custom skills for your team's needs

## How Skills Work with Kiro IDE

### Automatic Integration

The boost-for-kiro-ide package provides **zero-configuration** Skills support:

1. **No Additional Setup Required**: Skills work automatically once Kiro is registered as an agent
2. **Automatic Loading**: Laravel Boost loads skills and makes them available to Kiro
3. **Seamless Integration**: Skills appear in the AI context without manual configuration

### Skills Directory Structure

Skills are stored in your Laravel project following this structure:

```
your-laravel-project/
├── .ai/
│   ├── skills/
│   │   ├── skill-name-1/
│   │   │   └── SKILL.md
│   │   ├── skill-name-2/
│   │   │   └── SKILL.md
│   │   └── ...
│   └── livewire/
│       └── 3/
│           └── skill/
│               └── custom-skill/
│                   └── SKILL.md
└── .kiro/
    ├── settings/
    │   └── mcp.json
    └── steering/
        └── laravel-boost.md
```

### How Kiro Accesses Skills

1. **Laravel Boost** manages the skills directory (`.ai/skills/`)
2. **Skills are loaded** automatically when Kiro starts
3. **AI context** includes skill instructions
4. **No Kiro configuration** needed - it just works!

## Installing Skills

### Using boost:add-skill Command

Install skills from GitHub repositories:

```bash
php artisan boost:add-skill owner/repository
```

**Example:**
```bash
php artisan boost:add-skill laravel/boost-skill-livewire
```

This command:
- Downloads the skill from the specified repository
- Installs it to `.ai/skills/`
- Makes it immediately available to Kiro
- Requires no additional configuration

### Skill Installation Process

1. **Command Execution**: Run `php artisan boost:add-skill owner/repo`
2. **Download**: Laravel Boost fetches the skill from GitHub
3. **Installation**: Skill files are placed in `.ai/skills/skill-name/`
4. **Automatic Loading**: Laravel Boost loads the skill
5. **Kiro Access**: Skill is immediately available in AI context

## Creating Custom Skills

### Skill File Format

Skills are markdown files with specific structure:

```markdown
# Skill Name

## Description
Brief description of what this skill does.

## When to Use
Explain when this skill should be applied.

## Instructions
Step-by-step instructions for the AI to follow.

## Examples
Code examples and usage patterns.

## Best Practices
Guidelines and recommendations.
```

### Creating a Custom Skill

1. Create a directory in `.ai/skills/`:
```bash
mkdir -p .ai/skills/my-custom-skill
```

2. Create a `SKILL.md` file:
```bash
touch .ai/skills/my-custom-skill/SKILL.md
```

3. Write your skill instructions in the markdown file

4. The skill is automatically available to Kiro

### Skill Override

You can override existing skills by creating a skill with the same name in your project's `.ai/skills/` directory. Your local version takes precedence.

## Skills and Kiro IDE Integration

### What Kiro Sees

When you use Kiro with Laravel Boost v2.0:

1. **Guidelines**: Kiro loads `.kiro/steering/laravel-boost.md` automatically
2. **Skills**: Laravel Boost provides skills to Kiro through the MCP protocol
3. **Context**: Skills appear as additional context in AI interactions
4. **Automatic**: No manual configuration or imports needed

### MCP Integration

The Model Context Protocol (MCP) enables communication between Kiro and Laravel Boost:

- **MCP Config**: Located at `.kiro/settings/mcp.json`
- **Server Registration**: Laravel Boost MCP server is registered automatically
- **Skill Delivery**: Skills are delivered through MCP to Kiro
- **Real-time Updates**: Changes to skills are reflected immediately

### Guidelines vs Skills

| Feature | Guidelines | Skills |
|---------|-----------|--------|
| **Location** | `.kiro/steering/` | `.ai/skills/` |
| **Purpose** | General project context | Specific task instructions |
| **Scope** | Project-wide | Task-specific |
| **Management** | Manual editing | `boost:add-skill` command |
| **Loading** | Kiro auto-loads | Laravel Boost provides |

## Verification and Testing

### Verify Skills are Installed

Check the skills directory:
```bash
ls -la .ai/skills/
```

### Verify Kiro Configuration

Check that Kiro is properly configured:
```bash
cat .kiro/settings/mcp.json
cat .kiro/steering/laravel-boost.md
```

### Test Skill Functionality

1. Install a skill:
```bash
php artisan boost:add-skill laravel/boost-skill-example
```

2. Open Kiro IDE in your project

3. Ask Kiro to perform a task related to the skill

4. Verify that Kiro uses the skill's instructions

## No Additional Configuration Required

### Key Points

✅ **Skills work automatically** with Kiro IDE
✅ **No Kiro-specific configuration** needed
✅ **No manual imports** or includes required
✅ **No code changes** to the boost-for-kiro-ide package
✅ **Laravel Boost handles everything** behind the scenes

### Why It Just Works

The boost-for-kiro-ide package implements the required interfaces:

- `SupportsGuidelines`: Enables guidelines integration
- `SupportsMcp`: Enables MCP communication
- `Agent`: Registers Kiro with Laravel Boost

These interfaces ensure that:
1. Laravel Boost knows how to communicate with Kiro
2. Skills are delivered through the proper channels
3. Kiro receives and processes skill instructions
4. Everything works seamlessly without configuration

## Troubleshooting

### Skills Not Appearing

If skills don't seem to be working:

1. **Verify Installation**:
```bash
ls -la .ai/skills/
```

2. **Check Kiro Configuration**:
```bash
cat .kiro/settings/mcp.json
```

3. **Restart Kiro IDE**: Close and reopen Kiro to reload skills

4. **Check Laravel Boost Version**:
```bash
composer show laravel/boost
```
Ensure you have v2.0 or higher.

### MCP Connection Issues

If MCP isn't working:

1. **Verify MCP Config**: Check `.kiro/settings/mcp.json` exists
2. **Check Permissions**: Ensure Kiro can read the config file
3. **Restart Kiro**: Restart the IDE to reconnect MCP

### Skill Not Loading

If a specific skill isn't loading:

1. **Check Skill Format**: Ensure `SKILL.md` exists in the skill directory
2. **Verify Markdown**: Check that the markdown is valid
3. **Check File Permissions**: Ensure files are readable
4. **Clear Cache**: Try clearing Laravel's cache

## Best Practices

### Organizing Skills

- **Use Descriptive Names**: Name skills clearly (e.g., `api-resource-generator`)
- **One Skill Per Task**: Keep skills focused on specific tasks
- **Document Dependencies**: Note any required packages or setup
- **Version Control**: Commit skills to your repository

### Writing Effective Skills

- **Be Specific**: Provide clear, actionable instructions
- **Include Examples**: Show code examples for clarity
- **Consider Context**: Assume the AI has Laravel knowledge
- **Test Thoroughly**: Verify skills work as expected

### Team Collaboration

- **Share Skills**: Commit custom skills to your team repository
- **Document Usage**: Explain when and how to use each skill
- **Review Regularly**: Update skills as best practices evolve
- **Standardize Naming**: Use consistent naming conventions

## Examples

### Example 1: Installing a Livewire Skill

```bash
# Install the Livewire skill
php artisan boost:add-skill laravel/boost-skill-livewire

# Verify installation
ls -la .ai/skills/livewire/

# Use in Kiro
# Simply ask Kiro to create a Livewire component
# The skill will guide the AI automatically
```

### Example 2: Creating a Custom API Skill

```bash
# Create skill directory
mkdir -p .ai/skills/api-best-practices

# Create skill file
cat > .ai/skills/api-best-practices/SKILL.md << 'EOF'
# API Best Practices

## Description
Guidelines for creating RESTful APIs in Laravel following team standards.

## When to Use
When creating or modifying API endpoints.

## Instructions
1. Use API resources for response formatting
2. Implement proper HTTP status codes
3. Add rate limiting to routes
4. Include API versioning
5. Document endpoints with OpenAPI

## Examples
[Include code examples here]
EOF

# The skill is now available to Kiro automatically
```

### Example 3: Overriding a Default Skill

```bash
# Create override in your project
mkdir -p .ai/skills/model-generator

# Create your custom version
cat > .ai/skills/model-generator/SKILL.md << 'EOF'
# Custom Model Generator

[Your custom instructions that override the default]
EOF

# Your version now takes precedence
```

## Conclusion

The Laravel Boost v2.0 Skills system provides powerful extensibility for AI-assisted development with Kiro IDE. The boost-for-kiro-ide package ensures seamless integration with:

- ✅ Zero configuration required
- ✅ Automatic skill loading
- ✅ Full MCP support
- ✅ Guidelines integration
- ✅ Skill override capability

Skills are loaded automatically by Laravel Boost and delivered to Kiro through the MCP protocol. No additional configuration or code changes are needed in the boost-for-kiro-ide package.

## Additional Resources

- [Laravel Boost Documentation](https://github.com/laravel/boost)
- [Kiro IDE Documentation](https://kiro.amazon.com)
- [Model Context Protocol](https://modelcontextprotocol.io)
- [Manual Testing Guide](../tests/Integration/MANUAL_TESTING_GUIDE.md)

## Support

For issues or questions:
- Package Issues: https://github.com/jotafurtado/boost-for-kiro-ide/issues
- Laravel Boost: https://github.com/laravel/boost/issues
- Kiro IDE: https://kiro.amazon.com/support
