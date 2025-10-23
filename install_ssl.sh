#!/bin/bash

echo "=== Installing SSL certificates for news24.az ==="
echo ""

# Install certbot
echo "Step 1: Installing Certbot..."
sudo apt-get update
sudo apt-get install -y certbot

# Stop nginx container temporarily
echo ""
echo "Step 2: Stopping nginx container..."
docker compose down nginx-new1

# Generate certificates for all domains
echo ""
echo "Step 3: Generating SSL certificates..."
sudo certbot certonly --standalone \
  -d new.news24.az \
  -d edm.news24.az \
  -d www.news24.az \
  --non-interactive \
  --agree-tos \
  --email admin@news24.az \
  --http-01-port=80

# Create SSL directory in project
echo ""
echo "Step 4: Setting up SSL certificates..."
mkdir -p ./docker/ssl
sudo cp /etc/letsencrypt/live/new.news24.az/fullchain.pem ./docker/ssl/
sudo cp /etc/letsencrypt/live/new.news24.az/privkey.pem ./docker/ssl/
sudo chown -R admin:admin ./docker/ssl/
sudo chmod 644 ./docker/ssl/*

# Update nginx config to use SSL config
echo ""
echo "Step 5: Updating nginx configuration..."
# Uncomment SSL lines in docker-compose.yml
sed -i 's|# - ./docker/nginx-ssl.conf:/etc/nginx/conf.d/default.conf|- ./docker/nginx-ssl.conf:/etc/nginx/conf.d/default.conf|g' docker-compose.yml
sed -i 's|# - ./docker/ssl:/etc/nginx/ssl:ro|- ./docker/ssl:/etc/nginx/ssl:ro|g' docker-compose.yml
# Comment out the temp config
sed -i 's|- ./docker/nginx-temp.conf:/etc/nginx/conf.d/default.conf|# - ./docker/nginx-temp.conf:/etc/nginx/conf.d/default.conf|g' docker-compose.yml

# Restart nginx with SSL
echo ""
echo "Step 6: Restarting nginx with SSL..."
docker compose up -d nginx-new1

echo ""
echo "=== SSL installation completed! ==="
echo ""
echo "✅ Your sites are now available at:"
echo "   https://new.news24.az"
echo "   https://edm.news24.az"
echo "   https://www.news24.az"
echo ""
echo "ℹ️  SSL certificates will auto-renew via certbot"
