# 🛒 Système de Rappel de Panier - PharmaFind

## 📋 Description

Ce système envoie automatiquement des emails de rappel aux utilisateurs qui ont des produits dans leur panier depuis plus de 10 heures sans passer de commande ou de réservation.

## 🚀 Fonctionnalités

- ✅ **Détection automatique** des paniers abandonnés
- ✅ **Email personnalisé** avec les détails du panier
- ✅ **Planification horaire** des vérifications
- ✅ **Commande de test** pour vérifier le système
- ✅ **Gestion des sessions** pour chaque utilisateur

## ⚙️ Configuration

### 1. Configuration Email

Dans votre fichier `.env`, configurez l'envoi d'emails :

```env
MAIL_MAILER=log
MAIL_FROM_ADDRESS=noreply@PharmaFind.com
MAIL_FROM_NAME="PharmaFind"
```

Pour la production, utilisez SMTP :

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@gmail.com
MAIL_PASSWORD=votre-mot-de-passe-app
MAIL_ENCRYPTION=tls
```

### 2. Planification des Tâches

Le système vérifie les paniers toutes les heures. Pour activer la planification :

```bash
# Ajouter au crontab (Linux/Mac)
* * * * * cd /path/to/pharmacie && php artisan schedule:run >> /dev/null 2>&1

# Pour Windows, utilisez le Task Scheduler
```

## 🧪 Test du Système

### 1. Test Manuel

```bash
# Envoyer les rappels manuellement
php artisan cart:send-reminders

# Ou utiliser la commande
php artisan schedule:run
```

### 2. Test via Interface Web

Connectez-vous et visitez : `/test-cart-reminder`

## 📧 Format de l'Email

L'email contient :
- Nom de l'utilisateur
- Nombre de produits dans le panier
- Total du panier
- Lien vers le panier
- Message d'incitation

## 🔧 Personnalisation

### Modifier le délai (actuellement 10h)

Dans `app/Jobs/CheckCartReminders.php` :

```php
// Ligne 35 : changer 10 par le nombre d'heures souhaité
if ($hoursDiff >= 10) {
```

### Modifier le contenu de l'email

Dans `app/Notifications/CartReminderNotification.php` :

```php
public function toMail(object $notifiable): MailMessage
{
    // Personnalisez le contenu ici
}
```

### Modifier la fréquence de vérification

Dans `routes/console.php` :

```php
// Changer hourly() par :
Schedule::job(new CheckCartReminders)->everyMinute(); // Toutes les minutes
Schedule::job(new CheckCartReminders)->daily();       // Tous les jours
Schedule::job(new CheckCartReminders)->weekly();      // Toutes les semaines
```

## 📊 Logs

Les emails sont loggés dans : `storage/logs/laravel.log`

## 🛠️ Dépannage

### Problème : Les emails ne s'envoient pas

1. Vérifiez la configuration email dans `.env`
2. Vérifiez les logs : `tail -f storage/logs/laravel.log`
3. Testez manuellement : `php artisan cart:send-reminders`

### Problème : La planification ne fonctionne pas

1. Vérifiez que le cron est configuré
2. Testez : `php artisan schedule:run`
3. Vérifiez les logs du système

## 📝 Notes

- Le système utilise les sessions Laravel pour stocker les paniers
- Chaque utilisateur a son propre panier et son propre timer
- Les rappels sont envoyés une seule fois par panier
- Le système est compatible avec les queues Laravel pour de meilleures performances

## 🔄 Mise à Jour

Pour mettre à jour le système :

```bash
composer update
php artisan config:cache
php artisan route:cache
``` 