# AUTO-MATCH — Plateforme Web Véhicules d'Occasion

Application web développée en **PHP Orienté Objet (POO)** suivant le motif d'architecture **MVC (Modèle - Vue - Contrôleur)** avec un routeur frontal (*Front Controller*). 

Le design et l'expérience utilisateur reproduisent fidèlement les 4 maquettes Figma du projet :
1. **Accueil AUTO-MATCH** : Catalogue des véhicules sélectionnés avec badges et informations clés.
2. **Honda Civic — Fiche et contact** : Fiche détaillée du véhicule, caractéristiques essentielles et formulaire de contact sans inscription.
3. **Honda Civic — Erreurs du formulaire** : Gestion et affichage précis des erreurs de validation (champs obligatoires, format d'email, alertes).
4. **Honda Civic — Demande confirmée** : Écran de confirmation avec récapitulatif du véhicule et des coordonnées transmises.

---

## 🏛️ Architecture Logicielle Orientée Objet

L'application s'appuie sur une séparation stricte des responsabilités et respecte les principes de la programmation orientée objet :

### 1. La couche Données (`Db.php` & `config/database.php`)
- **`Db`** : Classe encapsulant la connexion PDO (Pattern Singleton).
- Gère automatiquement la connexion MySQL (compatible MAMP sur port `8889` ou standard `3306`), et dispose d'un fallback SQLite automatique pour garantir que le projet fonctionne immédiatement sur n'importe quel environnement, même sans serveur MySQL démarré.

### 2. Les Modèles (`model/`)
- **`Vehicle.php`** : Modèle principal représentant un véhicule (propriétés protégées, constructeur, getters, setters, méthodes de formatage `getFormattedPrice()`, `getFormattedMileage()`, et méthodes d'accès aux données `findAll()`, `findById()`).
- **`Car.php`** : Classe spécialisée héritant de `Vehicle` (`extends Vehicle`), illustrant le principe fondamental d'**Héritage** et de **Polymorphisme** en POO (analogue à `Teacher` et `Student` qui héritent de `User`).
- **`ContactRequest.php`** : Modèle métier gérant la demande de contact. Intègre la méthode `validate()` pour contrôler la conformité des données (messages d'erreurs identiques à la maquette Figma) et la persistance en base.
- **`Reservation.php`** : Modèle gérant l'action "Réserver la voiture".
- **`User.php`** : Modèle utilisateur issu du TP d'architecture logicielle, permettant la gestion de compte, l'inscription et la connexion sécurisée (`password_hash` et compatibilité `sha1`).

### 3. Les Contrôleurs (`controller/`)
- **`BaseController.php`** : Classe abstraite fournissant les utilitaires communs (`render()`, `redirect()`, gestion des messages flash).
- **`VehicleController.php`** : Contrôleur gérant l'affichage du catalogue (`index()`), la fiche détaillée (`show()`), le traitement et la validation du formulaire de contact (`submitContact()`), la confirmation (`confirmation()`), et la réservation (`reserve()`).
- **`AuthController.php`** : Contrôleur gérant l'authentification client ("Mon Compte" présent dans le header des maquettes).

### 4. Le Routeur et Point d'entrée (`routeur.php` & `index.php`)
- **`index.php`** : *Front Controller* unique recevant toutes les requêtes HTTP.
- **`routeur.php`** : Classe `Router` qui analyse le paramètre `action` et la méthode HTTP pour déléguer l'exécution au contrôleur approprié.

### 5. Les Vues (`view/`)
- Découpage modulaire :
  - `view/layout/header.php` : En-tête bleu responsive avec logo `AUTO-MATCH` et bouton `Mon Compte`.
  - `view/layout/footer.php` : Pied de page standardisé.
  - `view/catalog.php` : Maquette 1 (Catalogue 3 cartes).
  - `view/vehicle_detail.php` : Maquettes 2 & 3 (Fiche détaillée, spécifications, formulaire de contact et alertes de validation).
  - `view/confirmation.php` : Maquette 4 (Demande confirmée avec récapitulatif 2 colonnes).
  - `view/reservation_success.php` : Réservation confirmée.
  - `view/auth/` : Écrans de connexion, inscription et tableau de bord "Mon Compte".

---

## 🎨 Design & Fidélité aux Maquettes Figma

Le fichier CSS `public/css/style.css` a été élaboré sur-mesure pour reproduire chaque détail visuel :
- **Couleurs exactes** : Bleu royal d'en-tête (`#1855e0`), bleu primaire interactif (`#165df5`), typographie soignée (`#0f172a`, `#64748b`), alertes rouge pastel (`#fef2f2`, border `#fca5a5`, text `#b91c1c`).
- **Composants reproduits** :
  - Badge arrondi `"3 véhicules référencés"`.
  - Cartes véhicules avec image, détails (modèle, version, année, kilométrage, énergie), prix en bleu vif et double bouton d'action.
  - Bloc "L'essentiel du véhicule" avec icônes Année, Kilométrage et Énergie.
  - Encadré d'information bleu *"Une question sur cette Honda Civic ?"*.
  - Formulaire de contact avec pilule d'information, messages d'erreurs en cas de saisie invalide et champ textarea pré-rempli.
  - Écran de confirmation centré avec icône de coche verte, carte véhicule à gauche, carte coordonnées et message à droite, et bouton de retour.

---

## 🚀 Démarrage Rapide

### Option 1 : Serveur PHP intégré (Recommandé en développement)
Ouvrez un terminal dans le dossier du projet et lancez :
```bash
php -S localhost:8000
```
Puis ouvrez votre navigateur sur : [http://localhost:8000](http://localhost:8000)

### Option 2 : Avec MAMP / WAMP / XAMPP
1. Placez le dossier `projetDesignPhp` dans votre répertoire web (`htdocs` ou `www`).
2. Importez le fichier SQL `sql/database.sql` dans phpMyAdmin ou votre client MySQL si vous souhaitez réinitialiser les données (la base `automatch` est déjà configurée).
3. Accédez à l'URL `http://localhost:8888/projetDesignPhp/` (ou port configuré).

---

## 👤 Compte de test (Fonctionnalité "Mon Compte")
- **Email** : `camille.martin@exemple.fr`
- **Mot de passe** : `password123`
*(Vous pouvez également créer un nouveau compte via la page d'inscription)*
