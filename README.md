# 🐘 PHPMongoDB

A simple and lightweight **web-based administrative tool for MongoDB**, built in PHP.  
Manage **databases, collections, and documents** directly from your browser.

---

## 📜 License
This project is licensed under the **MIT License**.

---

## 🚀 Features
- Clean web UI to browse databases & collections
- Insert, update, and delete documents
- Export & import collections
- Uses the official MongoDB PHP driver
- Lightweight, simple, and fast

---

## 📦 Manual Installation

### Step 1: Install the MongoDB PHP Driver
```bash
pecl install mongodb
# Enable the extension (adds to your loaded php.ini)
echo "extension=mongodb.so" | sudo tee -a "$(php --ini | grep 'Loaded Configuration' | sed -E 's|.*:\s*||')"
```

### Verify it’s loaded
```bash
php -m | grep mongodb
```

### Step 2: Get the Code
```bash
git clone https://github.com/phpmongodb/phpmongodb.git
cd phpmongodb
```

### Step 3: Install PHP Dependencies
```bash
composer install --no-dev --optimize-autoloader
```

### Step 4: Serve the App
```bash
php -S 0.0.0.0:8080 -t .
```

### Access in browser
Open: <http://localhost:8080>



## 📸 Screenshots

### Dashboard – Collections View
![Collections View](https://res.cloudinary.com/dinp1dyuh/image/upload/v1758177976/phpmongodb-index_iuivkk.png)

### Records – Document Browser
![Record Browser](https://res.cloudinary.com/dinp1dyuh/image/upload/v1758177977/phpmongodb-record_kfmxqm.png)
