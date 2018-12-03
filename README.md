# Louvre_projet4
Openclassrooms project 4

## Mettre le projet en ligne

Pour commencer, cloner le projet sur votre poste de travail : `git clone https://github.com/mimo1449/Louvre_projet4.git`

### Créer votre profil Stripe
Rendez vous sur https://stripe.com/fr et créer votre profil. 
Récupérer vos clés pour plus tard

### Installer les dépendances
Il faut ensuite installer toutes les dépendances dans le dossiers vendor, en executant la commande suivante : `composer install`

### Modifier le fichier .env
Configurer l'accès à la base de données
```
###> doctrine/doctrine-bundle ###
# Format described at http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html#connecting-using-a-url
# For an SQLite database, use: "sqlite:///%kernel.project_dir%/var/data.db"
# Configure your db driver and server_version in config/packages/doctrine.yaml
DATABASE_URL=mysql://username:password@127.0.0.1:3306/databaseName
###< doctrine/doctrine-bundle ###
```
pour plus d'infos consulter la page https://symfony.com/doc/current/doctrine.html

Rentrer les clés Srtipe fournies lors de la création de votre compte
copier et coller le lignes ci-dessous dans le fichier .env
```
STRIPE_PUBLIC_KEY="pk_test_votre cle"
STRIPE_SECRET_KEY="sk_test_votre cle"
```
Rentrer la configuration de la boite mail d'envoi
```
###> symfony/swiftmailer-bundle ###
# For Gmail as a transport, use: "gmail://username:password@localhost"
# For a generic SMTP server, use: "smtp://localhost:25?encryption=&auth_mode="
# Delivery is disabled by default via "null://localhost"
MAILER_URL=smtp://localhost:25?encryption=&auth_mode=login&username=&password=
###< symfony/swiftmailer-bundle ###
```
Pour plus d'infos consulter la page https://symfony.com/doc/current/email.html

### Créer les tables de la base de données
Une fois les configuration d'accès à la base de donnée entrées, créer la base en entrant la commande:
```
php bin/console doctrine:database:create
```
Créer le schéma de la base avant la migration
```
php bin/console make:migration
```
Faire la migration
```
php bin/console doctrine:migrations:migrate
```

### Lancement du serveur en local
Il suffit d'utiliser le serveur interne de php : 
```
php bin/console server:run
```

