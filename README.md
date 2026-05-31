# TaskFlow

TaskFlow est une application web de gestion de tâches développée en PHP, MySQL, HTML, CSS et JavaScript.

## Fonctionnalités

* Inscription et connexion utilisateur
* Authentification sécurisée avec mot de passe hashé
* Création de tâches
* Modification de tâches
* Suppression de tâches
* Recherche de tâches
* Filtrage par catégorie
* Filtrage par priorité
* Marquage des tâches comme terminées
* Gestion des catégories
* Rappels par email avec PHPMailer
* Interface responsive pour mobile et desktop

## Technologies utilisées

* PHP
* MySQL
* HTML5
* CSS3
* JavaScript
* PHPMailer
* Composer

## Installation

### 1. Cloner le projet

```bash
git clone https://github.com/hibaajala23-pixel/taskflow.git
```

### 2. Importer la base de données

Importer le fichier :

```sql
database.sql
```

dans phpMyAdmin.

### 3. Installer les dépendances

```bash
composer install
```

### 4. Configurer la base de données

Modifier le fichier :

```php
db.php
```

et renseigner :

```php
$host = "sql111.infinityfree.com";
$dbname = "if0_42051541_taskflow";
$username = "if0_42051541";
$password = "mFMpCUNmhO4";
```

### 5. Configurer PHPMailer

Dans le fichier :

```php
send_reminders.php
```

renseigner :s

```php
$mail->Username = "h.chrorou@gmail.com";
$mail->Password = "nphx bouz qryd hakj";
```

### 6. Lancer le projet

Placer le projet dans :

```text
htdocs/
```

puis ouvrir :

```text
http://localhost/taskflow
```
## Live Demo

https://taskflow-app.infinityfree.me

## Structure du projet

```text
taskflow/
├──  index.html
├── dashboard.php
├── create_task.php
├── save_task.php
├── edit_task.php
├── delete_task.php
├── login.php
├── register.php
├── logout.php
├── send_reminders.php
├── db.php
├── composer.json
├── composer.lock
├── connexion.html
└── README.md

 Auteur

Développé par [CHROROU HIBA , HIBA AJALA , HAYAT ZAK]

