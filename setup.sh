#!/bin/bash
# 🚀 Laravel Queue + Mail - Setup & Run Script

echo "╔════════════════════════════════════════════════════════════╗"
echo "║   Laravel Queue + Mail - Bài Tập 8 Setup & Run            ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo ""

# Colors for output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Step 1: Check PHP
echo -e "${BLUE}[1/6]${NC} Checking PHP installation..."
php -v | head -n 1
if [ $? -ne 0 ]; then
    echo "❌ PHP not found! Install PHP first."
    exit 1
fi
echo -e "${GREEN}✓ PHP OK${NC}\n"

# Step 2: Check Composer
echo -e "${BLUE}[2/6]${NC} Checking Composer..."
composer --version
if [ $? -ne 0 ]; then
    echo "❌ Composer not found!"
    exit 1
fi
echo -e "${GREEN}✓ Composer OK${NC}\n"

# Step 3: Install dependencies
echo -e "${BLUE}[3/6]${NC} Installing dependencies..."
composer install --no-interaction
if [ $? -ne 0 ]; then
    echo "❌ Composer install failed!"
    exit 1
fi
echo -e "${GREEN}✓ Dependencies installed${NC}\n"

# Step 4: Setup .env
echo -e "${BLUE}[4/6]${NC} Setting up .env..."
if [ ! -f .env ]; then
    cp .env.example .env
    echo -e "${GREEN}✓ .env created from .env.example${NC}"
else
    echo -e "${GREEN}✓ .env already exists${NC}"
fi

# Generate app key if needed
if ! grep -q "APP_KEY=base64" .env; then
    php artisan key:generate
    echo -e "${GREEN}✓ APP_KEY generated${NC}"
else
    echo -e "${GREEN}✓ APP_KEY already set${NC}"
fi
echo ""

# Step 5: Run migrations
echo -e "${BLUE}[5/6]${NC} Running migrations..."
php artisan migrate --force
if [ $? -ne 0 ]; then
    echo "❌ Migration failed!"
    exit 1
fi
echo -e "${GREEN}✓ Migrations completed${NC}\n"

# Step 6: Clear cache
echo -e "${BLUE}[6/6]${NC} Clearing cache..."
php artisan cache:clear
php artisan config:clear
php artisan view:clear
echo -e "${GREEN}✓ Cache cleared${NC}\n"

echo "╔════════════════════════════════════════════════════════════╗"
echo -e "║                  ${GREEN}✓ SETUP COMPLETE${NC}                       ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo ""
echo -e "${YELLOW}📋 Next Steps:${NC}"
echo ""
echo "1️⃣  Terminal 1 - Start Laravel Server:"
echo "   ${BLUE}php artisan serve${NC}"
echo ""
echo "2️⃣  Terminal 2 - Start Queue Worker:"
echo "   ${BLUE}php artisan queue:work${NC}"
echo ""
echo "3️⃣  Open Browser:"
echo "   📝 Register: ${BLUE}http://localhost:8000/register${NC}"
echo "   📊 Dashboard: ${BLUE}http://localhost:8000/dashboard${NC}"
echo "   📋 Job Logs: ${BLUE}http://localhost:8000/job-logs${NC}"
echo ""
echo -e "${YELLOW}🧪 Testing (in new terminal):${NC}"
echo "   ${BLUE}php artisan tinker${NC}"
echo "   > App\Jobs\SendWelcomeEmailJob::dispatch('test@example.com', 'Test');"
echo ""
echo -e "${YELLOW}📚 Documentation:${NC}"
echo "   • QUICKSTART.md - 5 minute guide"
echo "   • IMPLEMENTATION_GUIDE.md - Detailed setup"
echo "   • QUEUE_GUIDE.md - Complete reference"
echo "   • COMPLETION_SUMMARY.md - What was done"
echo ""
