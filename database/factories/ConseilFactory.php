<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory pour les conseils santé / météo.
 */
class ConseilFactory extends Factory
{
    private static array $conseilsData = [
        // hydratation
        ['titre' => 'Boire au moins 2 litres d\'eau par jour', 'categorie' => 'hydratation', 'niveau' => 'vert',
         'contenu' => 'Pendant les périodes de chaleur, il est essentiel de s\'hydrater régulièrement. Buvez de l\'eau fraîche toutes les heures, même sans ressentir de soif. Évitez les boissons sucrées et alcoolisées qui favorisent la déshydratation.'],
        ['titre' => 'Boire 3 litres d\'eau minimum en cas de canicule', 'categorie' => 'hydratation', 'niveau' => 'orange',
         'contenu' => 'En situation de canicule, les besoins en eau augmentent significativement. Augmentez votre consommation à au moins 3 litres par jour. Préférez l\'eau plate, les jus de fruits frais et les bouillons de légumes.'],
        ['titre' => 'Hydratation d\'urgence en cas de coup de chaleur', 'categorie' => 'hydratation', 'niveau' => 'rouge',
         'contenu' => 'En cas de coup de chaleur, l\'hydratation est vitale. Buvez immédiatement et appelez le SAMU. Appliquez de l\'eau froide sur le corps et placez-vous dans un endroit frais.'],
        ['titre' => 'Consommer des fruits et légumes riches en eau', 'categorie' => 'hydratation', 'niveau' => 'jaune',
         'contenu' => 'La pastèque, le concombre, les tomates et les melons contiennent plus de 90% d\'eau. Intégrez-les dans votre alimentation quotidienne pour maintenir un bon niveau d\'hydratation.'],

        // energie
        ['titre' => 'Économiser l\'énergie pendant les heures de pointe', 'categorie' => 'energie', 'niveau' => 'orange',
         'contenu' => 'Entre 12h et 18h, évitez d\'utiliser les appareils énergivores (four, lave-linge, lave-vaisselle). Préférez les ventilateurs aux climatiseurs. Éteignez les lumières et appareils en veille.'],
        ['titre' => 'Utiliser la climatisation avec modération', 'categorie' => 'energie', 'niveau' => 'jaune',
         'contenu' => 'Réglez votre climatiseur à 26°C minimum. Un écart de plus de 8°C entre l\'intérieur et l\'extérieur est néfaste pour la santé. Fermez portes et fenêtres quand la climatisation fonctionne.'],
        ['titre' => 'Plan de délestage en cas de coupure électrique', 'categorie' => 'energie', 'niveau' => 'rouge',
         'contenu' => 'Préparez une lampe torche et des bougies. Identifiez les lieux de fraîcheur publics (médiathèques, centres commerciaux). Conservez une réserve d\'eau fraîche dans des thermos.'],

        // sante
        ['titre' => 'Protéger les personnes vulnérables de la chaleur', 'categorie' => 'sante', 'niveau' => 'orange',
         'contenu' => 'Vérifiez régulièrement l\'état des personnes âgées, des enfants en bas âge et des malades chroniques. Assurez-vous qu\'ils restent hydratés et dans des lieux frais. Signalez-les au numéro d\'urgence si nécessaire.'],
        ['titre' => 'Reconnaître les signes d\'un coup de chaleur', 'categorie' => 'sante', 'niveau' => 'rouge',
         'contenu' => 'Symptômes : température corporelle > 40°C, peau chaude et sèche, confusion mentale, perte de conscience. C\'est une urgence médicale. Appelez immédiatement le 15 (SAMU).'],
        ['titre' => 'Adapter les médicaments en période de chaleur', 'categorie' => 'sante', 'niveau' => 'jaune',
         'contenu' => 'Certains médicaments (diurétiques, antihypertenseurs, antipsychotiques) augmentent la sensibilité à la chaleur. Consultez votre médecin pour adapter votre traitement pendant la canicule.'],
        ['titre' => 'Éviter l\'exposition solaire aux heures chaudes', 'categorie' => 'sante', 'niveau' => 'jaune',
         'contenu' => 'De 12h à 16h, évitez toute exposition directe au soleil. Portez un chapeau, des lunettes de soleil et des vêtements légers. Utilisez une crème solaire indice 50+.'],

        // equipements
        ['titre' => 'Équipements essentiels contre la chaleur', 'categorie' => 'equipements', 'niveau' => 'vert',
         'contenu' => 'Procurez-vous : ventilateur, brumisateur, stores ou volets opaques, thermomètre, thermos isotherme pour l\'eau fraîche, kit de premiers secours.'],
        ['titre' => 'Brumisateurs et ventilateurs : mode d\'emploi', 'categorie' => 'equipements', 'niveau' => 'jaune',
         'contenu' => 'Le brumisateur abaisse la température ressentie de 3 à 5°C. Combinez-le avec un ventilateur pour maximiser l\'effet rafraîchissant. Remplissez-le avec de l\'eau fraîche (pas trop froide).'],

        // habitat
        ['titre' => 'Fermer les volets pendant la journée', 'categorie' => 'habitat', 'niveau' => 'vert',
         'contenu' => 'Fermez les volets et rideaux occultants dès le matin pour bloquer la chaleur. Aérez uniquement la nuit quand la température extérieure descend en dessous de la température intérieure.'],
        ['titre' => 'Créer une pièce fraîche dans votre logement', 'categorie' => 'habitat', 'niveau' => 'orange',
         'contenu' => 'Identifiez la pièce la plus fraîche (nord, en sous-sol). Équipez-la d\'un ventilateur et de stores occultants. Passez-y les heures les plus chaudes de la journée.'],
        ['titre' => 'Isoler son logement pour l\'été', 'categorie' => 'habitat', 'niveau' => 'vert',
         'contenu' => 'Les logements bien isolés restent frais plus longtemps. Posez des joints de portes et fenêtres, installez des stores extérieurs. La végétation autour du logement aide aussi à le rafraîchir naturellement.'],

        // deplacement
        ['titre' => 'Éviter les déplacements aux heures chaudes', 'categorie' => 'deplacement', 'niveau' => 'jaune',
         'contenu' => 'Planifiez vos déplacements tôt le matin (avant 10h) ou en soirée (après 19h). Si vous devez sortir en journée, choisissez des itinéraires ombragés et emportez de l\'eau.'],
        ['titre' => 'Sécurité routière par forte chaleur', 'categorie' => 'deplacement', 'niveau' => 'orange',
         'contenu' => 'La chaleur augmente la fatigue et diminue la vigilance. Faites des pauses régulières (toutes les 2 heures). Aérez votre véhicule avant de monter. Ne laissez jamais un enfant ou animal seul dans une voiture.'],
        ['titre' => 'Transport en commun et chaleur', 'categorie' => 'deplacement', 'niveau' => 'vert',
         'contenu' => 'Préférez les transports en commun climatisés à la voiture. Evitez les heures de pointe. Dans les gares et stations, cherchez les espaces ombragés ou climatisés pour attendre.'],
    ];

    public function definition(): array
    {
        $conseil = $this->faker->randomElement(self::$conseilsData);

        return [
            'titre'               => $conseil['titre'],
            'contenu'             => $conseil['contenu'],
            'categorie'           => $conseil['categorie'],
            'niveau_alerte_cible' => $conseil['niveau'],
            'icone'               => $this->getIconeForCategorie($conseil['categorie']),
            'actif'               => $this->faker->boolean(85),
        ];
    }

    /** Retourne une icône FontAwesome selon la catégorie */
    private function getIconeForCategorie(string $categorie): string
    {
        return match ($categorie) {
            'hydratation' => 'fa-solid fa-droplet',
            'energie'     => 'fa-solid fa-bolt',
            'sante'       => 'fa-solid fa-heart-pulse',
            'equipements' => 'fa-solid fa-kit-medical',
            'habitat'     => 'fa-solid fa-house',
            'deplacement' => 'fa-solid fa-car',
            default       => 'fa-solid fa-circle-info',
        };
    }
}
