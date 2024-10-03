# Variable
SYMFONY_CONSOLE = symfony console

# Couleurs
GREEN = /bin/echo -e "\x1b[32m\"
RED = /bin/echo -e "\x1b[31m\"

## Assets
ai: ## assets:Install - Installer les assets web d'un bundle dans un répertoire public
	$(SYMFONY_CONSOLE) assets:Install

## Asset-map
amc: ## assets-map - Compiler tous les assets mappés et les écrire dans le répertoire de sortie public final 
	$(SYMFONY_CONSOLE) assets-map:compile

## Cache
cc: ## cache:clear - Vider le cache 
	$(SYMFONY_CONSOLE) cache:clear
cpc: ## cache:pool:clear - Vider les pools de cache
	$(SYMFONY_CONSOLE) cache:pool:clear
cpd: ## cache:pool:delete - Supprimer un élément d'un pool de cache
	$(SYMFONY_CONSOLE) cache:pool:delete
cpi: ## cache:pool:invalidate-tags - Invalider les tags de cache pour tous ou un pool spécifique
	$(SYMFONY_CONSOLE) cache:pool:invalidate-tags
cpl: ## cache:pool:list - Lister les pools de cache disponible
	$(SYMFONY_CONSOLE) cache:pool:list
cpp: ## cache:pool:prune - Nettoyer un pool de cache
	$(SYMFONY_CONSOLE) cache:pool:prune		 
cw: ## cache:warmup - Préparer un cache vide
	$(SYMFONY_CONSOLE) cache:warmup

## config
cd: ## config:dump-reference - Afficher la configuration par défaut pour une extention
	$(SYMFONY_CONSOLE) config:dump-reference

## dbal
drs: ## dbal:run-sql - Exécuter une requête SQL directement depuis la ligne de commande
	$(SYMFONY_CONSOLE) dbal:run-sql 

## debug
dam: ## debug:asset-map - Afficher tous les assets mappés
	$(SYMFONY_CONSOLE) debug:asset-map
da: ## debug:autowiring - Lister les classes/interfaces utilisables pour l'auto-configuration
	$(SYMFONY_CONSOLE) debug:autowiring 
dc: ## debug:config - Afficher la configuration courante
	$(SYMFONY_CONSOLE) debug:config
dcn: ## debug:container - Affiche les services actuels de l'application
	$(SYMFONY_CONSOLE) debug:container
dd: ## debug:dotenv - Lister tous les fichiers .env avec variables et valeurs
	$(SYMFONY_CONSOLE) debug:dotenv
de: ## debug:event-dispatcher - Affiche les listeners configurés pour l'application
	$(SYMFONY_CONSOLE) debug:event-dispatcher
dfi: ## debug:firewall - Afficher les informations sur le pare-feu de l'application
	$(SYMFONY_CONSOLE) debug:firewall 
dfo: ## debug:form - Affiche les informations sur les types de formulaire
	$(SYMFONY_CONSOLE) debug:form 
dm: ## debug:messenger - Lister les messages que vous pouvez dispatcher via les bus de message
	$(SYMFONY_CONSOLE) debug:messenger
dr: ## debug:rouger - Affiche les routes de l'application
	$(SYMFONY_CONSOLE) debug:router
ds: ## debug:serializer - Affiche les informations de serialisation pour les classes
	$(SYMFONY_CONSOLE) debug:serializer
dt: ## debug:translation - Affiche les informations sur les messages de traduction
	$(SYMFONY_CONSOLE) debug:translation
dtw: ## debug:twig - Affiche une liste des fonctions, des filtres, des variable globales et de teste de Twig
	$(SYMFONY_CONSOLE) debug:twig
dv: ## debug:validator - Affiche les contrataintes de validation pour une classe
	$(SYMFONY_CONSOLE) debug:validator

## Doctrine
dccc: ## doctrine:cache:clear-collection-region - Vider le cache d'une région de collection de second niveau.
	$(SYMFONY_CONSOLE) doctrine:cache:clear-collection-region
dcce: ## doctrine:cache:clear-entity-region - Vider le cache d'une région d'entité de second niveau.
	$(SYMFONY_CONSOLE) doctrine:cache:clear-entity-region
dccm: ## doctrine:cache:clear-metadata - Vider tout le cache des métadonnées pour tous les pilotes de cache.
	$(SYMFONY_CONSOLE) doctrine:cache:clear-metadata 
dccq: ## doctrine:cache:clear-query - Vider tout le cache des métadonnées pour tous les pilotes de cache.
	$(SYMFONY_CONSOLE) doctrine:cache:clear-query
dccqr: ##  doctrine:cache:clear-query-region - Vider le cache d'une région de requêtes de second niveau.
	$(SYMFONY_CONSOLE)  doctrine:cache:clear-query-region 
dccr: ## doctrine:cache:clear-result - VVider tout le cache des résultats pour tous les pilotes de cache.
	$(SYMFONY_CONSOLE) 	doctrine:cache:clear-result		
ddc: ## doctrine:database:create - Créer la base de données selon le schéma des entités.
	$(SYMFONY_CONSOLE) doctrine:database:create			
ddd: ## doctrine:database:drop - Supprimer la base de données configurée (en mode developpement).
	$(SYMFONY_CONSOLE) doctrine:database:drop
dddf: ## doctrine:database:drop - Supprimer la base de données configurée (en mode production).
	$(SYMFONY_CONSOLE) doctrine:database:drop --force	
dfl: ## doctrine:fixtures:load - Charger les fixures dans la BDD.
	$(SYMFONY_CONSOLE) doctrine:fixtures:load				
dci: ## doctrine:mapping:info - Afficher les informations sur les entités mappées dans Doctrine.
	$(SYMFONY_CONSOLE) doctrine:mapping:info			
dmc: ## doctrine:migrations:current - Afficher la version actuelle du fichier de migration.
	$(SYMFONY_CONSOLE) doctrine:migrations:current			
dmd: ## doctrine:migrations:diff - Générer une migration en comparant le schéma DB avec les entités.
	$(SYMFONY_CONSOLE) doctrine:migrations:diff 		
dmds: ## doctrine:migrations:dump-schema - Afficher le schéma DB dans une migration.
	$(SYMFONY_CONSOLE) doctrine:migrations:dump-schema			
dme: ## doctrine:migrations:execute - Exécuter une ou plusieurs versions de migration manuellement.
	$(SYMFONY_CONSOLE) doctrine:migrations:execute			
dmg: ## doctrine:migrations:generate - Générer une classe de migration vide.
	$(SYMFONY_CONSOLE) doctrine:migrations:generate			
dml: ## doctrine:migrations:latest - Afficher la dernière version de migration disponible.
	$(SYMFONY_CONSOLE) doctrine:migrations:latest			
dmli: ## doctrine:migrations:list - Lister toutes les migrations et leur statut.
	$(SYMFONY_CONSOLE) doctrine:migrations:list		
dmm: ## doctrine:migrations:migrate - Exécuter les migrations vers la dernière version ou une version spécifique.
	$(SYMFONY_CONSOLE) doctrine:migrations:migrate			
dmr: ## doctrine:migrations:rollup - Fusionner toutes les migrations précédentes dans la version actuelle.
	$(SYMFONY_CONSOLE) doctrine:migrations:rollup 			
dms: ## doctrine:migrations:status - Afficher le statut des migrations.
	$(SYMFONY_CONSOLE) doctrine:migrations:status			
dmsms: ## doctrine:migrations:sync-metadata-storage - Synchroniser le stockage des métadonnées avec la dernière version.
	$(SYMFONY_CONSOLE) doctrine:migrations:sync-metadata-storage			
dmu: ## doctrine:migrations:up-to-date - Vérifier si le schéma est à jour avec les migrations.
	$(SYMFONY_CONSOLE) doctrine:migrations:up-to-date			
dmv: ## doctrine:migrations:version - Ajouter ou supprimer des versions de migration manuellement.
	$(SYMFONY_CONSOLE) doctrine:migrations:version			
dqd: ## doctrine:query:dql - Exécuter une requête DQL directement depuis la ligne de commande.
	$(SYMFONY_CONSOLE) doctrine:query:dql			
dqs: ## doctrine:query:sql - Exécuter une requête SQL directement depuis la ligne de commande
	$(SYMFONY_CONSOLE) doctrine:query:sql			
dsc: ## doctrine:schema:create - Créer la structure de la base de données depuis les entités.
	$(SYMFONY_CONSOLE) doctrine:schema:create			
dsd: ## doctrine:schema:drop - Supprimer les tables de la base de données selon le schéma.
	$(SYMFONY_CONSOLE) doctrine:schema:drop			
dsu: ## doctrine:schema:update - Mettre à jour le schéma DB selon les entités sans perdre les données.
	$(SYMFONY_CONSOLE) doctrine:schema:update			
dcv: ## doctrine:schema:validate - Vérifier la cohérence entre le schéma DB et les entités ORM.
	$(SYMFONY_CONSOLE) doctrine:schema:validate			

## Importmap
ia: ## importmap:audit - Audite les dépendances d'importmap pour vérifier leur sécurité et leur validité.
	$(SYMFONY_CONSOLE) importmap:audit
ii: ## importmap:install - Installe les packages nécessaires définis dans l'importmap.
	$(SYMFONY_CONSOLE) importmap:install
io: ## importmap:outdated - Vérifie les packages d'importmap pour les mises à jour disponibles.
	$(SYMFONY_CONSOLE) importmap:outdated
ir: ## importmap:remove - Supprime un package de l'importmap.
	$(SYMFONY_CONSOLE) importmap:remove
ire: ## importmap:require - Ajoute un nouveau package à l'importmap.
	$(SYMFONY_CONSOLE) importmap:require
iu: ## importmap:update - Met à jour les packages de l'importmap vers leurs versions les plus récentes.
	$(SYMFONY_CONSOLE) importmap:update

## Lint
lc: ## lint:container - Vérifie la validité de la configuration du conteneur de services Symfony.
	$(SYMFONY_CONSOLE) lint:container
lt: ## lint:twig - Analyse les fichiers Twig pour vérifier leur validité syntaxique.
	$(SYMFONY_CONSOLE) lint:twig
lx: ## lint:xliff - Valide la syntaxe des fichiers de traduction au format XLIFF.
	$(SYMFONY_CONSOLE) lint:xliff
ly: ## lint:yaml - Vérifie la syntaxe des fichiers YAML pour s'assurer qu'ils sont valides.
	$(SYMFONY_CONSOLE) lint:yaml

## Mailer
mt: ## mailer:test - Teste la configuration du mailer pour vérifier l'envoi d'e-mails via le service configuré.
	$(SYMFONY_CONSOLE) mailer:test 

## Make
ma: ## make:auth - Crée une authentification pour votre application Symfony.
	$(SYMFONY_CONSOLE) make:auth
mc: ## make:command - Génère une nouvelle commande console Symfony.
	$(SYMFONY_CONSOLE) make:command
mco: ## make:controller - Crée un contrôleur Symfony pour gérer les requêtes HTTP.
	$(SYMFONY_CONSOLE) make:controller
mcr: ## make:crud - Génère un CRUD pour une entité Symfony.
	$(SYMFONY_CONSOLE) make:crud
mdd: ## make:docker:database - Configure une base de données Docker pour votre projet Symfony.
	$(SYMFONY_CONSOLE) make:docker:database
me: ## make:entity - Crée une nouvelle entité Doctrine pour la base de données.
	$(SYMFONY_CONSOLE) make:entity
mf: ## make:fixtures - Génère des fixtures pour insérer des données de test en base.
	$(SYMFONY_CONSOLE) make:fixtures 
mfo: ## make:form - Crée un formulaire Symfony basé sur une entité.
	$(SYMFONY_CONSOLE) make:form
ml: ## make:listener - Crée un écouteur d'événements Symfony.
	$(SYMFONY_CONSOLE) make:listener
mme: ## make:message - Crée une classe de message pour le système de messagerie.
	$(SYMFONY_CONSOLE) make:message
mmm: ## make:messenger-middleware - Crée un middleware pour le composant Messenger.
	$(SYMFONY_CONSOLE) make:messenger-middleware 
mm: ## make:migration - Génère un fichier de migration pour la base de données.
	$(SYMFONY_CONSOLE) make:migration 
mr: ## make:registration-form - Crée un formulaire d'inscription pour les utilisateurs
	$(SYMFONY_CONSOLE) make:registration-form
mrp: ## make:reset-password - Génère un système de réinitialisation de mot de passe.
	$(SYMFONY_CONSOLE) make:reset-password 
ms: ## make:schedule - Crée un planificateur de tâches avec le composant Scheduler.
	$(SYMFONY_CONSOLE) make:schedule 
msc: ## make:security:custom - Crée une sécurité personnalisée dans Symfony.
	$(SYMFONY_CONSOLE) make:security:custom
msf: ## make:security:form-login - Crée un formulaire de connexion avec le composant Security.
	$(SYMFONY_CONSOLE) make:security:form-login
mse: ## make:serializer:encoder - Crée un encodeur personnalisé pour le composant Serializer.
	$(SYMFONY_CONSOLE) make:serializer:encoder
msn: ## make:serializer:normalizer - Crée un normaliseur personnalisé pour le composant Serializer.
	$(SYMFONY_CONSOLE) make:serializer:normalizer 
msco: ## make:stimulus-controller - Crée un contrôleur Stimulus pour interagir avec l'UI.
	$(SYMFONY_CONSOLE) make:stimulus-controller
mtest: ## make:test - Crée des tests unitaires ou fonctionnels pour l'application.
	$(SYMFONY_CONSOLE) make:test
mtc: ## make:twig-component - Crée un composant Twig pour réutiliser des fragments HTML
	$(SYMFONY_CONSOLE) make:twig-component
mte: ## make:twig-extension - Crée une extension Twig personnalisée pour ajouter des filtres ou fonctions.
	$(SYMFONY_CONSOLE) make:twig-extension 
mu: ## make:user - Crée une entité utilisateur pour la gestion des utilisateurs.
	$(SYMFONY_CONSOLE) make:user
mv: ## make:validator - Crée une contrainte de validation personnalisée pour Symfony.
	$(SYMFONY_CONSOLE) make:validator
mvo: ## make:voter - Crée un voter de sécurité pour restreindre les accès.
	$(SYMFONY_CONSOLE) make:voter 
mw: ## make:webhook - Crée un point d’entrée pour recevoir des webhooks externes.
	$(SYMFONY_CONSOLE) make:webhook
test:
	php bin/phpunit

## Messenger
mcon: ## messenger:consume - Lance les workers pour traiter les messages en file d'attente dans les transports configurés.
	$(SYMFONY_CONSOLE) messenger:consume
mfra: ## messenger:failed:remove - Supprime les messages échoués dans la file d'attente.
	$(SYMFONY_CONSOLE) messenger:failed:remove
mfre: ## messenger:failed:retry - Relance les messages échoués en les renvoyant dans la file d'attente pour réexécution.
	$(SYMFONY_CONSOLE) messenger:failed:retry	
mfs: ## messenger:failed:show - Affiche les messages échoués dans la file d'attente.
	$(SYMFONY_CONSOLE) messenger:failed:show
mst: ## messenger:setup-transports - Configure les transports pour le composant Messenger.
	$(SYMFONY_CONSOLE) messenger:setup-transports
msta: ## messenger:stats - Affiche des statistiques sur les transports de messages (ex : messages envoyés, en attente).
	$(SYMFONY_CONSOLE) messenger:stats
msw: ## messenger:stop-workers - Arrête les workers en cours d'exécution pour le composant Messenger.
	$(SYMFONY_CONSOLE) messenger:stop-workers

## Router
rm: ## router:match - Tester si une URL donnée correspond à une route définie dans la configuration Symfony.
	$(SYMFONY_CONSOLE) router:match

## Secrets
sd: ## secrets:decrypt-to-local - Décrypter les secrets et les stocker dans un fichier local pour les utiliser en développement.
	$(SYMFONY_CONSOLE) secrets:decrypt-to-local 
se: ## secrets:encrypt-from-local - Chiffrer les secrets d'un fichier local pour les stocker dans l'infrastructure Symfony.
	$(SYMFONY_CONSOLE) secrets:encrypt-from-local 
sg: ## secrets:generate-keys - Générer une paire de clés publiques/privées pour le chiffrement et le déchiffrement des secrets.
	$(SYMFONY_CONSOLE) secrets:generate-keys
sl: ## secrets:list - Lister tous les secrets actuellement stockés dans l'infrastructure Symfony.
	$(SYMFONY_CONSOLE) secrets:list
sr: ## secrets:remove - Supprimer un secret spécifique de l'infrastructure Symfony.
	$(SYMFONY_CONSOLE) secrets:remove
srl: ## secrets:reveal - Afficher la version décryptée d'un secret chiffré dans l'infrastructure Symfony.
	$(SYMFONY_CONSOLE) secrets:reveal
ss: ## secrets:set - Ajouter ou remplacer un secret dans l'infrastructure Symfony en le chiffrant.
	$(SYMFONY_CONSOLE) secrets:set

## Security
sh: ## security:hash-password - Hash un mot de passe avec les paramètres de sécurité configurés dans votre application.
	$(SYMFONY_CONSOLE) security:hash-password 

## Server
sdu: ## server:dump - Affiche les données de débogage en temps réel depuis le serveur.
	$(SYMFONY_CONSOLE) server:dump 
slo: ## server:log - Montre les journaux du serveur pour surveiller les activités et erreurs en temps réel.
	$(SYMFONY_CONSOLE) server:log

## Translation
te: ## translation:extract - Extrait les messages de traduction de votre code pour générer les fichiers de traductions.
	$(SYMFONY_CONSOLE) translation:extract
tp: ## translation:pull - Récupère les fichiers de traduction depuis un service externe (ex: Crowdin)
	$(SYMFONY_CONSOLE) translation:pull 	
tpu: ## translation:push - Envoie les fichiers de traduction vers un service externe pour traduction.
	$(SYMFONY_CONSOLE) translation:push

## Vich
vmd: ## vich:mapping:debug - Affiche la configuration du mapping pour l'upload de fichiers via VichUploader.
	$(SYMFONY_CONSOLE) vich:mapping:debug 
vmc: ## vich:mapping:debug-class - Affiche les mappings de fichiers pour une classe spécifique dans VichUploader.
	$(SYMFONY_CONSOLE) vich:mapping:debug-class
vml: ## vich:mapping:list-classes - Liste toutes les classes qui utilisent le mapping de VichUploader pour l'upload de fichiers.
	$(SYMFONY_CONSOLE) vich:mapping:list-classes 

## Other
about: ## about - Afficher les informations sur le projet
	$(SYMFONY_CONSOLE) about
cmp: ## completion - Génére un script pour l'autocomplétion des commandes dans le shell.
	$(SYMFONY_CONSOLE) completion

## Help
hc: ## Liste complère des commandes make
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

help: ## Liste toutes les sections et leurs commandes d'aides associées
	@printf "\033[1m%-40s\033[0m \033[1m%s\033[0m\n" "Sections" "Commandes"
	@grep -E '^## [A-Za-z0-9_-]+' $(MAKEFILE_LIST) | sed 's/^## //' | awk '{printf "\033[32m%-40s\033[0m \033[33mh%s\033[0m\n", $$1, $$1}'

h%: ## Affiche les commandes appartenant à la section donnée (ex: hCache, hAssets)
	@awk '/^## '$*'/{flag=1; next} /^## /{flag=0} flag && /^[a-zA-Z0-9_-]+:/' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}'


	



