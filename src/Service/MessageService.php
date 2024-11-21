<?php

namespace App\Service;

class MessageService
{
    private array $messages = [

        /** Entity */
        // Messages pour User
        'user.password.length.error' => 'Le mot de passe est trop court. Nombre de caractères requis : ',
        'user.password.content.error' => 'Le mot de passe doit inclure : une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial.',
        'user.email.unique.error' => 'Email déjà utilisé',
        'user.first_name.length.error' => 'Longueur invalide pour le prénom. Longueur de caractères attendue : entre ',
        'user.first_name.content.error' => 'Le prénom contient des caractères invalides. Seules les lettres sont autorisées.',
        'user.last_name.length.error' => 'Longueur invalide pour le nom de famille. Longueur de caractères attendue : ',
        'user.last_name.content.error' => 'Le nom de famille contient des caractères invalides. Seules les lettres sont autorisées.',
        'user.avatar.url.error' => 'URL de l\'avatar invalide.',
        'user.avatar.empty.error' => 'Aucune URL d\'avatar fournie.',
        'user.etat.error' => 'L’état de l\'utilisateur doit être une instance de DecisionEnum.',
        'user.reaction.already_associated' => 'Réaction déjà associée à cet utilisateur.',
        'user.reaction.add.error' => 'Impossible d\'ajouter la réaction à l\'utilisateur. Erreur : ',
        'user.reaction.not_associated' => 'Réaction non associée à cet utilisateur : ',
        'user.reaction.remove.error' => 'Erreur lors de la suppression de la réaction de l\'utilisateur. Erreur : ',
        'user.post.already_associated' => 'Le post est déjà associé à cet utilisateur : ',
        'user.post.add.error' => 'Impossible d\'ajouter le post à l\'utilisateur. Erreur : ',
        'user.post.not_associated' => 'Le post n\'est pas associé à cet utilisateur : .',
        'user.post.remove.error' => 'Erreur lors de la suppression du post de l\'utilisateur. Erreur : ',  

        // Messages pour Category
        'category.titre.already_use' => 'Ce titre est déjà utilisé.',
        'category.description.length.error' => 'La description est trop long. Nombre de caractères maximum entre : ',
        'category.description.string.error' => 'La description doit être une chaîne de caractères.',
        'category.post.already_associated' => 'Réaction déjà associée à ce post.',
        'category.post.not_associated' => 'Réaction n’est pas associée à ce post.',
        'category.description.not_blank' => 'La description ne peut pas être vide.',

        // Messages pour Post
        'post.content.length.error' => 'Longueur invalide pour le contenu. Longueur de caractères attendue :',

        // Message pour Reaction
        'reaction.avis.error' => 'L’avis à la réaction doit être une instance de DecisionEnum. Valeurs possibles : ',
        'reaction.moderation.error' => 'La moderation à la réaction doit être une instance de DecisionEnum. Valeurs possibles : ',
        'reaction.ip_address.error' => 'Adresse IP invalide.',

        // Messages pour Tag
        'tag.description.length.error' => 'La description est trop long. Nombre de caractères maximum : ',
        'tag.description.string.error' => 'La description doit être une chaîne de caractères.',
        'tag.post.already_associated' => 'Le tag est déjà associé à ce post : ',
        'tag.post.add.error' => 'Impossible d\'ajouter le post au tag. Erreur : ',
        'tag.post.not_associated' => 'Le post n\'est pas associé à ce tag : .',
        'tag.post.remove.error' => 'Erreur lors de la suppression du post au tag. Erreur : ',
        'tag.description.not_blank' => 'La description ne peut pas être vide.',  

        // Messages pour Thumbail

        /** Enumérations */
        // Messages pour RoleEnum
        'role.error' => 'Le rôle n’est pas valide :',

        // Messages pour DecisionEnum
        'decision.error' => 'La décision n’est pas valide :',

        // Messages pour EtatEnum
        'etat.error' => 'L’état n’est pas valide :',

        /** Traits */
        // Messages pour CommonMethodsEntityTrait

        // Messages pour DateTrait

        // Messages pour SlugTrait
        'slug.already_use' => 'Ce slug est déjà utilisé.',

        /** Messsage généraux */
        'validation.error' => 'Les données fournies ne sont pas valides.',
        'entity.not_found' => 'L’entité demandée n’existe pas.',
    ];

    /**
     * Récupère un message par clé.
     *
     * @param string $key Clé du message.
     * @return string Le message correspondant.
     */
    public function getMessage(string $key): string
    {
        return $this->messages[$key] ?? 'Message non défini.';
    }
}