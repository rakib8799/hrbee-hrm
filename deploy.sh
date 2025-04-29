#!/bin/sh

ssh -o StrictHostKeyChecking=no $USER@$HOST << 'ENDSSH'
  set -e  # Exit immediately if any command fails

  # Set the correct path for Node.js and npm
  export PATH=/home/ubuntu/.nvm/versions/node/v20.12.2/bin:$PATH

  cd /var/www/html/hrbee-hrm/
  echo "$PWD"

  git stash -u
  git pull
  echo "Pulled latest changes"

  composer install --no-interaction --prefer-dist --optimize-autoloader
  composer update --no-interaction
  echo "Installed composer dependencies"

  echo "============================Removed old node_modules============================"
  sudo rm -rf /var/www/html/hrbee-hrm/node_modules/

  echo "============================Install node_modules================================"

  # Use the absolute paths
  /home/ubuntu/.nvm/versions/node/v20.12.2/bin/npm install
  /home/ubuntu/.nvm/versions/node/v20.12.2/bin/npm run build

  ln -s /var/www/html/hrbee-hrm/public/media/ /var/www/html/hrbee-hrm/public/build/

  echo "Installed npm dependencies"
  echo "============================npm build done======================================="

  php artisan multi-tenant:migrate --no-interaction
  echo "============================migrated============================================="

  sudo systemctl reload nginx
  echo "Deployed!"
ENDSSH
