# Site de E-commerce

## Réflexion autour des entités

Nous allons concevoir notre Diagramme relationnel d'entité (erDiagram).

- de quelles entités avons nous besoin ?
- qu'elles sont les relations entre ces entités ?


**Par étape :**

- fournir une liste de toutes les entités 
- penser les propriétés de ces entités
- définir les relations entre chaque entités 

### Entités définies ensemble 

- User
- Customer
- CustomerAddress
---
- Category
- Product
- Review
---
- Order
- OrderLine
  - product
  - qty
  - price
- Payment


## Mermaid 

**🖥️ Prise en main de Mermaid.**
https://mermaid.js.org/syntax/entityRelationshipDiagram.html

Nous allons définir ensuite notre Diagram relation d'entités à l'aide Mermaid.

- Créer un fichier readme.md
- Structurer le document avec un chapitre `Diagramme relationnel d'entités`
- Concevoir le Diagram

**ℹ️ Pour en savoir plus


    ⚠️ Le fichier `readme.md` sera utilisé comme documentation de notre projet.
    Il sera placé à la racine de votre projet et comportera en plus les information pour l'installation et la configuration de votre projet.


### Les relations entre entités avec Mermaid

| Value (left) | Value (right)	| Meaning
| :--------------- |:---------------:| -----:|
| \\|o |	o\\|	| Zero or one 
| \\|\\| | \\|\\|	| Exactly one 
|}o	 | o{ |	Zero or more (no upper limit)
| }\\| |	\\|{	| One or more (no upper limit)


### Exemple

```mermaid
---
title: Exemple Collector
---
erDiagram
    user {
        int id PK
        string(255) email
        string password
        array role
    }
    collector {
        int id PK
        string(255) firstname
        string(255) lastname
        string(255) phone
        int age
        string(255) city
        string(255) country
    }
    photo {
        int id PK
        string(255) title
        string description
        string(255) image_url
        float price
        json meta_info
    }

    tag {
        int id PK
        string(255) name
    }

    photo_tag {
        int tag_id PK, FK
        int photo_id PK, FK
    }

    user ||--o| collector : has
    collector ||--o{ photo : has
    photo ||--o{ photo_tag : has
    tag ||--o{ photo_tag : contains
```


### Notre ErDiagramm
```mermaid
---
title: E-Commerce
---
erDiagram
    User {
        int id PK
        string(255) email
        string password
        array role
        datetime createdAt
        datetime modifiedAt
    }
    Customer {
        int id PK
        string(100) lastname
        string(100) firstname
        string(100) phone
        datetime birthdateAt
    }
    CustomerAddress {
        int id PK
        string(100) name
        string(255) line1
        string(255) line2
        string(50) zipCode
        string(100) city
        string(100) country
        array type
    }
    Product {
        int id PK
        string(100) name
        text description
        float price
        int stock
        string(100) slug
        datetime createdAt
        datetime modifiedAt
    }
    Tva {
        int id PK
        string(100) name
        float value
        datetime createdAt
        datetime modifiedAt
    }
    ProductImage {
        int id PK
        string(100) name
        string(255) file
        datetime createdAt
        datetime modifiedAt
    }
    Category {
        int id PK
        string(100) name
        text description
        string(100) slug
        datetime createdAt
        datetime modifiedAt
    }
    Review {
        int id PK
        text content
        int review
        datetime createdAt
        datetime modifiedAt
    }
    Order {
        int id PK
        string(255) orderNumber
        string(100) status
        datetime createdAt
        datetime shippedAt
    }
    OrderLine {
        int id PK
        float price
        float tva
        int qty
    }
    Payment {
        int id PK
        string(255) type
        float amount
        datetime createdAt
    }

    Review }o--|| Customer : has
    Review }o--|| Product : has
    Product ||--|{ ProductImage : has
    Product }o--|| Tva : taxed
    Category }|--o{ Product : inside
    User ||--o| Customer : is
    Customer ||--}| CustomerAddress : has
    Order ||--}| OrderLine : inside
    Payment }o--|| Order : isPaid
    Customer ||--o{ Order : has
    OrderLine }|--|| Product : inside
```


## Installation

Cloner le github


```bash 

    php bin/console d:d:c

 
    # Fixtures
    php bin/console 

```

## Commande Symfony utilisées

```bash

    # Creation d'entité
    php bin/console make:entity

```