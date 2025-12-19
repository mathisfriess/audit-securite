# Audit Sécurité – Blog

**Auteur :** FRIESS Mathis  
**Type :** Audit de sécurité applicative (Web)

---

## Présentation

Ce projet consiste en un audit de sécurité d’un site de blog permettant :
- la consultation et la publication d’articles
- la création de comptes utilisateurs
- l’ajout de commentaires
- la recherche de contenu

---

## Périmètre du test

L’audit couvre l’intégralité du site :
- interface publique
- fonctionnalités utilisateur
- interface d’administration
- configuration du projet

---

## Objectifs

- Identifier les vulnérabilités de sécurité
- Évaluer les risques associés
- Proposer des corrections justifiées
- Fournir une base de documentation sécurité

---

## Méthodologie

L’audit a été réalisé en deux phases :

**Black Box**  
Tests sans accès au code source afin d’identifier les failles exploitables rapidement par un attaquant externe.

**White Box**  
Analyse du code source pour confirmer les failles, en découvrir de nouvelles et proposer des correctifs précis.

---

## Résumé exécutif

L’audit met en évidence de nombreuses vulnérabilités critiques permettant :
- l’accès à des données sensibles
- l’exécution de code malveillant
- l’escalade de privilèges
- la compromission totale du site

Le niveau de sécurité global est insuffisant pour une mise en production.

---

## Environnement testé

- Application web en **PHP**
- Base de données **SQLite**
- Aucune protection avancée (framework, WAF, sandbox)
- Tests réalisés en ligne puis en local avec accès au code

---

## Résultats principaux

Vulnérabilités identifiées :
- Injections SQL multiples
- XSS stockées et réfléchies
- Contrôles d’accès défaillants (IDOR, privilèges admin)
- Upload de fichiers non sécurisé
- Fuite de données sensibles
- Absence de protection CSRF
- Mauvaise configuration Git exposant des fichiers sensibles

Failles exploitables avec un faible niveau technique.

---

## Analyse des risques

Risques élevés :
- Vol de données utilisateurs
- Compromission de comptes
- Exécution de code à distance
- Défiguration du site
- Impact légal et perte de confiance

Catégories OWASP concernées :
- A01 – Broken Access Control
- A03 – Injection
- A05 – Security Misconfiguration
- A07 – Identification & Authentication Failures

---

## Recommandations

- Utiliser uniquement des requêtes préparées
- Échapper toutes les données avant affichage
- Implémenter un contrôle d’accès strict côté serveur
- Sécuriser l’upload de fichiers (type, taille, stockage)
- Ne jamais exposer d’erreurs techniques ou de mots de passe
- Ajouter une protection CSRF
- Corriger le `.gitignore` et séparer configuration / code

---

## Conclusion

Le site présente de nombreuses failles critiques le rendant impropre à une mise en production.  
Les vulnérabilités sont toutefois classiques et peuvent être corrigées par l’application de bonnes pratiques de sécurité web.

Une nouvelle phase de tests est recommandée après correction.
