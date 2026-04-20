<?php

declare(strict_types=1);

namespace Jcf\BoostForKiro\Hooks;

use Illuminate\Support\Collection;
use Laravel\Boost\Mcp\Boost;
use Laravel\Mcp\Server\Prompt;
use Laravel\Mcp\Server\ServerContext;

/**
 * Extends the Boost MCP server to expose prompt discovery.
 *
 * Since Server requires a Transport in its constructor, we override
 * the constructor to avoid that dependency — we only need prompt discovery,
 * not the actual MCP transport layer.
 */
class PromptServer extends Boost
{
    public function __construct()
    {
        // Intentionally skip parent constructor — we don't need Transport.
    }

    /**
     * Get the registered and eligible prompts from the Boost MCP server.
     *
     * @return Collection<int, Prompt>
     */
    public function getPrompts(): Collection
    {
        $classes = $this->discoverPrompts();

        $context = new ServerContext(
            supportedProtocolVersions: [],
            serverCapabilities: [],
            serverName: '',
            serverVersion: '',
            instructions: '',
            maxPaginationLength: 50,
            defaultPaginationLength: 50,
            tools: [],
            resources: [],
            prompts: $classes,
        );

        return $context->prompts();
    }
}
