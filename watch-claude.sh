#!/bin/bash

# Watch CLAUDE.md for changes and auto-regenerate context.html
# This script monitors /var/www/bixcash.com/CLAUDE.md and runs the Artisan command when it changes

CLAUDE_MD="/var/www/bixcash.com/CLAUDE.md"
ARTISAN_DIR="/var/www/bixcash.com/backend"

echo "👀 Watching CLAUDE.md for changes..."
echo "📂 Monitoring: $CLAUDE_MD"
echo "🔄 Will regenerate context.html on changes"
echo ""
echo "Press Ctrl+C to stop watching..."
echo ""

# Get initial file modification time
LAST_MOD=$(stat -c %Y "$CLAUDE_MD" 2>/dev/null)

while true; do
    # Get current modification time
    CURRENT_MOD=$(stat -c %Y "$CLAUDE_MD" 2>/dev/null)

    # Check if file was modified
    if [ "$CURRENT_MOD" != "$LAST_MOD" ]; then
        echo "[$(date '+%Y-%m-%d %H:%M:%S')] 📝 CLAUDE.md changed detected!"
        echo "🚀 Regenerating context.html..."

        cd "$ARTISAN_DIR"
        php artisan context:generate

        if [ $? -eq 0 ]; then
            echo "✅ context.html regenerated successfully!"
        else
            echo "❌ Failed to regenerate context.html"
        fi

        echo ""
        LAST_MOD=$CURRENT_MOD
    fi

    # Check every 2 seconds
    sleep 2
done
