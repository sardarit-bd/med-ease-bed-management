# 🏥 Hospital Bed Management System API

A professional, REST API for managing hospital beds, patient admissions, and facility resources.

---

## 🛠 Tech Stack

* **Framework:** Laravel 12.x
* **Language:** PHP 8.2+
* **Database:** MySQL 8.0+

---

## ⚙️ Prerequisites

Ensure you have the following installed on your local machine:

* [PHP 8.2](https://www.php.net/downloads) or higher
* [Composer](https://getcomposer.org/)
* [MySQL](https://www.mysql.com/)
* [Git](https://git-scm.com/)

---

## 📥 Installation Guide

Follow these steps to set up the project locally.

### 1. Clone the Repository

```bash
git clone https://github.com/sardarit-bd/med-ease-bed-management.git
cd med-ease-bed-management

```

### 2. Install Dependencies

```bash
composer install

```

### 3. Environment Configuration

Copy the example environment file and configure your database credentials.

```bash
cp .env.example .env

```

Open the `.env` file and update your database settings:

```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=med_ease_bed_management
DB_USERNAME=root
DB_PASSWORD=your_password

```

### 4. Generate App Key

```bash
php artisan key:generate

```

### 5. Run Migrations

This will create the database tables (Facilities, Services, Beds, Patients, Admissions).

```bash
php artisan migrate

```

*(Optional) Seed the database with dummy data:*

```bash
php artisan db:seed --class=BedManagementSeeder

```

### 6. Serve the Application

```bash
php artisan serve

```

The API will be available at: `http://127.0.0.1:8000/api`

---

## 📖 API Documentation

### **Base URL**

`http://127.0.0.1:8000/api`

### **1. Facilities (Hospitals)**

| Method | Endpoint | Description |
| --- | --- | --- |
| `GET` | `/facilities` | List all hospitals/clinics |
| `POST` | `/facilities` | Create a new facility |
| `GET` | `/facilities/{id}` | Get facility details |
| `PUT` | `/facilities/{id}` | Update facility details |
| `DELETE` | `/facilities/{id}` | Delete facility (if empty) |

### **2. Services (Departments)**

| Method | Endpoint | Description |
| --- | --- | --- |
| `GET` | `/services` | List departments (e.g., Cardiology) |
| `POST` | `/services` | Create a new department |
| `PUT` | `/services/{id}` | Update department |
| `DELETE` | `/services/{id}` | Delete department (if empty) |

### **3. Patients**

| Method | Endpoint | Description |
| --- | --- | --- |
| `GET` | `/patients` | List patients (Searchable via `?search=Name`) |
| `POST` | `/patients` | Register a new patient |
| `PUT` | `/patients/{id}` | Update patient details |
| `DELETE` | `/patients/{id}` | Delete patient (if no history) |

### **4. Beds (CRUD)**

| Method | Endpoint | Description |
| --- | --- | --- |
| `GET` | `/beds` | Dashboard view of all beds |
| `POST` | `/beds` | Create a new bed |
| `PUT` | `/beds/{id}` | Update bed label/service |
| `DELETE` | `/beds/{id}` | Delete bed (if available) |

### **5. Bed Operations (Logic)**

| Method | Endpoint | Description |
| --- | --- | --- |
| `POST` | `/beds/{id}/assign` | **Admit Patient** (Changes status to `occupied`) |
| `POST` | `/beds/{id}/discharge` | **Discharge Patient** (Changes status to `cleaning`) |
| `PATCH` | `/beds/{id}/status` | **Update Status** (e.g., Mark as `available`) |

---

## 🧪 Testing

You can test the API endpoints using **Postman** or **cURL**.

**Example: Admit a Patient**

```bash
curl -X POST http://127.0.0.1:8000/api/beds/{BED_UUID}/assign \
-H "Content-Type: application/json" \
-d '{"patient_id": "{PATIENT_UUID}", "notes": "Admitted via ER"}'

```

---

## 🔒 Security & Best Practices

* **Validation:** Strict `FormRequest` validation for all inputs.
* **UUIDs:** Used for all primary keys to prevent ID enumeration.
* **Transactions:** Ensures data integrity during complex operations.
* **Standardized Responses:** Unified JSON structure for Success and Error responses.
