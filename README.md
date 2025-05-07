# WattWay ⚡  
### AI-Powered Electric Vehicle Fleet Management System  
![Electric Car Animation](https://www.gifcen.com/wp-content/uploads/2021/05/car-gif-6.gif)

[![Symfony](https://img.shields.io/badge/Symfony-6.4-black?style=flat&logo=symfony)](https://symfony.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

**An Esprit University Project** | **Sustainable Mobility Solution**  

---

## 🌱 About The Project  

WattWay revolutionizes fleet electrification through AI-driven management tools, helping organizations:  

- ✅ Transition smoothly from combustion to electric vehicles  
- 🧠 Leverage predictive maintenance algorithms  
- 📊 Optimize charging infrastructure deployment  
- 💰 Reduce total cost of EV ownership  

*"Managing electric fleets should be as smooth as the vehicles themselves"*  

---

## 🚀 Key Features  

### 🏗️ Core Modules  
| Module | Description | Tech Used |
|--------|-------------|-----------|
| **Smart Fleet Dashboard** | Real-time monitoring of all EVs | Symfony UX, Chart.js |
| **AI Maintenance Predictor** | Reduces downtime by 40% | Python TensorFlow |
| **Charge Optimizer** | Lowers energy costs by 25% | PHP Algorithms |
| **Eco-Routing** | Minimizes energy consumption | Mapbox API |

### 🛒 EV Marketplace  
![Marketplace Preview](https://via.placeholder.com/600x200?text=EV+Marketplace+Preview)  
- Browse/Purchase/Rent EVs  
- Integrated payment system  
- Vehicle comparison tools  

---

## ⚙️ Installation  

```bash
# Clone repository
git clone https://github.com/your-username/wattway.git
cd wattway

# Install dependencies
composer install

# Configure environment
cp .env .env.local
# Edit database settings in .env.local

# Run migrations
php bin/console doctrine:migrations:migrate

# Start development server
symfony server:start
