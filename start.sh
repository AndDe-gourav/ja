#!/bin/bash
# Start PHP development server on port 5000
# The -t flag sets the document root to public directory
cd "$(dirname "$0")"
php -S 0.0.0.0:5000 -t public
