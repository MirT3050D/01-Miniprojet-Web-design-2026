# CRUD Complet du Backoffice - Documentation

## Vue d'ensemble

Un système CRUD (Create, Read, Update, Delete) complet a été implémenté pour le backoffice "Iran Situation Desk".

## Fonctionnalités

### 📝 CREATE - Créer un article
**Fichier:** `pages/index_back_office.php`

- Formulaire pour créer un nouvel article
- Champs:
  - **Titre (H1)** - Obligatoire
  - **URL slug** - Auto-généré depuis le titre, modifiable
  - **Image héroïque** - Upload optionnel
  - **Alt text image** - Pour l'accessibilité SEO
  - **Meta description** - Max 160 caractères avec compteur
  - **Contenu HTML** - Éditeur TinyMCE intégré
- Redirection vers la liste des articles après création

### 📋 READ - Lister et consulter les articles
**Fichier:** `pages/articles_list.php`

- Tableau responsive affichant tous les articles
- Colonnes:
  - Titre de l'article
  - URL slug (code)
  - Image (lien vers l'image)
  - Date de création
  - Actions (Éditer/Supprimer)
- Statistiques: nombre total d'articles
- Affichage des messages de succès/erreur
- Navigation vers création d'article
- État vide avec lien vers création si aucun article

### ✏️ UPDATE - Éditer un article
**Fichier:** `pages/edit_article.php`

- Formulaire pré-rempli avec les données de l'article
- Mêmes champs que la création
- Possibilité de remplacer l'image
- Affichage de l'image actuelle
- Lien pour retourner à la liste
- Affichage des métadonnées de l'article (ID, date création)

### 🗑️ DELETE - Supprimer un article
**Fichier:** Intégré dans la liste et le contrôleur

- Bouton "Supprimer" avec confirmation
- Suppression définitive de l'article de la base de données
- Message de confirmation avant suppression

## Architecture

### Contrôleur Principal
**Fichier:** `inc/article_controller.php`

Gère toutes les opérations CRUD:
```
GET/POST /inc/article_controller.php?action=create  → Crée un article
GET/POST /inc/article_controller.php?action=update  → Met à jour un article
GET/POST /inc/article_controller.php?action=delete  → Supprime un article
```

### Base de données
Table `articles` avec les colonnes:
- `id` - Clé primaire
- `titre_h1` - Titre principal
- `url_slug` - URL unique
- `contenu_html` - Contenu éditorial
- `image_url` - Chemin de l'image
- `image_alt` - Texte alternatif
- `meta_description` - Description SEO
- `date_creation` - Timestamp automatique

## Flux de navigation

```
1. LOGIN (login.php)
   ↓
2. BACKOFFICE DASHBOARD
   ├─ [+ Nouvel article] → index_back_office.php (CREATE)
   └─ [📋 Voir articles] → articles_list.php (READ)
      ├─ [✎ Éditer] → edit_article.php?id=X (UPDATE)
      └─ [✕ Supprimer] → Suppression (DELETE)
```

## Sécurité

✓ **Sessions requises** - Tous les accès protégés par session utilisateur  
✓ **Validations** - Vérifications des champs obligatoires  
✓ **Préparation SQL** - Protégé against les injections SQL (PDO prepared statements)  
✓ **Confirmation suppression** - Dialog JavaScript avant suppression  
✓ **Gestion erreurs** - Redirection avec messages d'erreur explicites  

## Messages utilisateur

### Succès
- ✓ Article créé avec succès
- ✓ Article mis à jour avec succès
- ✓ Article supprimé avec succès

### Erreurs
- ✗ Une erreur est survenue
- ✗ Cet URL slug existe déjà
- ✗ Article introuvable
- ✗ Données invalides
- ✗ Erreur lors du téléchargement d'image

## Styles et présentation

### CSS ajoutés (assets/css/backoffice.css)
- `.alert-success` et `.alert-error` - Notifications
- `.btn`, `.btn-primary`, `.btn-secondary`, `.btn-danger` - Système de boutons
- `.articles-table` - Tableau responsive
- `.empty-state` - État vide
- Animations: `rise`, `fade-in`, `slide-in`

### Design
- Palette cohérente avec le design existant
- Responsive sur mobile
- Animations fluides
- Typographie Georgia/Segoe UI

## JavaScript (assets/js/backoffice.js)

Fonctionnalités améliorées:
- **Slugify** - Convertit le titre en URL-friendly slug
- **Auto-slug** - Génère automatiquement le slug du titre
- **Meta counter** - Compte les caractères de la meta description
- **TinyMCE** - Éditeur WYSIWYG pour le contenu
- **Delete confirmation** - Confirmation avant suppression

## Utilisation

### Pour créer un article:
1. Se connecter en tant qu'admin  
2. Cliquer "+ Nouvel article"  
3. Remplir le formulaire  
4. Cliquer "Créer l'article"  

### Pour éditer:
1. Aller à la liste des articles  
2. Cliquer "✎ Éditer" sur un article  
3. Modifier les champs  
4. Cliquer "Mettre à jour l'article"  

### Pour supprimer:
1. Aller à la liste des articles  
2. Cliquer "✕ Supprimer"  
3. Confirmer la suppression  

## Fichiers modifiés/créés

### Nouveaux fichiers
- `pages/articles_list.php` - Liste des articles
- `pages/edit_article.php` - Édition d'article
- `inc/article_controller.php` - Contrôleur CRUD

### Fichiers modifiés
- `pages/index_back_office.php` - Lien vers liste + action UPDATE
- `assets/css/backoffice.css` - Styles supplémentaires
- `assets/js/backoffice.js` - Améliorations JavaScript
- `inc/index_back_office_controller.php` - Correction import

## Prochains développements possibles

- [ ] Recherche/filtrage par titre
- [ ] Pagination si trop d'articles
- [ ] Tri par colonnes
- [ ] Brouillons (articles non publiés)
- [ ] Historique des modifications
- [ ] Permissions utilisateur
- [ ] Bulk actions (suppression multiple)
- [ ] Export/Import d'articles
